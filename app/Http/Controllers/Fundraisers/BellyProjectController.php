<?php

namespace App\Http\Controllers\Fundraisers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BellyImages;
use App\Models\BellyProject;
use Auth;

class BellyProjectController extends Controller
{
    public function submit(Request $request, $path){
        //VALIDATE
        $error = false;
        $message = '';
        //all posts need an image
        if(!$request->input('image')){
            return response()->json(['error' => true, 'message' => 'Please upload at least one image.']);
        }
        //validate fields
        $bio = $request->input('bio');
        if(!$bio || !strlen(trim($bio))){
            $error = true;
            $message = 'Please write up to 250 words on your experience with your body.';
        }
        $abortions = $request->input('abortions');
        if(!$abortions || !strlen(trim($abortions))){
            if(is_numeric($abortions) && ($abortions >= 0) && (intval($abortions) == $abortions)){
            }else{
                $error = true;
                $message = 'Please fill in a number for abortions.';
            }
        }
        $cbirth = $request->input('cbirth');
        if(!$cbirth || !strlen(trim($cbirth))){
            if(is_numeric($cbirth) && ($cbirth >= 0) && (intval($cbirth) == $cbirth)){
            }else{
                $error = true;
                $message = 'Please fill in a number for c-section births.';
            }
        }
        $vbirth = $request->input('vbirth');
        if(!$vbirth || !strlen(trim($vbirth))){
            if(is_numeric($vbirth) && ($vbirth >= 0) && (intval($vbirth) == $vbirth)){
            }else{
                $error = true;
            $message = 'Please fill in a number for vaginal births.';
            }
        }
        $miscarriages = $request->input('miscarriages');
        if(!$miscarriages || !strlen(trim($miscarriages))){
            if(is_numeric($miscarriages) && ($miscarriages >= 0) && (intval($miscarriages) == $miscarriages)){
            }else{
                $error = true;
                $message = 'Please fill in a number for miscarriages.';
            }
        }
        $pregnancies = $request->input('pregnancies');
        if(!$pregnancies || !strlen(trim($pregnancies))){
            if(is_numeric($pregnancies) && ($pregnancies >= 0) && (intval($pregnancies) == $pregnancies)){
            }else{
                $error = true;
                $message = 'Please fill in the number of pregnancies you have had.';
            }
        }
        $country = $request->input('country');
        if(!$country || !strlen(trim($country))){
            $error = true;
            $message = 'Please fill in the country you live in.';
        }
        $gender = $request->input('gender');
        if(!$gender || !strlen(trim($gender))){
            $error = true;
            $message = 'Please fill in your gender identity.';
        }
        $age = $request->input('age');
        if(!$age || !strlen(trim($age))){
            $error = true;
            $message = 'Please fill in your current age.';
        }
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'A post title is required.';
        }
        $email = $request->input('email');
        if(!$email || !strlen(trim($email))){
            $error = true;
            $message = 'Please fill in your email.';
        }
        $name = $request->input('name');
        if(!$name || !strlen(trim($name))){
            $error = true;
            $message = 'Please fill in your full (or at least first) name.';
        }
        //INPUT ERRORS
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $belly = BellyProject::where('title', trim($title))->first();
        if($belly){
            return response()->json(['error'=>true, 'message'=>'A post with this title already exists.']);
        }
        //RETURN THE POST
        $belly = new BellyProject();
        //SAVE BELLY
        $belly->name = trim($name);
        $belly->email = trim($email);
        $belly->title = trim($title);
        $belly->age = $request->input('age');
        $belly->gender = $request->input('gender');
        $belly->country = $request->input('country');
        $belly->pregnancies = $request->input('pregnancies');
        $belly->miscarriages = $request->input('miscarriages');
        $belly->vbirth = $request->input('vbirth');
        $belly->cbirth = $request->input('cbirth');
        $belly->abortions = $request->input('abortions');
        $belly->bio = $request->input('bio');
        $belly->active = 0;
        //SAVE IMAGES
        $sort = 0;
        $save_img = '';
        foreach($request->input('image') as $img){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$img)){
                copy(config('constant.hold').'/'.$img,
                config('constant.belly.upload').'/'.$img);
                $image = new BellyImages();
                $image->image = $img;
                $image->sort = $sort;
                $image->belly_id = $belly->id;
                $image->save();
                $sort++;
                if($save_img == ''){
                    $save_img = $img;
                }
            }
        };
        $belly->save();
        //remove temp files
        $temp_folder = config('constant.hold');
        $files = glob($temp_folder.'/*');
        foreach($files as $file) {
            if(is_file($file))
                // Delete the given file
                unlink($file);
        };
        /*
        Need an opt in for newsletter default would be unchecked.
        //instantiate API to AC*/
        $this->ac = new \ActiveCampaign(\Config::get('services.activecampaign.url'), \Config::get('services.activecampaign.key'));
        $full_name  = explode(" ", $name);
        $last_name  = array_pop($full_name);
        $first_name = count($full_name)? implode(" ", $full_name) : "";
        if(Auth::check()){
            $user_id = auth()->user()->id;
        }
        $contact = array(
            "email"          => $email,
            "first_name"     => $first_name,
            "last_name"      => $last_name,
		    "p[{1}]"         => 1,
		    "status[{1}]"    => 1, // "Active" status
            "tags"           => ('project: Belly-contributor'),
            "field[1,0]"     => $user_id,
        );
        $contact_sync = $this->ac->api("contact/sync", $contact);
        return response()->json(['success'=>true, 'belly'=>$belly]);
    }

    //submitted
    public function thanks(Request $request, $path = 'educators'){
        return view('fundraisers.thebellyproject.thank-you')
            ->with('path', get_path($path));
        }

    //final
    public function index(Request $request, $path = 'educators'){
        $bellies = BellyProject::orderBy('updated_at', 'DESC')->get();
        $posts = [];
        foreach($bellies as $b){
            $id =           $b->id;
            $title =        $b->title;
            $date =         $b->updated_at;
            $age =          $b->age;
            $gender =       $b->gender;
            $country =      $b->country;
            $pregnancies =  $b->pregnancies;
            $miscarriages = $b->miscarriages;
            $vbirths =       $b->vbirths;
            $cbirths =       $b->cbirths;
            $abortions =    $b->abortions;
            $bio =          $b->bio;
            $posts[] = [
                'id'            => $id,
                'title'         => $title,
                'date'          => date('M, jS Y', strtotime($date)),
                'age'           => $age,
                'gender'        => $gender,
                'country'       => $country,
                'pregnancies'   => $pregnancies,
                'miscarriages'  => $miscarriages,
                'vbirths'       => $vbirths,
                'cbirths'       => $cbirths,
                'abortions'     => $abortions,
                'bio'           => $bio,
            ];
            $images = BellyImages::where('belly_id', $id)->orderBy('sort', 'ASC')->get();
        }
        return view('fundraisers.thebellyproject.index')
        ->with('posts', $posts)
        ->with('images', $images)
        ->with('path', get_path($path));
    }
}
