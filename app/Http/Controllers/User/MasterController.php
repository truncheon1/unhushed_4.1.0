<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\VerifyEmail;
use App\Models\Organizations;
use App\Models\Roles;
use App\Models\User;
use App\Models\Validations;
use App\Jobs\ProcessEmail;

class MasterController extends Controller
{
    const RPP = 20; //results per page

    public function __construct()
    {
        //$this->middleware('role:admin');
    }

    //FIND USERS
    public function users(Request $req, $path = 'educators'){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
        $oid = $req->org_id ?? 0;
        if($oid > 0){
            $org = Organizations::find($oid);
            $org_id = $org->id;
        }else{
            $org_id = $oid;
        }
        $page = $req->input('page') || 0;
        $skip = $page * self::RPP;
        auth()->user()->permissions_string();
        return view('backend.users.master-users')->with(
                [
                    'organizations'=> Organizations::orderBy('name', 'ASC')->get(),
                    'users'=>$org_id == -1 ? User::orderBy('name', 'ASC')->get() : User::where('org_id', $org_id)->orderBy('name', 'ASC')->get(),
                    'oid'=>$org_id,
                ]
                )->with('section', 'master')
                ->with('sect2', 'master-users')
                ->with('path', get_path($path));
    }

    //ADD NEW USER (MASTER)
    public function user_add(Request $req){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
        try {
            $req->validate([
                'oid'   => 'bail|required',
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
        $user->org_id = $req->input('oid');
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
    
    //UPDATE USER (MASTER)
    public function user_get(Request $req, $path = 'educators'){
        $user = User::find($req->input('user_id'));
        if(!$user)
            abort(404);
        return view('backend.users.master-user-edit')
        ->with('user', $user)
        ->with('path', get_path($path));
    }

    public function user_update(Request $req, $path = 'educators'){
        try{
            $req->validate([
                'oid' => 'bail|required',
                'user_id' => 'bail|required',
                'name'=>'bail|required|min:3',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $user = User::find($req->input('user_id'));
        if(!$user)
            return response()->json(['success' => false, 'message' => [['Error. User not found!']]]);
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
        $user->email = $req->input('email');
        $user->org_id = $req->input('oid');
        $user->save();
        $roles = $req->input('role') ?? [];
        //roles to delete
        foreach(Roles::all() as $role){
            if(is_array($roles) && !in_array($role->id, $roles) && $role->id != 1){
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
        return response()->json(['success'=>true]);
    }

    public function resend_activation($path = 'educators', $id){
        $user = User::find($id);
        $validation = new \App\Models\Validations();
        $validation->getNewValidation($user->id);
        $email = new \App\Mail\VerifyEmail($validation, $user);
        $job = new \App\Jobs\ProcessEmail($validation, $user, $email);
        $job->dispatch();
        return response()->json(['success'=>true]);
    }

    public function delete_user($path = 'educators', $users){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
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
            if(!$user->hasRole('admin')){
                $user->delete();
                $deleted[] = $uid;
            }else{
                $except[] = $user;
            }
        }
        $message = !count($except) ? "Users Deleted" : "Some users are admin and they cannot be deleted!";
        return response()->json(['success' => true, 'reason' => $message, 'deleted'=>$deleted]);
    }

    public function assign_users(Request $req, $path = 'educators'){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
        $req->validate([
            'oid' => 'bail|required',
            'users' => 'bail|required',
        ]);
        $org = Organizations::find($req->input('oid'));
        if(!$org){
            return redirect()->back()->with('status', 'Error. Organization not found!');
        }
        $users = explode("|", $req->input('users'));
        foreach($users as $user){
            $user = User::find($user);
            if($user){
                $user->org_id = $org->id;
                $user->save();
            }
        }
        return redirect()->back()->with('status', 'User(s) moved to '.$org->name."!");
    }
}
