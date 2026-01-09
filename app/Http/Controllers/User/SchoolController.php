<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Services\ActiveCampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\VerifyEmail;
use App\Models\ActiveSubscriptions;
use App\Models\OrderItems;
use App\Models\ProductAssignments;
use App\Models\Products;
use App\Models\Roles;
use App\Models\User;
use App\Models\Validations;
use App\Jobs\ProcessEmail;

class SchoolController extends Controller
{
    public function user($path = 'educators', $id){
        $user = User::find($id);
        if(!$user || $user->org_id != auth()->user()->org_id)
            return response()->json(['success' => false, 'reason'=>'No User']);
        return response()->json(['success' => true, 'user', $user])
        ->with('section', 'users')
        ->with('path', get_path($path));
    }

    public function users($path = 'educators'){
        $subscriptions = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
            ->where('category', '=', '1')
            ->orderBy('product_id', 'desc')
            ->get();
        $trainings = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
            ->where('category', '=', '7')
            ->orderBy('product_id', 'desc')
            ->get();
        $subs = [];
        $sub_ids = [];
        $tr = [];
        $tr_ids = [];
        foreach($subscriptions as $s){
            $subs[$s->product_id] = $s;
            $sub_ids[] = $s->product_id;
        }
        foreach($trainings as $t){
            $tr[$t->product_id] = $t;
            $tr_ids[] = $t->product_id;
        }
        $packages = Products::where('category', 1)->whereIn('id', $sub_ids)->orderBy('sort', 'ASC')->get();
        $trainings = Products::where('category', 7)->whereIn('id', $tr_ids)->orderBy('sort', 'ASC')->get();
        $users = User::where('org_id', auth()->user()->org_id)->get();
        return view('backend.users.users')
            ->with('users', $users)
            ->with('subscriptions', $subscriptions)
            ->with('packages', $packages)
            ->with('trainings', $trainings)
            ->with('section', 'users')
            ->with('path', get_path($path));
    }

