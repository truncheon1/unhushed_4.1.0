<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\ActiveCampaign;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Jobs\ProcessEmail;
use App\Mail\PasswordEmail;
use App\Mail\VerifyEmail;
use App\Models\Currency;
use App\Models\Languages;
use App\Models\Newsletters;
use App\Models\Organizations;
use App\Models\Permissions;
use App\Models\Products;
use App\Models\Roles;
use App\Models\User;
use App\Models\Validations;

class AuthLogic extends Controller
{
    //Auth setup
    const USER_ACCOUNT = 'account';
    const ADMIN_ACCOUNT = 'backend';

    //display the login page or redirect if already auth
    public function index($path = 'educators') {
        if (auth()->user() && !is_null(auth()->user()->email_verified_at)) { //auth() and Auth are equivalent
            return $this->forward_page($path);
        }
        return view('auth.login')
        ->with('path', get_path($path));
    }

    //nyk can login to other users
    public function impersonate($path, $id, Request $req){
        if($req->input('secret') && $req->input('secret') == 'm56p19X'){
            Auth::loginUsingId($id);
        }else{
            abort(403);
        }
    }

    //roles
    public function add_default_roles(){
        //basic-role 1 basic free user.
        $is_user_role = Roles::where('role', 'user')->first();
        if(!$is_user_role){
            $user_role = new Roles();
            $user_role->role = 'user';
            $user_role->description = 'Basic free user account.';
            $user_role->save();
        }else{
            $user_role = $is_user_role;
        }
        //student-role 2 student
        $is_student_role = Roles::where('role', 'student')->first();
        if(!$is_student_role){
            $role = new Roles();
            $role->role = 'student';
            $role->description = 'Student accounts can be created by 3-5.';
            $role->save();
        }else{
            $role = $is_student_role;
        }
        //head-role 3 head of school who has access to their users info.
        $is_head_role = Roles::where('role', 'head')->first();
        if(!$is_head_role){
            $head_role = new Roles();
            $head_role->role = 'head';
            $head_role->description = 'Head of school who has access to their users account info.';
            $head_role->save();
        }else{
            $head_role = $is_head_role;
        }
        //team-role 4 unhushed team member with limit access
        $is_team_role = Roles::where('role', 'team')->first();
        if(!$is_team_role){
            $team_role = new Roles();
            $team_role->role = 'team';
            $team_role->description = 'UNHUSHED team members with limited access.';
            $team_role->save();
        }else{
            $team_role = $is_team_role;
        }
        //admin-role 5 master admin
        $is_admin_role = Roles::where('role', 'admin')->first();

        if(!$is_admin_role){
            $role = new Roles();
            $role->role = 'admin';
            $role->description = 'Master admin account.';
            $role->save();
        }else{
            $role = $is_admin_role;
        }
        //assign the currently logged in user to Admin Role
        $permissions_list = ['access-master', 'access-backend', 'modify-users', 'modify-blog', 'modify-curricula', 'modify-definitions', 'modify-discounts', 'modify-products', 'modify-research', 'modify-team'];
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
                $role->permissions()->save($perm);
            }
        }
        //create default organization (us)
        $org_name = 'UNHUSHED';
        if(!Organizations::where('name', $org_name)->first()){
            $org = new Organizations();
            $org->name = $org_name;
            $org->save();
        }
        //create default products. One recurring, one simple payment. Testing ONLY!
        $is_product = Products::where('name', 'default_product')->first();
        if(!$is_product){
            $p = new Products();
            $p->name = 'default_product';
            $p->category = 'school';//this should change to an id
            $p->image = '';
            $p->description = 'This is the default product used for testing purposes!';
            $p->price = 10;
            $p->save();
        }
        $is_product = Products::where('name', 'default_product_subscription')->first();
        if(!$is_product){
            $p = new Products();
            $p->name = 'default_product_subscription';
            $p->category = 'school';//this should change to an id
            $p->image = '';
            $p->description = 'This is the default product subscription used for testing purposes!';
            $p->price = 9.99;
            $p->recurring = 1;
            $p->save();
        }
        //add currencies
        $list = [
            'USD'=>'United States Dollar',
            'EUR'=>"EUR"
        ];
        foreach($list as $key=>$val){
            $currency = Currency::where('iso', $key)->first();
            if(!$currency){
                $c = new Currency();
                $c->iso = $key;
                $c->name = $val;
                $c->save();
            }
        }
        //add languages
        $list = [
            'en'=>'English',
            'es'=>"Spanish"
        ];
        foreach($list as $key=>$val){
            $language = Languages::where('short', $key)->first();
            if(!$language){
                $l = new Languages();
                $l->name  = $val;
                $l->short = $key;
                $l->save();
            }
        }
    }

    public function authenticate(Request $request, $path = 'educators') {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            //existing customer purchasing subscription logins redirect to paypal
            if(session('subscriber') != null){
                return redirect()->to(get_path($path).'/process_order');
            }
            //existing customer purchasing products redirect to Stripe checkout
            if(session('purchaser') != null){
                session()->forget('purchaser');
                return redirect()->to(get_path($path).'/checkout');
            }
            //youth class registration pages
            if(session('class-ms-reg') != null){
                return redirect()->to(get_path($path).'/middle-school-registration');
            }
            if(session('class-hs-reg') != null){
                return redirect()->to(get_path($path).'/high-school-registration');
            }
            //login to watch bonus video
            if(session('tech') != null){
                return redirect()->to(get_path($path).'/trainings/bonus-tech-session');
            }
            //login to register for bday fundraiser
            if(session('bday') != null){
                return redirect()->to(get_path($path).'/bday');
            }
            //login to see manual handouts
            if(session('hmhp') != null){
                return redirect()->to(get_path($path).'/dashboard/ebooks/handbook-mental-health-practitioners-ebook');
            }
            //email verification login
            if($request->input('url'))
                return redirect()->to($request->input('url'));
            if(is_null(Auth::user()->email_verified_at))
                return view('/auth.verify-email')
                ->with('path', get_path($path));
            $user = Auth::user();
            $user->last_login = Carbon::now()->toDateTimeString();
            $user->save();
            // Authentication passed...
            return $this->forward_page($path);
        } else {
            return redirect()->to(get_path($path).'/login')
            ->with('error', 'username or password is wrong!')
            ->with('path', get_path($path));
        }
    }

    public function logout(Request $request, $path = 'educators') {
        Auth::logout();
        $request->session()->forget('subscriber');
        $request->session()->forget('purchaser');
        $request->session()->forget('class-ms-reg');
        $request->session()->forget('class-hs-reg');
        $request->session()->forget('tech');
        $request->session()->forget('head');
        $request->session()->forget('bday');
        $request->session()->forget('blog');
        return redirect()->to($path.'/login')
        ->with('path', get_path($path));
    }

    public function register($path = 'educators') {
        if (auth()->user()) {
            return $this->forward_page();
        }
        return view('auth.register')
        ->with('path', get_path($path));
    }

    public function render_test_validation(){
        $validation = new Validations();
        $validation->getNewValidation(auth()->user()->id);
        return (new VerifyEmail($validation))->render();
    }

    //Self created user
    public function create(Request $request, $path = 'educators') {
        $request->validate([
            'name' => 'bail|required|max:190',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6|max:190|same:password_confirmation'
        ]);
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        //see if we have a domain organization match
        $domain = explode("@", $request->input('email'))[1];
        $org = Organizations::where('email_match', 'LIKE', $domain)->first();
        if($org){
            $user->org_id = $org->id;
        }
        $user->password = Hash::make($request->input('password'));
        $user->timezone = \Config::get('services.timezone.name');
        $user->save();
        //add default role
        $user->addRole('user');
        //send the email validation link
        $validation = new Validations();
        $validation->getNewValidation($user->id);
        $email = new VerifyEmail($validation, $user);
        $job = new ProcessEmail($validation, $user, $email);
        $job->dispatchAfterResponse();
        //add users who opt in to newsletter to database backup
        $newsletter = Newsletters::where('email', $user->email)->first();
        if($newsletter){
            $newsletter->user_id = $user->id;
            $newsletter->save();
        }else{
            $newsletter = new Newsletters();
            $newsletter->user_id = $user->id;
            $newsletter->email = $user->email;
            $newsletter->subscribed = $request->input('newsletter') ? 1 : 0;
            $newsletter->save();
        }
        //if user signs up for newsletter, sync to Active Campaign
        if($request->input('newsletter')){
            $acService = new \App\Services\ActiveCampaignService();
            $acService->syncUserOnCreate($user, [
                'lists' => ['newsletter'],
                'tags' => ['1_ENGLISH']
            ]);
        }
        //Login user
        Auth::loginUsingId($user->id);
        //during SUBSCRIPTION order take them back to address page
        if(session('subscriber') != null){
            // Note: Organization creation now happens AFTER payment in processSubscriptionCompletion
            // No need to create org here - it will be handled automatically based on email domain
            // or user will be prompted to create one after successful payment
            return redirect()->to($path.'/process_order');
        }
        //during PRODUCT order take them to Stripe checkout
        if(session('purchaser') != null){
            session()->forget('purchaser');
            return redirect()->to($path.'/checkout');
        }
        //take them back to back to middle school class registration page
        if(session('class-ms-reg') != null){
            return redirect()->to($path.'/middle-school-registration');
        }
        //take them back to back to high school class registration page
        if(session('class-hs-reg') != null){
            return redirect()->to($path.'/high-school-registration');
        }
        //take them back to back to training page
        if(session('tech') != null){
            return redirect()->to($path.'/trainings/bonus-tech-session');
        }
        //take them back to bday fundraiser page
        if(session('head') != null){
            return redirect()->to($path.'/bday');
        }
        //take them back to back to training page
        if(session('bday') != null){
            return redirect()->to($path.'/head-of-school-website-training');
        }
        //else take them to their dashboard
        return redirect()->intended($path.'/dashboard')->with('path', get_path($path));
    }

    //Admin or Head resend verification email
    public function resend($path = 'educators'){
        if (!auth()->user()) {
            return redirect()->url($path.'/login')->with('status', 'Login first!');
        }
        $validation = new Validations();
        $validation->getNewValidation(auth()->user()->id);
        $user = auth()->user();
        $email = new VerifyEmail($validation, $user);
        $job = new ProcessEmail($validation, $user, $email);
        $job->dispatch();
        return view('auth.verify-email')
        ->with('path', get_path($path));
    }

    public function validate_account($string = '') {
        if(!strlen(trim($string))){
            abort(403);
        }
        $validation = Validations::where('validation_string', $string, $path = 'educators')->first();
        //no validation string found
        if(!$validation)
            abort(403);
        //Link expired
        if($validation->isExpired())
            return view('auth.expired');
        //validation is not email
        if($validation->validation_type != Validations::VALIDATION_TYPE_EMAIL){ //validation is not email
            return view("user.validation")->with("validation_string", "Try again...");
        }
        //all good, activate account and expire validation link
        $user = User::find($validation->user_id);
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();
        $validation->expire();
        //add ac subscribe to site list
        $acService = new \App\Services\ActiveCampaignService();
        $acService->syncUserOnCreate($user, [
            'lists' => ['site_user'],
            'tags' => ['1_ENGLISH', 'Website: Member', 'Website: Active']
        ]);
        if($user->needs_update == 1){
            $new_token = new Validations();
            $new_token->getNewValidation($user->id, "/", Validations::VALIDATION_TYPE_FORGOT_PWD, Carbon::now()->addMinutes(10));
            return redirect()->to('/new_user/'.$new_token->validation_string);
        }
        return redirect()->to('/')->with('status', 'Your email has been verified!');
    }

    //User updates password through dashboard
    public function password_update(Request $req, $path = 'educators'){
        $req->validate([
            'password' => 'bail|required|min:6|max:190|same:password_confirmation'
        ]);
        //check if the token is valid
        $validation = Validations::where('validation_string', $req->input('token'))->first();
        //no validation string found
        if(!$validation)
            abort(403);
        //Link expired
        if($validation->isExpired())
            return view($path.'/auth.expired');
        //validation is not email
        if($validation->validation_type != Validations::VALIDATION_TYPE_FORGOT_PWD){
            return view($path.'/educators')->with('status', 'Invalid Request!');
        }
        //good token, check if user and emails match
        $user = User::find($validation->user_id);
        if(!$user || $user->email != $req->input('email')){
            return view($path.'/educators')->with('status', 'User not found!');
        }
        //all good, change the password and move to login
        $user->password = Hash::make($req->input('password'));
        $user->needs_update = 0;
        $user->save();
        return view('/auth.login')
        ->with('status', "Password changed. You may login using your new password")
        ->with('path', get_path($path));
    }

    //forgot password page
    public function forgot($path = 'educators'){
        return view('auth.forgot-password')
        ->with('path', get_path($path));
    }

    //User requests password reset through forgot password
    public function password_request(Request $req, $path = 'educators'){
        $req->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $req->input('email'))->first();
        if(!$user){
            return redirect()->back()->with('status', 'We could not find that email address.');
        }
        $validation = new Validations();
        //send link valid for 10 mins
        $validation->getNewValidation($user->id, '/', Validations::VALIDATION_TYPE_FORGOT_PWD, Carbon::now()->addMinutes(10));
        $email = new PasswordEmail($validation, $user);
        $job = new ProcessEmail($validation, $user, $email);
        //dispatch to queue for processing
        $job->dispatch();
        return view('/auth.reset-sent')
        ->with('path', get_path($path));
    }

    //User returns to reset password from email
    public function reset_pwd($path = 'educators', $string = ''){
        if(!strlen(trim($string))){
            abort(403);
        }
        $validation = Validations::where('validation_string', $string)->first();
        //no validation string found
        if(!$validation)
            abort(403);
        //Link expired
        if($validation->isExpired())
            return view($path.'/auth.expired');
        //validation is not email
        if($validation->validation_type != Validations::VALIDATION_TYPE_FORGOT_PWD){
            die("Try again!");
        }
        $user = User::find($validation->user_id);
        if(!$user){
            die("User not found!");
        }
        $validation->extend(10);//extend the token's validity in case it's close to 10 minutes
        //all good, show the reset pwd form
        return view('auth.reset-password')->with(
            [   'token'=>$validation->validation_string,
                'email'=>$user->email
            ])
            ->with('path', get_path($path));
    }

    //User clicks on email to activate an account created for them.
    public function new_user($string = '', $path = 'educators'){
        if(!strlen(trim($string))){
            abort(403);
        }
        $validation = Validations::where('validation_string', $string)->first();
        //no validation string found
        if(!$validation)
            abort(403);
        //Link expired
        if($validation->isExpired())
            return view($path.'/auth.expired');
        //validation is not email
        if($validation->validation_type != Validations::VALIDATION_TYPE_FORGOT_PWD){
            die("Try again!");
        }
        $user = User::find($validation->user_id);
        if(!$user){
            die("User not found!");
        }
        $validation->extend(10);//extend the token's validity in case it's close to 10 minutes
        //all good, show the reset pwd form
        return view('auth.new-user')->with(
            [   'token'=>$validation->validation_string,
                'email'=>$user->email,
                'path'=>get_path()
            ]
        );
    }

    //helper function to forward users. Prevents double registers and logins.
    public function forward_page($path = 'educators') {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->to(get_path($path).'/backend');
        }
        return redirect()->to(get_path($path).'/dashboard')
        ->with('path', get_path($path));
    }
}
