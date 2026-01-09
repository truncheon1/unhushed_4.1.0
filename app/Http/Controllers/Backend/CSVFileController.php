<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\ActiveSubscriptions;
use App\Models\Organizations;
use App\Models\ProductAssignments;
use App\Models\Products;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CSVFileController extends Controller {
    
    const COLUMNS = [
        'Ignore This Column',
        'First Name',
        'Last Name',
        'Email',
        'Curricula/Training'
    ];

    public function upload(Request $request) {
        $res = [];
        $source = '';
        $type = 'text';
        if ($request->file('file')) {
            //we have a file to parse
            $file = $request->file('file');
            if (filesize($file)) {
                $filenfo = $file->getClientOriginalName();
                $extension = pathinfo($filenfo, PATHINFO_EXTENSION);
                if($extension != "csv"){
                    return response()->json(['success'=>false]);
                }
                $file_name = bin2hex(random_bytes(10)) . '.' . strtolower($extension);
                $file->move(config('constant.hold'), $file_name);
                $csv = \League\Csv\Reader::createFromPath(config('constant.hold'). "/" . $file_name, 'r');
                $records = $csv->getRecords();
                foreach($records as $record){
                    $res[] = $record;
                }
                $source = config('constant.hold') . "/" . $file_name;
                $type = "file";
            }
        } else if ($request->input('text')) {
            //we have text to parse
            $text = $request->input('text');
            $rows = explode("\n", $text);
            //split by cells
            foreach ($rows as $r) {
                $res[] = explode("\t", $r);
            }
            $file_name = bin2hex(random_bytes(10)) . '.csv';
            Storage::put($file_name, $request->input('text'));
            $source = $file_name;
            $type = "text";
        }
        if(count($res)){
            $bi = new \App\Models\BulkImports();
            $bi->type = $type;
            $bi->resource = $source;
            $bi->save();
            return response()->json(['success'=>true, 'sample'=>array_slice($res, 0, 5), 'total'=>count($res), 'source'=>$source, 'res'=>$bi]);
        }
        return response()->json(['success'=>false]);
    }
    
    public function action_org(Request $req){
        if(!$req->input('source')){
            return response()->json(['success'=>false, 'message'=>"Invalid Source to parse!"]);
        }
        $res = $req->input('source');
        
        $res = \App\Models\BulkImports::find($res['id']);
        if(!$res){
            return response()->json(['success'=>false, 'message'=>"Invalid Source to parse!"]);
        }
        try{
            if($res->type == "file"){
                $csv = \League\Csv\Reader::createFromPath($res->resource, 'r');
                $res = $csv->getRecords();
                $records = [];
                foreach($res as $record){
                    $records[] = $record;
                }
            }else{
                $string = Storage::get($res->resource);
                $rows = explode("\n", $string);
                //split by cells
                $records = [];
                foreach ($rows as $r) {
                    $records[] = explode("\t", $r);
                }
            
            }
        }catch(\League\Csv\UnavailableStream $e){
            return response()->json(['success'=>false, 'message'=>"Invalid Source to parse!"]);
        }
        if(!$req->input('action')){
            return response()->json(['success'=>false, 'message'=>"Please choose an action!"]);
        }
        $ignore = $req->input('ignore') ?? "false";
        if($ignore !== "false"){
            array_shift($records);
        }
        switch ($req->input('action')){
            case 'import':
                return $this->import_users(
                    $records, 
                    $req->input('columns'), 
                    auth()->user()->org_id
                    );
                break;
            case 'delete':
                return $this->delete_users_org(
                    $records,
                    $req->input('columns')
                    );
                break;
            case 'add':
                return $this->add_curricula_org(
                    $records, 
                    $req->input('columns'),
                    false,
                    $req->input('curricula')
                    );
                break;
            case 'rem':
                return $this->remove_curricula_org(
                    $records, 
                    $req->input('columns'),
                    false,
                    $req->input('curricula')
                    );
                break;
            case 'org':
                return $this->remove_org(
                    $records, 
                    $req->input('columns'),
                    false,
                    );
                break;
            case 'org_add':
                return $this->add_org(
                    $records, 
                    $req->input('columns'),
                    auth()->user()->org_id, 
                    false,
                    );
                break;
        }
    }
    
    /** ORGANIZATION SPECIFIC **/
    private function delete_users_org($records, $cols, $skip_first = false){
        $skipped = [];
        $added = [];
        $errors = [];
        
        //let's map. We need First Name|LastName and  Email as a minimum
        $name = false;
        $email = false;
        $fname = -1;
        $lname = -1;
        $em = -1;
        for($x = 0; $x < count($cols); $x++){
            $col = $cols[$x];
            if($col == 1 || $col == 2){
                if($col == 1){
                    $fname = $x;
                }
                if($col == 2){
                    $lname = $x;
                }
                $name = true;
            }
            if($col == 3){
                $em = $x;
                $email = true;
            }
        }
        if(!$name || !$email){
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! First Name OR/AND Last Name and Email are required!"]);
        }
        $ignored = false;
        $x = 0;
        foreach($records as $row){
            $x++;
           
            
            $email = $this->is_email(trim($row[$em]));
            if($email === false){
                $errors[] = [$x, 'Not a valid email'];
                continue;
            }
            
            $user = User::where('email', $email)->where('org_id', auth()->user()->org_id)->first();
            if(!$user){
                $skipped[] = [$x, 'User not found'];
                continue;
            }
            
            //protect admin users
            if($user->hasRole('admin')){
                $ignored[] = [$x, "User is admin!"];
                continue;
            }
            
            
            //check if user is assigned to any packages remove them and add the license back to org
            if($user->org_id != 0){
                $subscriptions = ActiveSubscriptions::where('org_id', $user->org_id)
                    ->where('category', '=', '1')
                    ->orderBy('product_id', 'desc') 
                    ->get();
                
                foreach($subscriptions as $subscription){
                    if($user->is_assigned($subscription->product_id)){
                        $packageAssigned = ProductAssignments::where('user_id', $user->id)->where('product_id', $subscription->product_id)->first();
                        $packageAssigned->delete();
                        $subscription->used -= 1;
                        $subscription->save();
                    }
                }
            }
            
            if(!$user->hasRole('admin')){
                $user->delete();
                
            }
            
            $added[] = $row;

            
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
    
    /**
     * @todo need to change the logic here: collect all the packages they want to assign, see how many users are already assigned
     * see if the amount is less then bought packages, if all good, proceed otherwise return an error with the reasons
     */
    private function add_curricula_org($records, $cols, $skip_first = false, $curricula){
        $skipped = [];
        $added = [];
        $errors = [];
        //find the email field
        $email = 0;
        $seek_col = null;
        $x = 0;
        foreach($cols as $col){
            if($col == 3){
                $email = $x;
            }
            
            if($col == 4 && $curricula == 'hinted'){
                $seek_col = $x;
            }
            
            $x++;
        }
        
        if(is_null($seek_col) && $curricula == 'hinted'){
            //booboo, selected hinted but has not selected a col for that
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! You selected hinted but have not selected a column to hint to!"]);
        }
        
        
        if(is_null($seek_col)){
            if(substr($curricula, 0, 1) == 'p'){
                //refactor during type update was 0 should be 1
                $type = 1;
                $id = str_replace("p-", "", $curricula);
                $subscription = Products::find($id);
            }else{
                //refactor during type update was 4 should be 7
                $type = 7;
                $id = str_replace("t-", "", $curricula);
                $subscription = Products::find($id);
            }
            
            $activeSubscriptions = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
                    ->where('product_id', $id)->where('category', $type)->first();
            $total = !$activeSubscriptions ? 0 : ($activeSubscriptions->total - $activeSubscriptions->used);
            
            if($total < count($records) ){
                return response()->json(['success'=>false, 'message'=>"You are trying to assign ".count($records)." but you have ".$total." licenses available"]);
            }

        }
        
        if($seek_col){
            //need to compile a list of all the packages and licenses to see if they are enough
            $skeys = [];
            foreach($records as $rec){
                $seek_c = "%".str_replace("school", "", strtolower(trim($rec[$seek_col])))."%";
                $subscription = \App\Models\Products::where('category', 1)->where('name', 'LIKE', $seek_c)->first();
                if($subscription){
                    $key = $subscription->id."-0";
                    if(!isset($skeys[$key]))
                        $skeys[$key] = [
                            'count'=>1,
                            's'=>$subscription,
                            'type'=>0
                        ];
                    else
                        $skeys[$key]['count']++;
                }else{
                    //training?
                    
                    $subscription = Products::where('name', 'LIKE', $seek_c)->first();
                    if($subscription){
                        $key = $subscription->id."-4";
                        if(!isset($skeys[$key]))
                            $skeys[$key] = [
                                'count'=>1,
                                's'=>$subscription,
                                'type'=>4
                            ];
                        else
                            $skeys[$key]['count']++;
                    }
                }
            }
            $errors = [];
            //loop and see if they are enough
            foreach($skeys as $key=>$values){
                $activeSubscriptions = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
                        ->where('product_id', $values['s']->id)->where('category', $values['type'])->first();
                $total = !$activeSubscriptions ? 0 : ($activeSubscriptions->total - $activeSubscriptions->used);
                if($total < $values['count']){
                    $errors[] = "You do not have enough licenses for ".$subscription->name." (".$total." licenses left, trying to assign ".$values['count'].")";
                }
            }
            
            //stop here if not enough licenses
            if(count($errors)){
                return response()->json(['success'=>false, 'message'=>implode("<br/>", $errors)]);
            }
        }
        
        
        $r = 0;
        foreach($records as $rec){
            $r++;
            //find the user by email 
            $user = User::where('email', trim($rec[$email]))->first();
            if(!$user){
                $errors[] = [$r, 'User not found'];
                continue;
            }
            
            //found the user, check for subscription access by hinted col
            if($seek_col){
                //need to find the package or subscription by name
                $seek_c = "%".str_replace("school", "", strtolower(trim($rec[$seek_col])))."%";
                $subscription = \App\Models\Products::where('category', 1)->where('name', 'LIKE', $seek_c)->first();
                
                if($subscription){
                    //refactor during type update was 0 should be 1
                    $type = 1; // curricula category mapping
                    if($user->is_assigned($subscription->id)){
                        $errors[] = [$r, 'User is already assigned to this Curricula'];
                        continue;
                    }
                }else{
                
                    $subscription = Products::where('name', 'LIKE', $seek_c)->first();
                    if($subscription){
                        //refactor during type update was 4 should be 7
                        $type = 7; // training category mapping
                        if($user->is_assigned_training($subscription->id)){
                            $errors[] = [$r, 'User is already assigned to this Training'];
                            continue;
                        }

                    }
                }
                
                if(!$subscription){
                    $errors[] = [$r, 'The hinted Curricula/Training was not found: '.$rec[$seek_col]];
                    continue;
                }
            }else{
                if($user->is_assigned_type($subscription->id, $type)){
                    $errors[] = [$r, 'User is already assigned to this Curricula/Training'];
                    continue;
                }
            }
            
            //all good, assign user to curricula/training
            $packageAssigned = new \App\Models\ProductAssignments();
            $packageAssigned->product_id = $subscription->id;
            $packageAssigned->user_id = $user->id;
            $packageAssigned->active = 1;
            $packageAssigned->category = $type;
            $packageAssigned->save();
            $added[] = $x;
            
            
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
    private function remove_curricula($records, $cols, $skip_first = false, $curricula){
        $skipped = [];
        $added = [];
        $errors = [];
        //find the email field
        $email = 0;
        $seek_col = null;
        $x = 0;
        foreach($cols as $col){
            if($col == 3){
                $email = $x;
            }
            
            if($col == 4 && $curricula == 'hinted'){
                $seek_col = $x;
            }
            
            $x++;
        }
        
        if(is_null($seek_col) && $curricula == 'hinted'){
            //booboo, selected hinted but has not selected a col for that
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! You selected hinted but have not selected a column to hint to!"]);
        }
        
        if(is_null($seek_col)){
            if(substr($curricula, 0, 1) == 'p'){
                //refactor during type update was 0 should be 1
                $type = 1;
                $id = str_replace("p-", "", $curricula);
                $subscription = Products::find($id);
            }else{
                //refactor during type update was 4 should be 7
                $type = 7;
                $id = str_replace("t-", "", $curricula);
                $subscription = Products::find($id);
            }
        }
        $r = 0;
        foreach($records as $rec){
            $r++;
            //find the user by email 
            $user = User::where('email', trim($rec[$email]))->first();
            if(!$user){
                $errors[] = [$r, 'User not found'];
                continue;
            }
            
            //found the user, check for subscription access by hinted col
            if($seek_col){
                //need to find the package or subscription by name
                $seek_c = "%".str_replace("school", "", strtolower(trim($rec[$seek_col])))."%";
                $subscription = \App\Models\Products::where('category', 1)->where('name', 'LIKE', $seek_c)->first();
                
                if($subscription){
                    //refactor during type update was 0 should be 1
                    $type = 0;
                    if(!$user->is_assigned($subscription->id)){
                        $errors[] = [$r, 'User is not assigned to this Curricula'];
                        continue;
                    }
                }else{

                    $subscription = Products::where('name', 'LIKE', $seek_c)->first();
                    if($subscription){
                        //refactor during type update was 4 should be 7
                        $type = 4;
                        if(!$user->is_assigned_training($subscription->id)){
                            $errors[] = [$r, 'User is not assigned to this Training'];
                            continue;
                        }

                    }
                }
                
                if(!$subscription){
                    $errors[] = [$r, 'The hinted Curricula/Training was not found: '.$rec[$seek_col]];
                    continue;
                }
            }else{
                if(!$user->is_assigned_type($subscription->id, $type)){
                    $errors[] = [$r, 'User is not assigned to this Curricula/Training'];
                    continue;
                }
            }
            $packageAssigned = \App\Models\ProductAssignments::where('user_id', $user->id)->where('product_id', $subscription->id)->where('category', $type)->first();
            $packageAssigned->delete();
            $added[] = $x;
            
            
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
    
    public function action(Request $req){
        
        if(!$req->input('source')){
            return response()->json(['success'=>false, 'message'=>"Invalid Source to parse!"]);
        }
        $res = $req->input('source');
        
        $res = \App\Models\BulkImports::find($res['id']);
        if(!$res){
            return response()->json(['success'=>false, 'message'=>"Invalid Source to parse!"]);
        }
        
        try{
            if($res->type == "file"){
                $csv = \League\Csv\Reader::createFromPath($res->resource, 'r');
                $res = $csv->getRecords();
                $records = [];
                foreach($res as $record){
                    $records[] = $record;
                }
                
            }else{
                $string = Storage::get($res->resource);
                $rows = explode("\n", $string);
                //split by cells
                $records = [];
                foreach ($rows as $r) {
                    $records[] = explode("\t", $r);
                }
            
            }
        }catch(\League\Csv\UnavailableStream $e){
            return response()->json(['success'=>false, 'message'=>"Invalid Source to parse!"]);
        }
        
        if(!$req->input('action')){
            return response()->json(['success'=>false, 'message'=>"Please choose an action!"]);
        }
        
        $ignore = $req->input('ignore') ?? "false";
        if($ignore !== "false"){
            array_shift($records);
        }
        
        switch ($req->input('action')){
            case 'import':
                $org = $req->input('organization') ?? 0;
                if($org == -1){
                    //add new
                    $organization = $this->add_organization($req->input('oname') ?? '', $req->input('oemail') ?? '');
                    if($organization[0] == false){
                        return response()->json(['success'=>false, 'message'=>$organization[1]]);
                    }
                    $org = $organization[1];
                }
                return $this->import_users(
                    $records, 
                    $req->input('columns'), 
                    $org, 
                    false
                    );
                break;
            case 'delete':
                return $this->delete_users(
                    $records,
                    $req->input('columns'),
                    false
                    );
                break;
            case 'add':
                return $this->add_curricula(
                    $records, 
                    $req->input('columns'),
                    false,
                    $req->input('curricula')
                    );
                break;
            case 'rem':
                return $this->remove_curricula(
                    $records, 
                    $req->input('columns'),
                    false,
                    $req->input('curricula')
                    );
                break;
            case 'org':
                return $this->remove_org(
                    $records, 
                    $req->input('columns'),
                    false,
                    );
                break;
            case 'org_add':
                $org = $req->input('organization') ?? 0;
                if($org == -1){
                    //add new
                    $organization = $this->add_organization($req->input('oname') ?? '', $req->input('oemail') ?? '');
                    if($organization[0] == false){
                        return response()->json(['success'=>false, 'message'=>$organization[1]]);
                    }
                    $org = $organization[1];
                }
                dd($org);
                return $this->add_org(
                    $records, 
                    $req->input('columns'),
                    $org, 
                    false,
                    );
                break;
        }
    }
    
    private function add_organization($name, $email){
        if(strlen($name) < 5){
            return [false, "The organization name must be at least 5 characters long"];
        }
        $is = Organizations::where('name', 'LIKE', $name)->first();
        if($is){
            return [false, "An organization with the same name already exists!"];
        }
        $org = new Organizations();
        $org->name = $name;
        $org->email_match = $email;
        $org->save();
        return [true, $org->id];
    }
    
    private function add_org($records, $cols, $org_id, $skip_first = false){
        $skipped = [];
        $added = [];
        $errors = [];
        //find the email field
        $email = 0;
        $seek_col = null;
        $x = 0;
        foreach($cols as $col){
            if($col == 3){
                $email = $x;
                break;
            }
            $x++;
        }
        $r = 0;
        foreach($records as $rec){
            $r++;
            //find the user by email 
            $user = User::where('email', trim($rec[$email]))->first();
            if(!$user){
                $errors[] = [$r, 'User not found'];
                continue;
            }
            if($user->org_id != 0){
                $errors[] = [$r, 'User is already assigned to another organization'];
                continue;
            }
            $user->org_id = $org_id;
            $user->save();
            $added[] = $x;
        }
        
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
    private function remove_org($records, $cols, $skip_first = false){
        $skipped = [];
        $added = [];
        $errors = [];
        //find the email field
        $email = 0;
        $seek_col = null;
        $x = 0;
        foreach($cols as $col){
            if($col == 3){
                $email = $x;
                break;
            }
            $x++;
        }
        $r = 0;
        foreach($records as $rec){
            $r++;
            //find the user by email 
            $user = User::where('email', trim($rec[$email]))->where('org_id', auth()->user()->org_id)->first();
            if(!$user){
                $errors[] = [$r, 'User not found'];
                continue;
            }
            $user->org_id = 0;
            $user->save();
            $added[] = $x;
        }
        
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
   
    private function remove_curricula_org($records, $cols, $skip_first = false, $curricula){
        $skipped = [];
        $added = [];
        $errors = [];
        //find the email field
        $email = 0;
        $seek_col = null;
        $x = 0;
        foreach($cols as $col){
            if($col == 3){
                $email = $x;
            }
            
            if($col == 4 && $curricula == 'hinted'){
                $seek_col = $x;
            }
            
            $x++;
        }
        
        if(is_null($seek_col) && $curricula == 'hinted'){
            //booboo, selected hinted but has not selected a col for that
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! You selected hinted but have not selected a column to hint to!"]);
        }
        
        if(is_null($seek_col)){
            if(substr($curricula, 0, 1) == 'p'){
                //refactor during type update was 0 should be 1
                $type = 1;
                $id = str_replace("p-", "", $curricula);
                $subscription = Products::find($id);
            }else{
                //refactor during type update was 4 should be 7
                $type = 7;
                $id = str_replace("t-", "", $curricula);
                $subscription = Products::find($id);
            }
        }
        $r = 0;
        foreach($records as $rec){
            $r++;
            //find the user by email 
            $user = User::where('email', trim($rec[$email]))->first();
            if(!$user){
                $errors[] = [$r, 'User not found'];
                continue;
            }
            
            //found the user, check for subscription access by hinted col
            if($seek_col){
                //need to find the package or subscription by name
                
                $subscription = \App\Models\Products::where('category', 1)->where('name', 'LIKE', trim($rec[$seek_col]))->first();
                
                if($subscription){
                    //refactor during type update was 0 should be 1
                    $type = 0;
                    if(!$user->is_assigned($subscription->id)){
                        $errors[] = [$r, 'User is not assigned to this Curricula'];
                        continue;
                    }
                }else{
                    $subscription = Products::where('name', 'LIKE', trim($rec[$seek_col]))->first();
                    if($subscription){
                        //refactor during type update was 4 should be 7
                        $type = 4;
                        if(!$user->is_assigned_training($subscription->id)){
                            $errors[] = [$r, 'User is not assigned to this Training'];
                            continue;
                        }

                    }
                }
                
                if(!$subscription){
                    $errors[] = [$r, 'The hinted Curricula/Training was not found: '.$rec[$seek_col]];
                    continue;
                }
            }else{
                if(!$user->is_assigned_type($subscription->id, $type)){
                    $errors[] = [$r, 'User is not assigned to this Curricula/Training'];
                    continue;
                }
            }
            $packageAssigned = ProductAssignments::where('user_id', $user->id)->where('product_id', $subscription->id)->where('category', $type)->first();
            $packageAssigned->delete();
            $ac = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
                    ->where('product_id', $subscription->id)->where('category', $type)->first();
            if($ac){
                $ac->used = $ac->used -1;
                $ac->save();
            }
            $added[] = $x;
            
            
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
    
    private function add_curricula($records, $cols, $skip_first = false, $curricula){
        $skipped = [];
        $added = [];
        $errors = [];
        //find the email field
        $email = 0;
        $seek_col = null;
        $x = 0;
        foreach($cols as $col){
            if($col == 3){
                $email = $x;
            }
            
            if($col == 4 && $curricula == 'hinted'){
                $seek_col = $x;
            }
            
            $x++;
        }
        
        if(is_null($seek_col) && $curricula == 'hinted'){
            //booboo, selected hinted but has not selected a col for that
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! You selected hinted but have not selected a column to hint to!"]);
        }
        
        if(is_null($seek_col)){
            if(substr($curricula, 0, 1) == 'p'){
                //refactor during type update was 0 should be 1
                $type = 1;
                $id = str_replace("p-", "", $curricula);
                $subscription = Products::find($id);
            }else{
                //refactor during type update was 4 should be 7
                $type = 7;
                $id = str_replace("t-", "", $curricula);
                $subscription = Products::find($id);
            }
        }
        $r = 0;
        foreach($records as $rec){
            $r++;
            //find the user by email 
            $user = User::where('email', trim($rec[$email]))->first();
            if(!$user){
                $errors[] = [$r, 'User not found'];
                continue;
            }
            
            //found the user, check for subscription access by hinted col
            if($seek_col){
                //need to find the package or subscription by name
                $seek_c = "%".str_replace("school", "", strtolower(trim($rec[$seek_col])))."%";
                $subscription = \App\Models\Products::where('category', 1)->where('name', 'LIKE', $seek_c)->first();
                
                if($subscription){
                    //refactor during type update was 0 should be 1
                    $type = 0;
                    if($user->is_assigned($subscription->id)){
                        $errors[] = [$r, 'User is already assigned to this Curricula'];
                        continue;
                    }
                }else{
                    $subscription = Products::where('name', 'LIKE', $seek_c)->first();
                    if($subscription){
                        //refactor during type update was 4 should be 7
                        $type = 4;
                        if($user->is_assigned_training($subscription->id)){
                            $errors[] = [$r, 'User is already assigned to this Training'];
                            continue;
                        }

                    }
                }
                
                if(!$subscription){
                    $errors[] = [$r, 'The hinted Curricula/Training was not found: '.$rec[$seek_col]];
                    continue;
                }
            }else{
                if($user->is_assigned_type($subscription->id, $type)){
                    $errors[] = [$r, 'User is already assigned to this Curricula/Training'];
                    continue;
                }
            }
            
            //all good, assign user to curricula/training
            $packageAssigned = new \App\Models\ProductAssignments();
            $packageAssigned->product_id = $subscription->id;
            $packageAssigned->user_id = $user->id;
            $packageAssigned->active = 1;
            $packageAssigned->category = $type;
            $packageAssigned->save();
            $added[] = $x;
            
            
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
    }
    
    private function import_users($records, $cols, $organization = 0, $skip_first = false){
        
        $skipped = [];
        $added = [];
        $errors = [];
        
        //let's map. We need First Name|LastName and  Email as a minimum
        $name = false;
        $email = false;
        $fname = -1;
        $lname = -1;
        $em = -1;
        for($x = 0; $x < count($cols); $x++){
            $col = $cols[$x];
            if($col == 1 || $col == 2){
                if($col == 1){
                    $fname = $x;
                }
                if($col == 2){
                    $lname = $x;
                }
                $name = true;
            }
            if($col == 3){
                $em = $x;
                $email = true;
            }
        }
        if(!$name || !$email){
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! First Name OR/AND Last Name and Email are required!"]);
        }
        
        //all good, map each user
        
        $x = 0;
        $ignored = false;
        foreach($records as $row){
            $x++;
            
            
            $email = $this->is_email(trim($row[$em]));
            if($email === false){
                $errors[] = [$x, 'Email not found!'];
                continue;
            }
            
            $user = User::where('email', $email)->first();
            if($user){
                $skipped[] = [$x, 'User already in the system!'];
                continue;
            }
            
            $user = new User();
            $user->name = $this->get_name($row, $fname, $lname);
            $user->email = $email;
            $user->password = Hash::make(bin2hex(random_bytes(10))); //random 10 char password
            $user->needs_update = 1;
            $user->org_id = $organization;
            $user->save();

            //add user role
            $user->addRole(Roles::find(1)); //basic user role

            //send email
            $validation = new \App\Models\Validations();
            $validation->getNewValidation($user->id);
            $email = new \App\Mail\VerifyEmail($validation, $user);
            $job = new \App\Jobs\ProcessEmail($validation, $user, $email);
            $job->dispatch($validation, $user, $email);
            //add to active campaign
            $this->ac = new \ActiveCampaign(\Config::get('services.activecampaign.url'), \Config::get('services.activecampaign.key'));
            $list_id = 2; //new user
            $nl_id = 1;// newsletter
            $full_name  = explode(" ", $user->name);
            $last_name  = array_pop($full_name);
            $first_name = count($full_name)? implode(" ", $full_name) : "";
            $contact = array(
                "email"              => $user->email,
                "first_name"         => $first_name,
                "last_name"          => $last_name,
                "p[{$list_id}]"      => $list_id,
                "status[{$list_id}]" => 1, // "Active" status
                "tags"               => "1_ENGLISH, Website: Member, Website: Active",
                "field[1,0]"         => $user->id,
                );

            $contact_sync = $this->ac->api("contact/sync", $contact);
            $added[] = $row;
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
        
    }
    
    private function delete_users($records, $cols, $skip_first = false){
        
        $skipped = [];
        $added = [];
        $errors = [];
        
        //let's map. We need First Name|LastName and  Email as a minimum
        $name = false;
        $email = false;
        $fname = -1;
        $lname = -1;
        $em = -1;
        for($x = 0; $x < count($cols); $x++){
            $col = $cols[$x];
            if($col == 1 || $col == 2){
                if($col == 1){
                    $fname = $x;
                }
                if($col == 2){
                    $lname = $x;
                }
                $name = true;
            }
            if($col == 3){
                $em = $x;
                $email = true;
            }
        }
        if(!$name || !$email){
            return response()->json(['success'=>false, 'message'=>"Please map the columns correctly! First Name OR/AND Last Name and Email are required!"]);
        }
        $ignored = false;
        $x = 0;
        foreach($records as $row){
            $x++;
            
            
            $email = $this->is_email(trim($row[$em]));
            if($email === false){
                $errors[] = [$x, 'Not a valid email'];
                continue;
            }
            
            $user = User::where('email', $email)->first();
            if(!$user){
                $skipped[] = [$x, 'User not found'];
                continue;
            }
            //protect admin users
            if($user->hasRole('admin')){
                $ignored[] = [$x, "User is admin!"];
                continue;
            }
            
            
            //check if user is assigned to any packages remove them and add the license back to org
            if($user->org_id != 0){
                $subscriptions = ActiveSubscriptions::where('org_id', $user->org_id)
                    ->where('category', '=', '1')
                    ->orderBy('product_id', 'desc') 
                    ->get();
                
                foreach($subscriptions as $subscription){
                    if($user->is_assigned($subscription->product_id)){
                        $packageAssigned = ProductAssignments::where('user_id', $user->id)->where('product_id', $subscription->product_id)->first();
                        $packageAssigned->delete();
                        $subscription->used -= 1;
                        $subscription->save();
                    }
                }
            }
            
            if(!$user->hasRole('admin')){
                $user->delete();
                
            }
            
            $added[] = $row;

            
        }
        return response()->json(['success'=>true, 'ignored'=>$skipped, 'errors'=>$errors, 'processed'=>count($added)]);
        
    }
    
    private function is_email($em){
        return filter_var($em, FILTER_VALIDATE_EMAIL);
    }
    
    private function get_name($row, $fname, $lname){
        $name = [];
        if($fname != -1){
            $name[] = $row[$fname];
        }
        if($lname != -1){
            $name[] = $row[$lname];
        }
        return trim(implode(" ", $name));
    }

}
