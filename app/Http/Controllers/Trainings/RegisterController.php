<?php

namespace App\Http\Controllers\Trainings;
use App\Http\Controllers\Controller;
//use App\Mail\HcwpEmail;
use App\Http\Controllers\Store\ShippingAPI;
use App\Models\HcwpAttendees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    public function __construct(){
        //constructing
    }

    //ship free book
    public function step1(Request $req){

        //validate address
        try {
            $req->validate([
                'name' => 'required|min:3',
                'email' => 'required|email',
                'street'=>'required|min:5',
                'city'=>'required|min:5',
                'state'=>'required|min:2|max:2',
                'zip'=>'required|string|min:5|max:5',
                'ceu' => 'required|min:3',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => true, 'reason' => $e->errors()]);
        }

        //check against the USPS api
        $api = new ShippingAPI();
        $result = $api->check_address([
            'address'=>$req->input('street'),
            'city'=>$req->input('city'),
            'state'=>$req->input('state'),
            'zip'=>$req->input('zip')
        ]);
        if(!$result){
            return response()->json(['error' => true, 'reason' => ['address'=>[$api->get_last_message()]]]);
        }

        //store the address for shipping
        $api_address = $api->get_last_response()->Address;
        $attendee = new HcwpAttendees();
        $attendee->name = $req->input('name');
        $attendee->email = $req->input('email');
        $attendee->user_id = auth()->user() ? auth()->user()->id : 0;
        $attendee->api_address1 = $api_address->Address2->__toString();
        $attendee->api_address2 = $api_address->Address1->__toString();
        $attendee->api_city = $api_address->City->__toString();
        $attendee->api_state = $api_address->State->__toString();
        $attendee->api_zip5 = $api_address->Zip5->__toString();
        $attendee->ceu = $req->input('ceu');
        $attendee->save();

        //add training role
        Auth::user()->addRole('hcwp');

        //add active campaign tag
        $this->ac = new \ActiveCampaign(\Config::get('services.activecampaign.url'), \Config::get('services.activecampaign.key'));
        $contact = array(
            "email"              => Auth::user()->email,
            "tags"               => "Website: Active, Training: HCWP",
            "field[1,0]"         => $user->id,
            "fieldValues"        => array(
                //["fieldID" => 1, //User ID
                //"value"     => Auth::user()->id,],
                ["fieldID" => 4, //Address
                "value"     => $api_address]),
	    );
        $contact_sync = $this->ac->api("contact/sync", $contact);

        //send email
        //$email_temp = new HcwpEmail(Auth::user()->name, Auth::user()->email, $api_address);
        //$job = new \App\Jobs\HcwpEmail(Auth::user()->name, Auth::user()->email, $api_address, $email_temp);
        //$job->dispatch(Auth::user()->name, Auth::user()->email, $api_address, $email_temp);

        return response()->json(['error' => false]);
    }

    //final
    public function step2(Request $request){
        $request->session()->forget('hcwp');
        return view('backend.content-trainings.hcwp.index');
    }

    //public function step3(){
    //    $name = 'Tester';
    //    $email = 'test@email.com';
    //    $api_address = '123 address';

    //    $email_temp = new HcwpEmail($name, $email, $api_address);
    //    $job = new \App\Jobs\HcwpEmail($name, $email, $api_address, $email_temp);
    //    $job->dispatch($name, $email, $api_address, $email_temp);
    //}
}
