<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    //Wild card setup
    const ACCEPTED_PATHS = [
        'educators',
        'organizations',
        'professionals',
        'parents',
        'youth'

    ];
    private function get_path($path){
        return in_array($path, self::ACCEPTED_PATHS)? $path : 'educators';
    }

    public function index($path = 'educators') {
        return view('backend.dashboards.dashboard')->with('section', 'dashboard')->with('path', $this->get_path($path));
    }

    public function address_book($path = 'educators') {
        $states = [
            'AL'=>'Alabama', 'AK'=>'Alaska', 'AZ'=>'Arizona', 'AR'=>'Arkansas',
            'CA'=>'California', 'CO'=>'Colorado', 'CT'=>'Connecticut', 'DE'=>'Delaware',
            'DC'=>'District Of Columbia', 'FL'=>'Florida', 'GA'=>'Georgia', 'HI'=>'Hawaii',
            'ID'=>'Idaho', 'IL'=>'Illinois', 'IN'=>'Indiana', 'IA'=>'Iowa',
            'KS'=>'Kansas', 'KY'=>'Kentucky', 'LA'=>'Louisiana', 'ME'=>'Maine',
            'MD'=>'Maryland', 'MA'=>'Massachusetts', 'MI'=>'Michigan', 'MN'=>'Minnesota',
            'MS'=>'Mississippi', 'MO'=>'Missouri', 'MT'=>'Montana', 'NE'=>'Nebraska',
            'NV'=>'Nevada', 'NH'=>'New Hampshire', 'NJ'=>'New Jersey', 'NM'=>'New Mexico',
            'NY'=>'New York', 'NC'=>'North Carolina', 'ND'=>'North Dakota', 'OH'=>'Ohio',
            'OK'=>'Oklahoma', 'OR'=>'Oregon', 'PA'=>'Pennsylvania', 'RI'=>'Rhode Island',
            'SC'=>'South Carolina', 'SD'=>'South Dakota', 'TN'=>'Tennessee', 'TX'=>'Texas',
            'UT'=>'Utah', 'VT'=>'Vermont', 'VA'=>'Virginia', 'WA'=>'Washington',
            'WV'=>'West Virginia', 'WI'=>'Wisconsin', 'WY'=>'Wyoming'
        ];
        
        $address = new UserAddress();
        $address->name = auth()->user()->name ?? '';
        $address->email = auth()->user()->email ?? '';
        
        return view('backend.dashboards.address-book')
            ->with('section', 'dashboard')
            ->with('sect2', 'address-book')
            ->with('path', $this->get_path($path))
            ->with('states', $states)
            ->with('address', $address);
    }

    public function getAddress($path, $id) {
        $address = UserAddress::where('user_id', auth()->id())
            ->findOrFail($id);
        return response()->json($address);
    }

    public function setDefaultAddress(\Illuminate\Http\Request $request, $path, $id) {
        $address = UserAddress::where('user_id', auth()->id())
            ->findOrFail($id);
        $address->setAsDefault();
        return response()->json(['success' => true, 'message' => 'Default address updated']);
    }

    public function deleteAddress($path, $id) {
        $address = UserAddress::where('user_id', auth()->id())
            ->findOrFail($id);
        
        if($address->default) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete default address. Set another address as default first.'
            ], 400);
        }
        
        $address->delete(); // Soft delete
        return response()->json(['success' => true, 'message' => 'Address deleted successfully']);
    }

    public function upload(Request $req) {
        if (!is_dir(config('constant.avatar.upload'))) {
            mkdir(config('constant.avatar.upload'));
        }
        if (!is_dir(config('constant.hold'))) {
            mkdir(config('constant.hold'));
        }
        /* Getting file name */
        $file = $req->file('file');
        if (is_array($req->file('file')))
            $file = $req->file('file')[0];
        if (filesize($file)) {
            $mime = $file->getMimeType();
            if (!strstr($mime, 'image')) {
                return response()->json(['success' => false, 'reason' => 'File must be an image.']);
            }
            $filenfo = $file->getClientOriginalName();
            $extension = pathinfo($filenfo, PATHINFO_EXTENSION);
            $image_name = bin2hex(random_bytes(10)) . '.' . strtolower($extension);
            $file->move(config('constant.hold'), $image_name);
            return response()->json(['success' => true, 'file' => $image_name, 'filePath' => url(config('constant.hold')) . "/" . $image_name]);
        }
        return response()->json(['success' => false, 'reason' => 'No files uploaded.']);
    }

    public function update_profile(Request $req){
        $req->validate([
            'name' => 'bail|required|min:5|max:190',
            'file' => 'required:max190'
        ]);
        //copy image from temp
        if(is_file(config('constant.hold').'/'.$req->input('file'))){
            copy(config('constant.hold').'/'.$req->input('file'),
            config('constant.avatar.upload').'/'.$req->input('file'));
        }
        //remove temp files
        $temp_folder = config('constant.hold');
        $files = glob($temp_folder.'/*');
        foreach($files as $file){
            if(is_file($file))
            // Delete the given file
            unlink($file);
        }
        $user = User::find(auth()->user()->id);
        $user->avatar = $req->input('file');
        $user->name = $req->input('name');
        $user->save();
        return redirect()->back()->with('success', 'profile updated');
    }

    public function update_password(Request $req){
        $req->validate([
            'current_password' => 'bail|required|min:6|max:190',
            'new_password' => 'bail|required|min:6|max:190|same:confirm_password'
        ]);
        //make sure the correct password has been filled in
        $user = User::find(auth()->user()->id);
        $valid_password = Hash::check($req->input('current_password'), $user->password);
        if(!$valid_password){
            return redirect()->back()->with('status', 'The current password is incorrect!');
        }
        //update the password
        $user->password = Hash::make($req->input('new_password'));
        $user->save();
        return redirect()->back()->with('success', 'Password changed!');
    }
}
