<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as Auth;
use App\Models\ActiveSubscriptions;
use App\Models\Organizations;
use App\Models\ProductAssignments;
use App\Models\Permissions;
use App\Models\Roles;
use App\Models\User;
use Carbon\Carbon;

class BackendController extends Controller {
    //Show the application backend for admin and team.
    public $section = 'backend';

    public function index($path = 'educators'){
        $totalUsers = User::count(); //shows all users
        $validUsers = User::where('needs_update', 0)->count(); //shows only validated users
        $YTDgain = User::whereYear('created_at', date('Y'))->count(); //shows new users this year
        $trainedUsers = ProductAssignments::distinct('user_id')->where('active', 1)->where('category', 7)->count();
        $YTDtrained = ProductAssignments::distinct('user_id')->where('active', 1)->where('category', 7)->whereYear('created_at', date('Y'))->count();
        $orgs = Organizations::count(); //shows all orgs
        $YTDorgs = Organizations::whereYear('created_at', date('Y'))->count(); //shows new orgs this year
        $paidOrgs = ActiveSubscriptions::where('used', '>', 0)->where('category', 1)->distinct('org_id')->count();
        $YTDpaid = ActiveSubscriptions::where('used', '>', 0)->where('category', 1)->distinct('org_id')->whereYear('created_at', date('Y'))->count();
        $trainedOrgs = ActiveSubscriptions::where('used', '>', 0)->where('category', 7)->distinct('org_id')->count();
        $es = ActiveSubscriptions::where('product_id', 2)->where('category', 1)->sum('used');
        $ms = ActiveSubscriptions::where('product_id', 3)->where('category', 1)->sum('used');
        $hs = ActiveSubscriptions::where('product_id', 4)->where('category', 1)->sum('used');
        $currentYr = Carbon::now()->year;
        $lastYear = Carbon::now()->subYears(1)->year;
        return view('backend.dashboards.backend')
        ->with('totalUsers', $totalUsers)
        ->with('validUsers', $validUsers)
        ->with('YTDgain', $YTDgain)
        ->with('trainedUsers', $trainedUsers)
        ->with('YTDtrained', $YTDtrained)
        ->with('orgs', $orgs)
        ->with('paidOrgs', $paidOrgs)
        ->with('YTDorgs', $YTDorgs)
        ->with('YTDpaid', $YTDpaid)
        ->with('trainedOrgs', $trainedOrgs)
        ->with('es', $es)->with('ms', $ms)->with('hs', $hs)
        ->with('currentYr', $currentYr)->with('lastYear', $lastYear)
        ->with('section', $this->section)
        ->with('path', get_path($path));
    }

    // AJAX: return stats for a given year
    public function stats($path = 'educators', $year = null)
    {
        $yr = $year ?: date('Y');
        $yr = (int) $yr;

        $YTDgain    = User::whereYear('created_at', $yr)->count();
        $YTDtrained = ProductAssignments::distinct('user_id')->where('active', 1)->where('category', 7)->whereYear('created_at', $yr)->count();
        $YTDorgs    = Organizations::whereYear('created_at', $yr)->count();
        $YTDpaid    = ActiveSubscriptions::where('used', '>', 0)->where('category', 1)->distinct('org_id')->whereYear('created_at', $yr)->count();

        return response()->json([
            'year'       => $yr,
            'ytdGain'    => $YTDgain,
            'ytdTrained' => $YTDtrained,
            'ytdOrgs'    => $YTDorgs,
            'ytdPaid'    => $YTDpaid,
        ]);
    }

    //removed from route, saved for later use
    public function grant_access_admin(){
        $is_user_role = Roles::where('role', 'user')->first();
        if(!$is_user_role){
            $user_role = new Roles();
            $user_role->role = 'user';
            $user_role->description = 'user';
            $user_role->save();
        }else{
            $user_role = $is_user_role;
        }
        Auth::user()->addRole($user_role);

        $is_admin_role = Roles::where('role', 'admin')->first();

        if(!$is_admin_role){
            $role = new Roles();
            $role->role = 'admin';
            $role->description = 'this is the admin';
            $role->save();

        }else{
            $role = $is_admin_role;
        }
        //assign the currently logged in user to Admin Role
        Auth::user()->addRole($role);

        $permissions_list = ['access-master', 'access-backend','modify-users', 'modify-blog', 'modify-curricula', 'modify-definitions', 'modify-discounts', 'modify-products', 'modify-research', 'modify-team'];

        foreach($permissions_list as $permission){
            $is_permission = Permissions::where('slug', $permission)->first();
            if($is_permission){
                $perm = $is_permission;
            }else{
                $perm = new Permissions();
                $perm->name = $permission;
                $perm->slug = $permission;
                $perm->save();

            }
            if(!$role->hasPermission($perm)){
                $role->permissions->save($perm);
            }
        }
    }
}
