<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\ProductAssignments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    //USER PROFILE PAGE
    public function view_user(Request $req, $path = 'educators', $id){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
        $user = User::where('id', $id)->first();
        $packages = ProductAssignments::where('user_id', $id)->get();

        return view('backend.users.user-profile')
            ->with('user', $user)
            ->with('packages', $packages)
            ->with('section', 'master')
            ->with('path', get_path($path));
    }
    
}