    //ADD NEW USER (HEAD)
    public function add_user(Request $req, $path = 'educators'){
        try {
            $req->validate([
                'name'  => 'bail|required|max:190',
                'email' => 'bail|required|email|unique:users',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $user = new User();
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make(bin2hex(random_bytes(10))); //random 10 char password
        $user->needs_update = 1;
        $user->org_id = auth()->user()->org_id;
        if($req->input('file')){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$req->input('file'))){
                copy(config('constant.hold').'/'.$req->input('file'),
                config('constant.avatar.upload').'/'.$req->input('file'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the given file
                    unlink($file);
            }
            $user->avatar = $req->input('file');
        }
        $user->save();
        $roles = $req->input('role');
        if($req->input('role')){
            foreach($req->input('role') as $role){
                $user->addRole(Roles::find($role));
            }
        }
        $user->addRole(Roles::find(1));
        $validation = new Validations();
        $validation->getNewValidation($user->id);
        $email = new VerifyEmail($validation, $user);
        $job = new ProcessEmail($validation, $user, $email);
        $job->dispatch($validation, $user, $email);
        return response()->json(['success' => true]);
    }

    public function user_get($path = 'educators', $id){
        $user = User::find($id);
        if(!$user || $user->org_id != auth()->user()->org_id)
            return '';
        return view('backend.users.user-edit')
        ->with('user', $user)
        ->with('section', 'users')
        ->with('path', get_path($path));
    }

    //UPDATE SCHOOL USERS
    public function user_update(Request $req, $path = 'educators'){
        try {
            $req->validate([
                'name' => 'required|max:190',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $user = User::find($req->input('user_id'));
        if(!$user || $user->org_id != auth()->user()->org_id)
            return response()->json(['success' => false, 'message' => [['User not found.']]]);
        if($user->avatar != $req->input('file')){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$req->input('file'))){
                copy(   config('constant.hold').'/'.$req->input('file'),
                        config('constant.avatar.upload').'/'.$req->input('file'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the given file
                    unlink($file);
            }
            $user->avatar = $req->input('file');
        }
        $user->name = $req->input('name');
        $user->save();
        $roles = $req->input('role') ?? [];
        //roles to delete
        foreach(Roles::all() as $role){
            if(is_array($roles) && !in_array($role->id, $roles) && $role->id != 1 && $role->id != 2 && $role->id != 4 && $role->id != 5 && $role->id != 6){
                $user->removeRole($role);
            }
        }
        if(is_array($roles)){
            foreach($roles as $role){
                $user->addRole(Roles::find($role));
            }
        }
        //always add 1 + whatever edit
        $user->addRole(Roles::find(1));
        //update active campaign user
        //complete
        return response()->json(['success' => true, 'user', $user]);
    }

    public function subscriptions($path = 'educators'){
        $subscriptions = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
            ->where('category', '=', '1')
            ->orderBy('product_id', 'desc')
            ->get();
        $trainings = ActiveSubscriptions::where('org_id', auth()->user()->org_id)
            ->where('category', '=', '7')
            ->orderBy('product_id', 'desc')
            ->get();
        $subs = [];
        $sub_ids = [];
        $tr = [];
        $tr_ids = [];
        foreach($subscriptions as $s){
            $subs[$s->product_id] = $s;
            $sub_ids[] = $s->product_id;
        }
        foreach($trainings as $t){
            $tr[$t->product_id] = $t;
            $tr_ids[] = $t->product_id;
        }
        $packages = Products::where('category', 1)->whereIn('id', $sub_ids)->orderBy('sort', 'ASC')->get();
        $trainings = Products::where('category', 7)->whereIn('id', $tr_ids)->orderBy('sort', 'ASC')->get();
        return view('backend.users.assign-access')
            ->with('active_subscriptions', $subs)
            ->with('active_trainings', $tr)
            ->with('subscriptions', $packages)
            ->with('trainings', $trainings)
            ->with('section', 'users')
            ->with('path', get_path($path));
    }

    // Return HTML for licenses badges for given user IDs
    public function user_licenses(Request $req, $path = 'educators'){
        $ids = $req->input('users', []);
        if (!is_array($ids) || empty($ids)){
            return response()->json(['success' => false, 'licenses' => []]);
        }
        $map = [];
        $users = User::whereIn('id', $ids)->get();
        foreach($users as $u){
            $pws = $u->packages_with_names();
            if(!empty($pws)){
                $html = '';
                foreach($pws as $p){
                    $title = implode(', ', $p['names']);
                    $acronym = $p['acronym'];
                    $html .= '<span class="badge bg-secondary me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.e($title).'">'.e($acronym).'</span>';
                }
                $map[$u->id] = $html;
            } else {
                $map[$u->id] = '&nbsp;';
            }
        }
        return response()->json(['success' => true, 'licenses' => $map]);
    }

    public function add_multiple_packages(Request $req){
        $added = 0;
        $skipped = [];
        $already_added = [];
        foreach($req->input('curriculas') as $id){
            $subscription = ActiveSubscriptions::where('product_id', $id)
                    ->where('category', $req->input('type'))
                    ->where('org_id', auth()->user()->org_id)->first();
            if(!$subscription)
                continue; //prevent input hacking
            if($subscription->total - ($subscription->used + count($req->input('users'))) < 0){
                if($subscription->category == 1){
                    $item = Products::where('category', 1)->find($subscription->product_id);
                    $skipped[] = $item->name;
                }else{
                    $item = Products::find($subscription->product_id);
                    $skipped[] = $item->name;
                }
                continue;
            }
            //take out where query
            foreach($req->input('users') as $user_id){
                $user = User::where('org_id', auth()->user()->org_id)
                        ->where('id', $user_id)->first();
                //no user in this organization with the id
                if(!$user){
                    continue;
                }
                //user already has this package assigned
                if($user->is_assigned_type($subscription->product_id, $subscription->category)){
                    if(!in_array($user->name, $already_added)){
                        $already_added[] = $user->name;
                    }
                    continue;
                }
                //licenses consumed?
                if($subscription->used >= $subscription->total){
                    continue;//this should never happen since we already check that at the top, it's just a fail safe
                }
                //all good, assign user to subscription
                $packageAssigned = new ProductAssignments();
                $packageAssigned->product_id = $subscription->product_id;
                $packageAssigned->user_id = $user->id;
                $packageAssigned->active = 1;
                $packageAssigned->type = $subscription->category;
                $packageAssigned->save();
                $subscription->used += 1;
                $subscription->save();
                $added++;
            }
        }
        return response()->json(['success' => true, 'added'=>$added, 'skipped'=>$skipped, 'already_added'=>$already_added]);
    }

    /**
     * Get organization users for API endpoint
     */
    public function get_organization_users(Request $req, $path = 'educators'){
        $users = User::where('org_id', auth()->user()->org_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        // If product_id and category provided, include access status
        if($req->has('product_id') && $req->has('category')){
            $productId = $req->input('product_id');
            $category = $req->input('category');
            
            $users = $users->map(function($user) use ($productId, $category) {
                $user->has_access = $user->is_assigned_type($productId, $category);
                return $user;
            });
        }
        
        return response()->json($users);
    }

    /**
     * Assign curriculum access via modal POST
     */
    public function assign_curriculum(Request $req, $path = 'educators'){
        $subscriptionId = $req->input('subscription_id');
        $userId = $req->input('user_id');
        
        $subscription = ActiveSubscriptions::with('product')->find($subscriptionId);
        $user = User::where('org_id', auth()->user()->org_id)
            ->where('id', $userId)
            ->first();

        if(!$subscription || !$user){
            return back()->with('error', 'Invalid request.');
        }

        if($user->is_assigned_type($subscription->product_id, 1)){
            return back()->with('error', 'User is already assigned to this curriculum.');
        }

        if($subscription->used >= $subscription->total){
            return back()->with('error', 'All available user spots have been assigned.');
        }

        $assignment = new ProductAssignments();
        $assignment->product_id = $subscription->product_id;
        $assignment->user_id = $user->id;
        $assignment->active = 1;
        $assignment->category = 1;
        $assignment->save();

        $subscription->used += 1;
        $subscription->save();

        // Sync to Active Campaign with curriculum access
        $acService = new ActiveCampaignService();
        $acService->syncUserUpdate($user, [
            'user_count_es' => $this->getUserCountByCategory($user, 16),
            'user_count_ms' => $this->getUserCountByCategory($user, 17),
            'user_count_hs' => $this->getUserCountByCategory($user, 18),
        ]);

        $productName = $subscription->product ? $subscription->product->name : 'Curriculum';
        return back()->with('success', "Access to {$productName} assigned to {$user->name}");
    }

    /**
     * Assign training access via modal POST
     */
    public function assign_training_post(Request $req, $path = 'educators'){
        $subscriptionId = $req->input('subscription_id');
        $userId = $req->input('user_id');
        
        $training = ActiveSubscriptions::with('product')->find($subscriptionId);
        $user = User::where('org_id', auth()->user()->org_id)
            ->where('id', $userId)
            ->first();

        if(!$training || !$user){
            return back()->with('error', 'Invalid request.');
        }

        if($user->is_assigned_type($training->product_id, 7)){
            return back()->with('error', 'User is already assigned to this training.');
        }

        if($training->used >= $training->total){
            return back()->with('error', 'All available training spots have been assigned.');
        }

        $assignment = new ProductAssignments();
        $assignment->product_id = $training->product_id;
        $assignment->user_id = $user->id;
        $assignment->active = 1;
        $assignment->category = 7;
        $assignment->save();

        $training->used += 1;
        $training->save();

        // Add ActiveCampaign tag and sync if applicable
        $acService = new ActiveCampaignService();
        $product = $training->product ?? Products::find($training->product_id);
        if($product && $product->tag){
            // Sync user with training access
            $acService->syncUserUpdate($user, [
                'message' => "Training: {$product->name}",
            ]);
            // Add product tag
            $acService->addTagByEmail($user->email, $product->tag);
        }

        $productName = $training->product ? $training->product->name : 'Training';
        return back()->with('success', "Access to {$productName} assigned to {$user->name}");
    }

    /**
     * Remove curriculum or training access
     */
    public function remove_access(Request $req, $path = 'educators'){
        $subscriptionId = $req->input('subscription_id');
        $userId = $req->input('user_id');
        $category = $req->input('category'); // 1 for curriculum, 7 for training
        
        $subscription = ActiveSubscriptions::find($subscriptionId);
        $user = User::where('org_id', auth()->user()->org_id)
            ->where('id', $userId)
            ->first();

        if(!$subscription || !$user){
            return response()->json(['success' => false, 'message' => 'Invalid request.'], 400);
        }

        if(!$user->is_assigned_type($subscription->product_id, $category)){
            return response()->json(['success' => false, 'message' => 'User does not have access.'], 400);
        }

        $assignment = ProductAssignments::where('user_id', $user->id)
            ->where('product_id', $subscription->product_id)
            ->where('category', $category)
            ->first();
            
        if($assignment){
            $assignment->delete();
            $subscription->used -= 1;
            $subscription->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bulk assign curriculum or training access
     */
    public function bulk_assign_access(Request $req, $path = 'educators'){
        $subscriptionId = $req->input('subscription_id');
        $userIds = $req->input('user_ids', []);
        $category = $req->input('category'); // 1 for curriculum, 7 for training
        
        if(empty($userIds)){
            return response()->json(['success' => false, 'message' => 'No users selected.'], 400);
        }
        
        $subscription = ActiveSubscriptions::find($subscriptionId);
        if(!$subscription){
            return response()->json(['success' => false, 'message' => 'Subscription not found.'], 404);
        }
        
        $added = 0;
        $skipped = 0;
        
        foreach($userIds as $userId){
            $user = User::where('org_id', auth()->user()->org_id)
                ->where('id', $userId)
                ->first();
                
            if(!$user || $user->is_assigned_type($subscription->product_id, $category)){
                $skipped++;
                continue;
            }
            
            if($subscription->used >= $subscription->total){
                break; // No more licenses available
            }
            
            $assignment = new ProductAssignments();
            $assignment->product_id = $subscription->product_id;
            $assignment->user_id = $user->id;
            $assignment->active = 1;
            $assignment->category = $category;
            $assignment->save();
            
            $subscription->used += 1;
            $subscription->save();
            
            $added++;
        }
        
        return response()->json([
            'success' => true, 
            'added' => $added, 
            'skipped' => $skipped,
            'message' => "Successfully assigned access to {$added} user(s)."
        ]);
    }

    /**
     * Bulk remove curriculum or training access
     */
    public function bulk_remove_access(Request $req, $path = 'educators'){
        $subscriptionId = $req->input('subscription_id');
        $userIds = $req->input('user_ids', []);
        $category = $req->input('category'); // 1 for curriculum, 7 for training
        
        if(empty($userIds)){
            return response()->json(['success' => false, 'message' => 'No users selected.'], 400);
        }
        
        $subscription = ActiveSubscriptions::find($subscriptionId);
        if(!$subscription){
            return response()->json(['success' => false, 'message' => 'Subscription not found.'], 404);
        }
        
        $removed = 0;
        
        foreach($userIds as $userId){
            $user = User::where('org_id', auth()->user()->org_id)
                ->where('id', $userId)
                ->first();
                
            if(!$user || !$user->is_assigned_type($subscription->product_id, $category)){
                continue;
            }
            
            $assignment = ProductAssignments::where('user_id', $user->id)
                ->where('product_id', $subscription->product_id)
                ->where('category', $category)
                ->first();
                
            if($assignment){
                $assignment->delete();
                $subscription->used -= 1;
                $subscription->save();
                $removed++;
            }
        }
        
        return response()->json([
            'success' => true, 
            'removed' => $removed,
            'message' => "Successfully removed access from {$removed} user(s)."
        ]);
    }

    public function delete_user($path = 'educators', $users){
        if(!strlen($users))
            abort(403);
        if(strstr($users, ",")){
            $users = explode(",", $users);
        }else{
            $users = [$users];
        }
        $except = [];
        $deleted = [];
        foreach($users as $uid){
            $user = User::find($uid);
            if(!$user->hasRole('admin') || !$user->hasRole('organization')){
                //remove them from subscription and trainings and restore licenses
                $subscriptions = ActiveSubscriptions::where('org_id', auth()->user()->org_id)->get();
                foreach($subscriptions as $subscription){
                    //all good, remove access
                    if($user->is_assigned($subscription->product_id)){
                        $packageAssigned = ProductAssignments::where('user_id', $user->id)->where('product_id', $subscription->product_id)->first();
                        $packageAssigned->delete();
                        $subscription->used -= 1;
                        $subscription->save();
                    }
                }
                //add script to delete avatar if not default.jpg
                $user->delete();
                $deleted[] = $uid;
            }else{
                $except[] = $user;
            }
        }
        $message = !count($except) ? "User(s) deleted" : "A user/users have admin status and cannot be deleted.";
        return response()->json(['success' => true, 'reason' => $message, 'deleted'=>$deleted]);
    }

    /**
     * Helper method to count user's products by curriculum level
     * Returns count of products in ES, MS, or HS category
     */
    private function getUserCountByCategory($user, $fieldId)
    {
        // Map field ID to category
        $categoryMap = [
            16 => 'es',  // Elementary
            17 => 'ms',  // Middle School
            18 => 'hs',  // High School
        ];

        $category = $categoryMap[$fieldId] ?? null;
        if (!$category) {
            return 0;
        }

        // Count distinct products assigned to user in this category
        return ProductAssignments::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->filter(function ($assignment) use ($category) {
                if (!$assignment->product) return false;
                // Filter by product name or ID pattern
                $name = strtolower($assignment->product->name);
                return strpos($name, $category) !== false || 
                       strpos($name, match($category) {
                           'es' => 'elementary',
                           'ms' => 'middle',
                           'hs' => 'high',
                           default => ''
                       }) !== false;
            })
            ->count();
    }

}
