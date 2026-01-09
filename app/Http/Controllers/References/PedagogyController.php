<?php
namespace App\Http\Controllers\References;
use App\Http\Controllers\Controller;
use App\Models\ResearchPedagogy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class PedagogyController extends Controller
{
    //Research main page route
    public function research($path = 'educators'){
        return view('free-content.research.research')
        ->with('path', get_path($path));
    }

    //Pedagogy public view
    public function pedagogy(Request $request, $path = 'educators'){
        $entries = ResearchPedagogy::where([
                ['keywords', '!=', null],
                [function ($query) use ($request){
                if (($term = $request->term)){
                $query->orWhere('title', 'LIKE', '%' . $term . '%' )
                ->orWhere('keywords', 'LIKE', '%' . $term . '%' )
                ->get();
            }
        }]
        ])
        ->orderBy('year', 'desc')
        ->paginate(1000);
        return view('free-content.research.pedagogy', compact('entries'))
        ->with('i', (request()->input('page, 1') - 1) *5)
        ->with('path', get_path($path));
    }

    //Pedagogy CRUD
    public function index($path = 'educators'){
        return view('backend.research.pedagogy.index')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function add($path = 'educators'){
        return view('backend.research.pedagogy.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function create(Request $req,){
        $error = false;
        $message = '';
        $title = $req->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Citation needs a title.';
        }
        $author = $req->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'Author is required.';
        }
        $journal = $req->input('journal');
        if(!$journal || !strlen(trim($journal))){
            $error = true;
            $message = 'Journal is required.';
        }
        $year = $req->input('year');
        if(!$year || !strlen(trim($year))){
            $error = true;
            $message = 'A year is required.';
        }
        $month = $req->input('month');
        if(!$month || !strlen(trim($month))){
            $error = true;
            $message = 'The month is required.';
        }
        $keywords = $req->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'Keywords are required.';
        }
        $url = $req->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'A url is required.';
        }
        $abstract = $req->input('abstract');
        if(!$abstract || !strlen(trim($abstract))){
            $error = true;
            $message = 'The abstract is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = ResearchPedagogy::where('title', trim($title))->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'A citation with the same title already exists.']);
        }
        //Add citation to database
        $entry = new ResearchPedagogy();
        $entry->title = trim($title);
        $entry->author = trim($author);
        $entry->journal = trim($journal);
        $entry->year = trim($year);
        $entry->month = trim($month);
        $entry->keywords = trim($keywords);
        $entry->url = trim($url);
        $entry->abstract = trim($abstract);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function view($path = 'educators', $id){
        $entry = ResearchPedagogy::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function update(Request $req, $path = 'educators'){
        //validate
        $error = false;
        $message = '';
        $title = $req->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Citation needs a title.';
        }
        $author = $req->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'Author is required.';
        }
        $journal = $req->input('journal');
        if(!$journal || !strlen(trim($journal))){
            $error = true;
            $message = 'Journal is required.';
        }
        $year = $req->input('year');
        if(!$year || !strlen(trim($year))){
            $error = true;
            $message = 'A year is required.';
        }
        $month = $req->input('month');
        if(!$month || $month = ''){;
        }
        $keywords = $req->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'Keywords are required.';
        }
        $url = $req->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'A url is required.';
        }
        $abstract = $req->input('abstract');
        if(!$abstract || !strlen(trim($abstract))){
            $error = true;
            $message = 'The abstract is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = ResearchPedagogy::where('title', trim($title))
                ->where('id','<>', $req->input('id'))
                ->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'A citation with this title already exists.']);
        }
        //Edit citation in database
        $entry = ResearchPedagogy::find($req->input('id'));
        $entry->title = trim($title);
        $entry->author = trim($author);
        $entry->journal = trim($journal);
        $entry->year = trim($year);
        $entry->month = trim($month);
        $entry->keywords = trim($keywords);
        $entry->url = trim($url);
        $entry->abstract = trim($abstract);
        $entry->save();
        return response()->json(['error'=>false, 'entry'=>$entry]);
    }

    public function delete($path = 'educators', $id){
        $term = ResearchPedagogy::find($id);
        if(!$term){
            return response()->json(['error' => true, 'message'=>'Term not found.']);
        }
        //delete entry
        $term->delete();
        return response()->json(['error'=>false]);
    }
}
