<?php
namespace App\Http\Controllers\References;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResearchLegal;
use App\Models\ResearchPedagogy;
use App\Models\ResearchStatistics;
class LegalController extends Controller
{
    //Research main page route
    public function research($path = 'educators'){
        return view('free-content.research.research')
        ->with('path', get_path($path));
    }

    //Legal public view
    public function legal(Request $request, $path = 'educators'){
        $entries = ResearchLegal::where([
            ['keywords', '!=', null],
            [function ($query) use ($request){
                if (($term = $request->term)){
                    $query->orWhere('title', 'LIKE', '%' . $term . '%' )
                    ->orWhere('keywords', 'LIKE', '%' . $term . '%' )->get();
                }
            }]
        ])
        ->orderBy('year', 'desc')
        ->paginate(1000);
        return view('free-content.research.legal', compact('entries'))
        ->with('i', (request()->input('page, 1') - 1) *5)
        ->with('path', get_path($path));
    }

    //Pedagogy CRUD
    public function l_index($path = 'educators'){
        return view('backend.research.legal.index')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_add($path = 'educators'){
        return view('backend.research.legal.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_create(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $state = $request->input('state');
        if(!$state || !strlen(trim($state))){
            $error = true;
            $message = 'Pick a state.';
        }
        $column = $request->input('column');
        if(!$column || !strlen(trim($column))){
            $error = true;
            $message = 'Column is required.';
        }
        $code = $request->input('code');
        if(!$code || !strlen(trim($code))){
            $error = true;
            $message = 'Code is required.';
        }
        $info = $request->input('info');
        if(!$info || !strlen(trim($info))){
            $error = true;
            $message = 'Information is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Add citation to database
        $entry = new ResearchLegal();
        $entry->state = trim($state);
        $entry->column = trim($column);
        $entry->code = trim($code);
        $entry->info = trim($info);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_view($path = 'educators', $id){
        $entry = ResearchLegal::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'training'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_show($path = 'educators', $id){
        $entry = ResearchLegal::find($id);
        if(!$entry){
            abort(404);
        }
        return view('backend.research.legal.edit')
        ->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_update(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $state = $request->input('state');
        if(!$state || !strlen(trim($state))){
            $error = true;
            $message = 'Please pick a state.';
        }
        $column = $request->input('column');
        if(!$column || !strlen(trim($column))){
            $error = true;
            $message = 'Column is required.';
        }
        $code = $request->input('code');
        if(!$code || !strlen(trim($code))){
            $error = true;
            $message = 'Code is required.';
        }
        $info = $request->input('info');
        if(!$info || !strlen(trim($info))){
            $error = true;
            $message = 'Information is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = ResearchLegal::where('code', trim($code))
                ->where('id','<>', $request->input('id'))
                ->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'Information with this code already exists.']);
        }
        //Edit citation in database
        $entry = ResearchLegal::find($request->input('id'));
        $entry->state = trim($state);
        $entry->column = trim($column);
        $entry->code = trim($code);
        $entry->info = trim($info);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_content($path = 'educators', $id){
        $entry = ResearchLegal::find($id);
        if(!$entry)
            abort(404);
        return view('backend.research.legal.content')
        ->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function l_delete($path = 'educators', $id){
        $entry = ResearchLegal::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        ResearchLegal::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Information deleted.'])
        ->with('section', 'backend')
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
    public function p_index($path = 'educators'){
        return view('backend.research.pedagogy.index')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function p_add($path = 'educators'){
        return view('backend.research.pedagogy.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function p_create(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Citation needs a title.';
        }
        $author = $request->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'Author is required.';
        }
        $journal = $request->input('journal');
        if(!$journal || !strlen(trim($journal))){
            $error = true;
            $message = 'Journal is required.';
        }
        $year = $request->input('year');
        if(!$year || !strlen(trim($year))){
            $error = true;
            $message = 'A year is required.';
        }
        $month = $request->input('month');
        if(!$month || !strlen(trim($month))){
            $error = true;
            $message = 'The month is required.';
        }
        $keywords = $request->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'Keywords are required.';
        }
        $url = $request->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'A url is required.';
        }
        $abstract = $request->input('abstract');
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

    public function p_view($path = 'educators', $id){
        $entry = ResearchPedagogy::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'training'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function p_show($path = 'educators', $id){
        $entry = ResearchPedagogy::find($id);
        if(!$entry){
            abort(404);
        }
        return view('backend.research.pedagogy.edit')
        ->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function p_update(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Citation needs a title.';
        }
        $author = $request->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'Author is required.';
        }
        $journal = $request->input('journal');
        if(!$journal || !strlen(trim($journal))){
            $error = true;
            $message = 'Journal is required.';
        }
        $year = $request->input('year');
        if(!$year || !strlen(trim($year))){
            $error = true;
            $message = 'A year is required.';
        }
        $month = $request->input('month');
        if(!$month || !strlen(trim($month))){
            $error = true;
            $message = 'The month is required.';
        }
        $keywords = $request->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'Keywords are required.';
        }
        $url = $request->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'A url is required.';
        }
        $abstract = $request->input('abstract');
        if(!$abstract || !strlen(trim($abstract))){
            $error = true;
            $message = 'The abstract is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = ResearchPedagogy::where('title', trim($title))
                ->where('id','<>', $request->input('id'))
                ->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'A citation with this title already exists.']);
        }
        //Edit citation in database
        $entry = ResearchPedagogy::find($request->input('id'));
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

    public function p_content($path = 'educators', $id){
        $entry = ResearchPedagogy::find($id);
        if(!$entry)
            abort(404);
        return view('backend.research.pedagogy.content')->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function p_delete($path = 'educators', $id){
        $entry = ResearchPedagogy::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        ResearchPedagogy::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Citation deleted.'])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    //Statistics public view
    public function statistics(Request $request, $path = 'educators'){
        $entries = ResearchStatistics::where([
            ['keywords', '!=', null],
            [function ($query) use ($request){
                if (($term = $request->term)){
                    $query->orWhere('title', 'LIKE', '%' . $term . '%' )
                    ->orWhere('keywords', 'LIKE', '%' . $term . '%' )->get();
                }
            }]
        ])
        ->orderBy('year', 'desc')
        ->paginate(1000);
        return view('free-content.research.statistics', compact('entries'))
        ->with('i', (request()->input('page, 1') - 1) *5)
        ->with('path', get_path($path));
    }

    //Statistics CRUD
    public function s_index($path = 'educators'){
        return view('backend.research.statistics.index')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }
    public function s_add($path = 'educators'){
        return view('backend.research.statistics.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function s_create(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Citation needs a title.';
        }
        $author = $request->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'Author is required.';
        }
        $journal = $request->input('journal');
        if(!$journal || !strlen(trim($journal))){
            $error = true;
            $message = 'Journal is required.';
        }
        $year = $request->input('year');
        if(!$year || !strlen(trim($year))){
            $error = true;
            $message = 'A year is required.';
        }
        $month = $request->input('month');
        if(!$month || !strlen(trim($month))){
            $error = true;
            $message = 'The month is required.';
        }
        $keywords = $request->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'Keywords are required.';
        }
        $url = $request->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'A url is required.';
        }
        $abstract = $request->input('abstract');
        if(!$abstract || !strlen(trim($abstract))){
            $error = true;
            $message = 'The abstract is required.';
        }
        //Check errors
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = ResearchStatistics::where('title', trim($title))->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'A citation with the same title already exists.']);
        }
        //Add citation to database
        $entry = new ResearchStatistics();
        $entry->title = trim($title);
        $entry->author = trim($author);
        $entry->journal = trim($journal);
        $entry->year = trim($year);
        $entry->month = trim($month);
        $entry->keywords = trim($keywords);
        $entry->url = trim($url);
        $entry->abstract = trim($abstract);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function s_view($path = 'educators', $id){
        $entry = ResearchStatistics::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'entry'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function s_show($path = 'educators', $id){
        $entry = ResearchStatistics::find($id);
        if(!$entry){
            abort(404);
        }
        return view('backend.research.statistics.edit')->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function s_update(Request $request, $path = 'educators'){
        $error = false;
        $message = '';
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Citation needs a title.';
        }
        $author = $request->input('author');
        if(!$author || !strlen(trim($author))){
            $error = true;
            $message = 'Author is required.';
        }
        $journal = $request->input('journal');
        if(!$journal || !strlen(trim($journal))){
            $error = true;
            $message = 'Journal is required.';
        }
        $year = $request->input('year');
        if(!$year || !strlen(trim($year))){
            $error = true;
            $message = 'A year is required.';
        }
        $month = $request->input('month');
        if(!$month || !strlen(trim($month))){
            $error = true;
            $message = 'The month is required.';
        }
        $keywords = $request->input('keywords');
        if(!$keywords || !strlen(trim($keywords))){
            $error = true;
            $message = 'Keywords are required.';
        }
        $url = $request->input('url');
        if(!$url || !strlen(trim($url))){
            $error = true;
            $message = 'A url is required.';
        }
        $abstract = $request->input('abstract');
        if(!$abstract || !strlen(trim($abstract))){
            $error = true;
            $message = 'The abstract is required.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $entry = ResearchStatistics::where('title', trim($title))
                ->where('id','<>', $request->input('id'))
                ->first();
        if($entry){
            return response()->json(['error'=>true, 'message'=>'A citation with this title already exists.']);
        }
        $entry = ResearchStatistics::find($request->input('id'));
        $entry->title = trim($title);
        $entry->author = trim($author);
        $entry->journal = trim($journal);
        $entry->year = trim($year);
        $entry->month = trim($month);
        $entry->keywords = trim($keywords);
        $entry->url = trim($url);
        $entry->abstract = trim($abstract);
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function s_content($path = 'educators', $id){
        $entry = ResearchStatistics::find($id);
        if(!$entry)
            abort(404);
        return view('backend.research.statistics.content')->with('entry', $entry)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function s_delete($path = 'educators', $id){
        $entry = ResearchStatistics::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        ResearchStatistics::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Citation deleted.'])
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }
}
