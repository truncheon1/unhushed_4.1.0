<?php

namespace App\Http\Controllers\Fundraisers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BdayController extends Controller
{

    public function __construct(){
        //constructing
    }

    //sign up for event
    public function step1(Request $request, $path){

        //validate address
        $error = false;
        $message = '';
        $tags = $request->input('tags');
        if(!$tags || !count($tags)){
            $error = true;
            $message = 'Tag(s) required.';
        }
        $email = $request->input('email');
        if(!$email || !strlen(trim($email))){
            $error = true;
            $message = 'Your email is required.';
        }
        $name = $request->input('name');
        if(!$name || !strlen(trim($name))){
            $error = true;
            $message = 'Your full name is required.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }

        //instantiate API to AC
        $this->ac = new \ActiveCampaign(\Config::get('services.activecampaign.url'), \Config::get('services.activecampaign.key'));
        $full_name  = explode(" ", $name);
        $last_name  = array_pop($full_name);
        $first_name = count($full_name)? implode(" ", $full_name) : "";
        $contact = array(
            "email"          => $email,
            "first_name"     => $first_name,
            "last_name"      => $last_name,
		    "p[{1}]"         => 1,
		    "status[{1}]"    => 1, // "Active" status
            "tags"           => implode(",", $tags),
            //"field[1,0]"     => $user->id,
        );
        $contact_sync = $this->ac->api("contact/sync", $contact);
        return response()->json(['error' => false, 'message'=>'Thanks for signing up!']);
    }

    //final
    public function step2(Request $request, $path = 'educators'){
        $request->session()->forget('bday');
        return view('fundraisers.bday.2025-10-24done')
        ->with('path', get_path($path))
        ->with(['message' => 'Thanks for signing up!']);
    }
}
