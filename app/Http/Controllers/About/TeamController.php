<?php
namespace App\Http\Controllers\About;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    //about team
    public function catalog($path = 'educators'){
        return view('about.team.team')
        ->with('member', Team::all())
        ->with('path', get_path($path));
    }

    //about each team member
    public function view_each_member($path = 'educators', $slug){
        $member = Team::where('slug', 'LIKE', $slug)->first();
        if(!$member){
            abort(404);
        }
        return view('about.team.member')
        ->with('path', get_path($path))
        ->with('member', $member);
    }

    //Team CRUD
    public function index($path = 'educators'){
        return view('backend.team.index')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function add($path = 'educators'){
        return view('backend.team.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function create(Request $req, $path = 'educators'){
        $error = false;
        $message = '';
        $bio = $req->input('bio');
        if(!$bio || !strlen(trim($bio))){
            $error = true;
            $message = 'Bio is required.';
        }
        $ac_tags = $req->input('ac_tags');
        if(!$ac_tags || !strlen(trim($ac_tags))){
            $error = true;
            $message = 'Active Campaign tag(s) required.';
        }
        $dept = $req->input('dept');
        if(!$dept || !strlen(trim($dept))){
            $error = true;
            $message = 'Departament required.';
        }
        $slug = $req->input('slug');
        if(!$slug || !strlen(trim($slug))){
            $error = true;
            $message = 'Slug required.';
        }
        $title = $req->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Title required.';
        }
        $last = $req->input('last');
        if(!$last || !strlen(trim($last))){
            $error = true;
            $message = 'Last name required.';
        }
        $first = $req->input('first');
        if(!$first || !strlen(trim($first))){
            $error = true;
            $message = 'First name required.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $member = Team::where('first', trim($first))->where('last', trim($last))->first();
        if($member){
            return response()->json(['error'=>true, 'message'=>'A team member with that name already exists.']);
        }
        //Return the team member
        $member = new Team();
        //Team member image
        if($req->input('file')){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$req->input('file'))){
                copy(config('constant.hold').'/'.$req->input('file'),
                config('constant.team.upload').'/'.$req->input('file'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file){
                if(is_file($file))
                // Delete the given file
                unlink($file);
            }
            $member->image = $req->input('file');
        }else{
            return response()->json(['error'=>true, 'message'=>'Image Required']);
        }
        //REFACTOR NOTE-add active campaign tags to new members
        $member->first = trim($first);
        $member->last = trim($last);
        $member->title = $req->input('title');
        $member->dept = $req->input('dept');
        $member->ac_tags = $req->input('ac_tags');
        $member->slug = $req->input('slug');
        $member->bio = $req->input('bio');
        $member->save();
        return response()->json(['success'=>true, 'member'=>$member]);
    }

    public function view($path = 'educators', $id){
        $member = Team::find($id);
        if(!$member){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'team'=>$member]);
    }

    public function show($path = 'educators', $id){
        $member = Team::find($id);
        if(!$member){
            abort(404);
        }
        return view('backend.team.edit')
        ->with('member', $member)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function update(Request $req, $path = 'educators'){
        $error = false;
        $message = '';
        $first = $req->input('first');
        if(!$first || !strlen(trim($first))){
            $error = true;
            $message = 'Cannot update without first name.';
        }
        $last = $req->input('last');
        if(!$last || !strlen(trim($last))){
            $error = true;
            $message = 'Cannot update without last name.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $member = Team::where('first', trim($first))->where('last', $last)
                ->where('id','<>', $req->input('id'))
                ->first();
        if($member){
            return response()->json(['error'=>true, 'message'=>'A team member with that name already exists.']);
        }
        $member = Team::find($req->input('id'));
        if($req->input('file') && $req->input('file') !== $member->image){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$req->input('file'))){
                copy(   config('constant.hold').'/'.$req->input('file'),
                config('constant.team.upload').'/'.$req->input('file'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file){
                if(is_file($file))
                // Delete the given file
                unlink($file);
            }
            $member->image = $req->input('file');
        }
        //REFACTOR NOTE-add new active campaign tags and remove old ones
        $member->first = trim($first);
        $member->last = trim($last);
        $member->title = $req->input('title');
        $member->dept = $req->input('dept');
        $member->ac_tags = $req->input('ac_tags');
        $member->slug = $req->input('slug');
        $member->bio = $req->input('bio');
        $member->save();
        return response()->json(['success'=>true, 'member'=>$member]);
    }

    public function content($path = 'educators', $id){
        $member = Team::find($id);
        if(!$member)
            abort(404);
        return view('backend.team.content')
        ->with('member', $member)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function delete($path = 'educators', $id){
        $member = Team::find($id);
        if(!$member){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        Team::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Team member deleted.']);
        //REFACTOR NOTE-remove any active campaign tags added during add or update
    }
}
