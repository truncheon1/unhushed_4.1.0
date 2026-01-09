<?php

namespace App\Http\Controllers\Data;
use App\Http\Controllers\Controller;
use App\Models\DataFamilies;
use App\Models\DataParents;
use App\Models\DataParticipants;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class EffectController extends Controller
{
    //Main index
    public function index1(){
        return view('backend.data.effective.index');
    }

    //CRUD view parents
    public function index2(Request $req, $path = 'educators'){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-data'))
            abort(503);
        $fid = 0;
        if($req->family_id)
            $fid = $req->family_id;

        if($fid != 0){
            $family = DataFamilies::find($fid);
            $family_id = $family->id;
        }else{
            $family_id = 0;
        }
        $page = $req->input('page') || 0;
        return view('backend.data.effective.parents.index')->with(
            [
                'families'=> DataFamilies::orderBy('name', 'ASC')->get(),
                'parents' => DataParents::where('family_id', $family_id)->get(),
                'fid'=>$family_id,
                'path', get_path($path)
            ]
        );
    }
    //CRUD view past participants
    public function index3(){
        return view('backend.data.effective.participants.index');
    }

    //Parents CRUD
    public function add(){
        return view('backend.data.effective.parents.create');
    }

    public function add_family(Request $request){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-data'))
            abort(503);
        try{
            $request->validate([
                'name' => 'string|required|min:3',
                'notes' => 'string|max:1000|nullable'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $family = new DataFamilies();
        $family->name = $request->input('name');
        $family->notes = $request->input('notes');
        $family->save();
        return response()->json(['success'=>true, 'family'=>$family]);
    }

    //public function add_parent(Request $request){
    //}

    public function parent_view($id){
        $parent = DataParents::find($id);
        if(!$parent){
            return response()->json(['success'=>false, 'message'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'parent'=>$parent]);
    }

    public function parent_show($id){
        $parent = DataParents::find($id);
        if(!$parent){
            abort(404);
        }
        return view('backend.data.effective.parents.edit')->with('parent', $parent);
    }

    //public function update(Request $request){
    //}

    public function delete_parent($id){
        $parent = DataParents::find($id);
        if(!$parent){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        DataParents::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Parent deleted.']);
    }

}
