<?php

namespace App\Http\Controllers\About;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Standards;

class StandardsController extends Controller
{
    //Standards CRUD
    public function index($path = 'educators'){
        $entries = Standards::orderBy('standard', 'DESC')->get();
        return view('backend.standards.index')
        ->with('entries', $entries)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function view($path = 'educators', $id){
        $entry = Standards::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'training'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function add($path = 'educators'){
        return view('backend.standards.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function create(Request $req, $path = 'educators'){
        $error = false;
        $message = '';
        $author = $req->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'The entry needs an author.';
        }
        $standard = $req->input('standard');
        if(!$standard || !strlen(trim($standard))){
            $error = true;
            $message = 'The entry needs an standard.';
        }
        $link = $req->input('link');
        if(!$link || !strlen(trim($link))){
            $error = true;
            $message = 'The entry needs a link.';
        }
        $aligns = $req->input('aligns');
        if(!$aligns || !strlen(trim($aligns))){
            $error = true;
            $message = 'Aligns yes or no?';
        }
        //Check errors & grab image file
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $image = $req->input('file');
        if($req->input('file') && $req->input('file') !== $image){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$req->input('file'))){
                copy(config('constant.hold').'/'.$req->input('file'),
                config('constant.standards.upload').'/'.$req->input('file'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the old file
                    unlink($file);
            }
        }else{
            return response()->json(['error'=>true, 'message'=>'The entry needs an image']);
        }

        $entry = Standards::where('standard', trim($standard))->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'This standard already exists.']);
        }
        //Add entry to database
        $entry = new Standards();
        $entry->author   = trim($author);
        $entry->standard = trim($standard);
        $entry->image    = trim($image);
        $entry->link     = trim($link);
        $entry->aligns   = trim($aligns);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function show($path = 'educators', $id){
        $entry = Standards::find($id);
        if(!$entry){
            abort(404);
        }
        return view('backend.standards.edit')
        ->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function update(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $name = $request->input('name');
        if(!$name || !strlen(trim($name))){
            $error = true;
            $message = 'Standard needs a name.';
        }
        $url = $request->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'URL is required.';
        }
        $aligns = $request->input('aligns');
        if(!$aligns || !strlen(trim($aligns))){
            $error = true;
            $message = 'Does UN|HUSHED align to this standard?';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = Standards::where('name', trim($name))
                ->where('id','<>', $request->input('id'))
                ->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'A standard with this name already exists.']);
        }
        //Edit standard in database
        $entry = Standards::find($request->input('id'));
        $entry->name = trim($name);
        $entry->url = trim($url);
        $entry->aligns = trim($aligns);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function content($path = 'educators', $id){
        $entry = Standards::find($id);
        if(!$entry)
            abort(404);
        return view('backend.standards.content')->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function delete($path = 'educators', $id){
        $entry = Standards::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        Standards::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Standard deleted.']);
    }

    //Flag CRUD
    public function view_flag($path = 'educators', $id){
        $entry = Standards::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function update_flag(Request $req, $path = 'educators'){
        $entry = Standards::find($req->input('id'));
        if($req->input('file') && $req->input('file') !== $entry->flag){
            //copy image from temp
            if(is_file(config('constant.hold').'/'.$req->input('file'))){
                copy(config('constant.hold').'/'.$req->input('file'),
                config('constant.standards.upload').'/'.$req->input('file'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the old file
                    unlink($file);
            }
            $entry->flag = $req->input('file');
        }else{
            return response()->json(['error'=>true, 'message'=>'Image Required']);
        }
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function delete_flag($path = 'educators', $id){
        $entry = Standards::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        Standards::destroy($entry->flag);
        return response()->json(['success'=>true, 'messaage'=>'Flag has been deleted.']);
    }
}
