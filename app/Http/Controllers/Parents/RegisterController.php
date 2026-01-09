<?php
namespace App\Http\Controllers\Parents;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Store\ShippingAPI;
use App\Models\MsClassRegistration;
use App\Models\HsClassRegistration;
use App\Services\ActiveCampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(){
        //constructing
    }

    //MS sign up youth
    public function msStep1(Request $req){
        //validate info
        try {
            $req->validate([
                'parent1_name'          =>  'required|min:3',
                'parent1_email'         =>  'required|email',
                'parent1_phone'         =>  'required|regex:/()[0-9]/|not_regex:/[a-z]/|min:9',
                'street'                =>  'required|min:5',
                'city'                  =>  'required|min:5',
                'state'                 =>  'required|min:2',
                'zip'                   =>  'required|string|min:5|max:5',
                'additional_guardian'   =>  'required|min:2',
                'parent2_name'          =>  'nullable|min:3',
                'parent2_email'         =>  'nullable|',
                'parent2_phone'         =>  'nullable|regex:/()[0-9]/|not_regex:/[a-z]/|min:9',
                'youth_name'            =>  'required|min:3',
                'youth_email'           =>  'nullable|unique:ms_class_registrations',
                'youth_phone'           =>  'nullable|regex:/()[0-9]/|not_regex:/[a-z]/|min:9',
                'age'                   =>  'required|int|min:2',
                'grade'                 =>  'required|int|min:1',
                'gender_identity'       =>  'required|min:3',
                'pronouns'              =>  'required|min:3',
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
        $attendee = new MsClassRegistration();
        $attendee->parent1_user_id = auth()->user() ? auth()->user()->id : 0;
        $attendee->parent1_name = $req->input('parent1_name');
        $attendee->parent1_email = $req->input('parent1_email');
        $attendee->parent1_phone = $req->input('parent1_phone');
        $attendee->address1 = $api_address->Address2->__toString();
        $attendee->address2 = $api_address->Address1->__toString();
        $attendee->city = $api_address->City->__toString();
        $attendee->state = $api_address->State->__toString();
        $attendee->zip = $api_address->Zip5->__toString();
        $attendee->add = $req->input('additional_guardian');
        $attendee->parent2_user_id = null;
        $attendee->parent2_name = $req->input('parent2_name');
        $attendee->parent2_email = $req->input('parent2_email');
        $attendee->parent2_phone = $req->input('parent2_phone');
        $attendee->kid_user_id = null;
        $attendee->kid_name = $req->input('youth_name');
        $attendee->kid_email = $req->input('youth_email');
        $attendee->kid_phone = $req->input('youth_phone');
        $attendee->age = $req->input('age');
        $attendee->grade = $req->input('grade');
        $attendee->gender = $req->input('gender_identity');
        $attendee->pronouns = $req->input('pronouns');
        $attendee->year = '2024-2025';
        $attendee->paid = '0';
        $attendee->legal = '';
        $attendee->save();
        //add MS parent role
        //Auth::user()->addRole('parent');
        //sync to active campaign
        $acService = new ActiveCampaignService();
        $acService->syncUserUpdate(Auth::user(), [
            'message' => 'Parent: MS 2024-25',
        ]);
        $acService->addTagByEmail(Auth::user()->email, 'Website: Active');
        $acService->addTagByEmail(Auth::user()->email, 'Parent');
        $acService->addTagByEmail(Auth::user()->email, 'Parent: MS 2024-25');
        return response()->json(['error' => false]);
    }

    //final
    public function MsStep2(Request $request, $path = 'parents'){
        $request->session()->forget('class-ms-reg');
        return view('parents.class-ms-pay')
        ->with('path', get_path($path));
    }

    //pay without form
    public function msPay(Request $request, $path = 'parents'){
        $request->session()->forget('class-ms-reg');
        return view('parents.class-ms-pay')
        ->with('path', get_path($path));
    }

    //HS sign up youth
    public function hsStep1(Request $req, $path = 'parents'){
        //validate info
        try {
            $req->validate([
                'parent1_name'          =>  'required|min:3',
                'parent1_email'         =>  'required|email',
                'parent1_phone'         =>  'required|regex:/()[0-9]/|not_regex:/[a-z]/|min:9',
                'street'                =>  'required|min:5',
                'city'                  =>  'required|min:5',
                'state'                 =>  'required|min:2',
                'zip'                   =>  'required|string|min:5|max:5',
                'additional_guardian'   =>  'required|min:2',
                'parent2_name'          =>  'nullable|min:3',
                'parent2_email'         =>  'nullable|',
                'parent2_phone'         =>  'nullable|regex:/()[0-9]/|not_regex:/[a-z]/|min:9',
                'youth_name'            =>  'required|min:3',
                'youth_email'           =>  'nullable|unique:hs_class_registrations',
                'youth_phone'           =>  'nullable|regex:/()[0-9]/|not_regex:/[a-z]/|min:9',
                'age'                   =>  'required|int|min:2',
                'grade'                 =>  'required|int|min:1',
                'gender_identity'       =>  'required|min:3',
                'pronouns'              =>  'required|min:3',
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
        $attendee = new HsClassRegistration();
        $attendee->parent1_user_id = auth()->user() ? auth()->user()->id : 0;
        $attendee->parent1_name = $req->input('parent1_name');
        $attendee->parent1_email = $req->input('parent1_email');
        $attendee->parent1_phone = $req->input('parent1_phone');
        $attendee->address1 = $api_address->Address2->__toString();
        $attendee->address2 = $api_address->Address1->__toString();
        $attendee->city = $api_address->City->__toString();
        $attendee->state = $api_address->State->__toString();
        $attendee->zip = $api_address->Zip5->__toString();
        $attendee->add = $req->input('additional_guardian');
        $attendee->parent2_user_id = null;
        $attendee->parent2_name = $req->input('parent2_name');
        $attendee->parent2_email = $req->input('parent2_email');
        $attendee->parent2_phone = $req->input('parent2_phone');
        $attendee->kid_user_id = null;
        $attendee->kid_name = $req->input('youth_name');
        $attendee->kid_email = $req->input('youth_email');
        $attendee->kid_phone = $req->input('youth_phone');
        $attendee->age = $req->input('age');
        $attendee->grade = $req->input('grade');
        $attendee->gender = $req->input('gender_identity');
        $attendee->pronouns = $req->input('pronouns');
        $attendee->year = '2024-2025';
        $attendee->paid = '0';
        $attendee->legal = '';
        $attendee->save();
        //add HS parent role
        //Auth::user()->addRole('parent');
        //sync to active campaign
        $acService = new ActiveCampaignService();
        $acService->syncUserUpdate(Auth::user(), [
            'message' => 'Parent: HS 2024-25',
        ]);
        $acService->addTagByEmail(Auth::user()->email, 'Website: Active');
        $acService->addTagByEmail(Auth::user()->email, 'Parent');
        $acService->addTagByEmail(Auth::user()->email, 'Parent: HS 2024-25');
        return response()->json(['error' => false]);
    }

    //final
    public function HsStep2(Request $request, $path = 'parents'){
        $request->session()->forget('class-hs-reg');
        return view('parents.class-hs-pay')
        ->with('path', get_path($path));
    }

    //pay without form
    public function hsPay(Request $request, $path = 'parents'){
        $request->session()->forget('class-hs-reg');
        return view('parents.class-hs-pay')
        ->with('path', get_path($path));
    }
}