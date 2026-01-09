<?php
namespace App\Http\Controllers\References;
use App\Http\Controllers\Controller;
use App\Models\DictDefs;
use App\Models\DictImages;
use App\Models\DictTags;
use App\Models\DictTerms;
use App\Models\DictTermsTags;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class DictionaryController extends Controller
{
    const PART = [
        'noun'=>1,
        'pronoun'=>2,
        'verb'=>3,
        'adjective'=>4,
        'adverb'=>5,
        'preposition'=>6,
        'conjunction'=>7,
        'interjection'=>8,
        'determiner'=>9,
        'article'=>10,
    ];

    //Frontend index
    public function sex_ed_dict(Request $request, $path = 'educators'){
        $entries = DictTerms::where([
            ['keywords', '!=', null],
            [function ($query) use ($request){
                if (($word = $request->word)){
                    $query->orWhere('term', 'LIKE', '%' . $word . '%' )
                    ->orWhere('keywords', 'LIKE', '%' . $word . '%' )->get();
                }
            }]
        ])
        ->orderBy('term', 'ASC')
        ->paginate(1000);
        return view('free-content.dictionary', compact('entries'))
        ->with('i', (request()->input('page, 1') - 1) *5)
        ->with('section', 'dictionary')
        ->with('path', get_path($path));
    }

    //CRUD
    public function index($path = 'educators'){
        $entries = [];
        $details = DictTerms::get();
        foreach($details as $detail){
            $id         = $detail->id;
            $term       = $detail->term;
            $phonetic   = $detail->phonetic;
            $audio      = $detail->audio;
            $slug       = $detail->slug;
            $keywords   = $detail->keywords;
            $alt        = $detail->alt;
            $entries[] = [
                'id'        => $id,
                'term'      => $term,
                'phonetic'  => $phonetic,
                'audio'     => $audio,
                'slug'      => $slug,
                'keywords'  => $keywords,
                'alt'       => $alt,
            ];
        }
        return view('backend.dictionaries.index', compact('entries'))
        ->with('path', get_path($path));
    }

    public function add($path = 'educators'){
        return view('backend.dictionaries.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function audio_upload(Request $req) {
        if (!is_dir(config('constant.dict.upload'))) {
            mkdir(config('constant.dict.upload'));
        }
        if (!is_dir(config('constant.hold'))) {
            mkdir(config('constant.hold'));
        }
        /* Getting audio file name */
        $file = $req->file('audio');
        if (is_array($req->file('audio')))
            $file = $req->file('audio')[0];
        if (filesize($file)) {
            $mime = $file->getMimeType();
            if (!strstr($mime, 'audio')) {
                return response()->json(['success' => false, 'reason' => 'File must be audio.']);
            }
            $filenfo = $file->getClientOriginalName();
            $extension = pathinfo($filenfo, PATHINFO_EXTENSION);
            $audio_name = bin2hex(random_bytes(10)) . '.' . strtolower($extension);
            $file->move(config('constant.hold'), $audio_name);
            return response()->json(['success' => true, 'audio' => $audio_name, 'filePath' => url(config('constant.dict.path_temp')) . "/" . $audio_name]);
        }
        return response()->json(['success' => false, 'reason' => 'No audio files uploaded.']);
    }

    public function create(Request $req){
        $error = false;
        $message = '';
        $keywords = $req->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'keywords are required.';
        }
        $slug = $req->input('slug');
        if(!$slug || !strlen(trim($slug))){
            $error = true;
            $message = 'A slug required.';
        }
        $phonetic = $req->input('phonetic');
        if(!$phonetic || !strlen(trim($phonetic))){
            $error = true;
            $message = 'A phonetic pronunciation is required.';
        }
        $term = $req->input('term');
        if(!$term || !strlen(trim($term))){
            $error = true;
            $message = 'A term is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = DictTerms::where('term', trim($term))->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'This term already exists.']);
        }
        //Return the new entry
        $entry = new DictTerms();
        //audio file
        //if($req->input('audio')){
            //copy audio from temp
            if(is_file(config('constant.hold').'/'.$req->input('audio'))){
                copy(config('constant.hold').'/'.$req->input('audio'),
                config('constant.dict.upload').'/'.$req->input('audio'));
            }
            //remove temp files
            $temp_folder = config('constant.hold');
            $files = glob($temp_folder.'/*');
            foreach($files as $file){
                if(is_file($file))
                // Delete the given file
                unlink($file);
            }
            $entry->audio = $req->input('audio');
        //}else{
        //    return response()->json(['error'=>true, 'message'=>'An audio file is required']);
        //}
        $entry->term        = trim($term);
        $entry->phonetic    = $req->input('phonetic');
        $entry->slug        = $req->input('slug');
        $entry->keywords    = $req->input('keywords');
        $entry->alt         = $req->input('alt');
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function view($path = 'educators', $id){
        $entry = DictTerms::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function update_entry(Request $req, $path = 'educators'){
        //validate
        $error = false;
        $message = '';
        $term = $req->input('term');
        if(!$term || !strlen(trim($term))){
            $error = true;
            $message = 'A unique term is required.';
        }
        $phonetic = $req->input('phonetic');
        if(!$phonetic || !strlen(trim($phonetic))){
            $error = true;
            $message = 'A phonetic pronounciation is required.';
        }
        $slug = $req->input('slug');
        if(!$slug || !strlen(trim($slug))){
            $error = true;
            $message = 'A unique slug is required.';
        }
        $keywords = $req->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'At least one keyword is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $term = DictTerms::find($req->input('id'));
        if(!$term){
            return response()->json(['error' => true, 'message' => 'Term not found!']);
        }
        //audio file
        //if($req->input('audio')){
            //copy audio from temp
            // if(is_file(config('constant.hold').'/'.$req->input('audio'))){
            //     copy(config('constant.hold').'/'.$req->input('audio'),
            //     config('constant.dict.upload').'/'.$req->input('audio'));
            // }
            // //remove temp files
            // $temp_folder = config('constant.hold');
            // $files = glob($temp_folder.'/*');
            // foreach($files as $file){
            //     if(is_file($file))
            //     // Delete the given file
            //     unlink($file);
            // }
            // $term->audio = $req->input('audio');
        //}else{
        //    return response()->json(['error'=>true, 'message'=>'An audio file is required']);
        //}
        //save term
        $term->term        = $req->input('term');
        $term->phonetic    = $req->input('phonetic');
        //$term->audio       = $req->input('audio');
        $term->slug        = $req->input('slug');
        $term->keywords    = $req->input('keywords');
        $term->alt         = $req->input('alt');
        $term->save();
        foreach(DictTermsTags::where('term_id', $term->id)->get() as $et){
           DictTermsTags::destroy($et->id);
        }
        if($req->input('tags')){
           foreach($req->input('tags') as $tag){
               $is = DictTermsTags::where('tag_id', $tag)->where('term_id', $term->id)->first();
               if(!$is){
                   $etag = new DictTermsTags();
                   $etag->term_id = $term->id;
                   $etag->tag_id = $tag;
                   $etag->save();
               }
           }
        }
        return response()->json(['error'=>false, 'term'=>$term]);
    }

    public function term_defs($path, $id){
        $term = DictTerms::find($id);
        if(!$term)
            abort(404);
        return response()->json(['success' => true, 'data' =>[
            'term'=>$term,
            'definitions'=>$term->definitions ?? []
        ]]);
    }

    public function term_defs_update(Request $request){
        // dd($request->all());
        $term = DictTerms::find($request->input('id'));
        //dd($request->input('definitions'));
        if(!$term){
            return response()->json(['success' => false, 'message' => 'Term not found']);
        }
        $old_definitions = DictDefs::where('term_id', $term->id)->get();
        $removed = $request->input('delete_definitions');
        $removed = explode("|", $removed);
        //remove or update
        foreach($old_definitions as $d){
            if(isset($request->input('def')[$d->id])){
                $d->def = $request->input('def')[$d->id];
                $d->part = $request->input('part')[$d->id];
                $d->sort = $request->input('sort')[$d->id];
                $d->example = $request->input('example')[$d->id];
                $d->save();
            }else{
                $d->delete();
            }
        }
        if($request->input('def_new')){
            $defs = $request->input('def_new');
            $parts = $request->input('part_new');
            $sorts = $request->input('sort_new');
            $examples = $request->input('example_new');
            //add new rows
            for($x = 0; $x < count($defs); $x++){
                $DictDef = new DictDefs();
                $DictDef->term_id    = $term->id;
                $DictDef->part       = $parts[$x];
                $DictDef->sort       = $sorts[$x];
                $DictDef->def        = $defs[$x];
                $DictDef->example    = $examples[$x];
                $DictDef->save();
            }
        }
        //needs fix: there is no validation messages being seen on save.
        $term->touch();
    }

    //DICT TAGS
    public function save_tags(Request $req, $path){
        if($req->input('r')){
            foreach($req->input('r') as $id=>$root_id){
                $tag = DictTags::find($id);
                if(!$tag){
                    continue;
                }
                $tag->root_id = $root_id;
                if($tag->id != $req->input('c')[$id]){
                    $tag->parent_id = $req->input('c')[$id];
                }
                $tag->name = $req->input('n')[$id];
                $tag->save();
            }
        }
        $i = 0;
        $errors = [];
        if($req->input('root')){
            foreach($req->input('root') as $root_id){
                $parent_id = $req->input('category')[$i];
                $name = $req->input('name')[$i];
                if(!$name || !strlen($name)){
                    $errors[] = 'Row: '.$i.' needs a tag fill!';
                }else{
                    $tag = new DictTags();
                    $tag->root_id = $root_id;
                    $tag->parent_id = $parent_id;
                    $tag->name = $name;
                    $tag->save();
                }
                $i++;
            }
        }
        return response()->json(['error' => false]);
    }
    
    public function delete($path = 'educators', $id){
        $term = DictTerms::find($id);
        if(!$term){
            return response()->json(['error' => true, 'message'=>'Term not found.']);
        }
        //tags
        foreach(DictTermsTags::where('term_id', $term->id)->get() as $ti){
            DictTermsTags::destroy($ti->id);
        }
        //definitions
        foreach(DictDefs::where('term_id', $term->id)->get() as $dd){
            //delete images
            $images = DictImages::where('def_id', $dd->id)->get();
            foreach($images as $i){
                $i->delete();
            }
            DictDefs::destroy($dd->id);
        }
        //delete term from DictTerms
        $term->delete();
        return response()->json(['error' => false]);
    }
}
