<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biology;

class BiologyController extends Controller
{
    //Main frontview index
    public function biological($path = 'educators'){
        $biologies = Biology::all();
        return view('free-content/dictionaries/biological-sex',compact('biologies'))
            ->with('i', (request()->input('page', 1) - 1) * 5)
            ->with('section', 'biological')
            ->with('path', get_path($path));
    }

    //Biologies CRUD
    public function index($path = 'educators'){
        return view('backend.dictionaries.biological')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function create(Request $request){
        //Setup error message
        $error = false;
        $message = '';
        //Check fields
        $internal = $request->input('internal');
        if(!$internal || !strlen(trim($internal))){
            $error = true;
            $message = 'Internal is required.';
        }
        $external = $request->input('external');
        if(!$external || !strlen(trim($external))){
            $error = true;
            $message = 'External is required.';
        }
        $gonads = $request->input('gonads');
        if(!$gonads || !strlen(trim($gonads))){
            $error = true;
            $message = 'Gonads are required (for this table anyway).';
        }
        $hormones = $request->input('hormones');
        if(!$hormones || !strlen(trim($hormones))){
            $error = true;
            $message = 'Hormones are required.';
        }
        $chromosomes = $request->input('chromosomes');
        if(!$chromosomes || !strlen(trim($chromosomes))){
            $error = true;
            $message = 'Chromosomes are required.';
        }
        $term = $request->input('term');
        if(!$term || !strlen(trim($term))){
            $error = true;
            $message = 'A term is required.';
        }
        //Send error for above fields
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Check term doesn't exist
        $b = Biology::where('term', trim($term))->first();
        if($b){
            return response()->json(['error'=>true, 'message'=>'A term with the same name already exists.']);
        }
        //Save
        $b = new Biology();
        $b->term = trim($term);
        $b->chromosomes = trim($chromosomes);
        $b->hormones = trim($hormones);
        $b->gonads = trim($gonads);
        $b->external = trim($external);
        $b->internal = trim($internal);
        $b->save();
        return response()->json(['error'=>false, 'b'=>$b]);
    }

    public function view($id){
        $b = Biology::find($id);
        if(!$b){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'biology'=>$b]);
    }

    public function update(Request $request){
        //Setup error message
        $error = false;
        $message = '';
        //Find term
        $b = Biology::find($request->input('id'));
        //Check fields
        $internal = $request->input('internal');
        if(!$internal || !strlen(trim($internal))){
            $error = true;
            $message = 'Internal is required.';
        }
        $external = $request->input('external');
        if(!$external || !strlen(trim($external))){
            $error = true;
            $message = 'External is required.';
        }
        $gonads = $request->input('gonads');
        if(!$gonads || !strlen(trim($gonads))){
            $error = true;
            $message = 'Gonads are required (for this table anyway).';
        }
        $hormones = $request->input('hormones');
        if(!$hormones || !strlen(trim($hormones))){
            $error = true;
            $message = 'Hormones are required.';
        }
        $chromosomes = $request->input('chromosomes');
        if(!$chromosomes || !strlen(trim($chromosomes))){
            $error = true;
            $message = 'Chromosomes are required.';
        }
        $term = $request->input('term');
        if(!$term || !strlen(trim($term))){
            $error = true;
            $message = 'A term is required.';
        }
        //Send error for above fields
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Save
        $b = new Biology();
        $b->term = trim($term);
        $b->chromosomes = trim($chromosomes);
        $b->hormones = trim($hormones);
        $b->gonads = trim($gonads);
        $b->external = trim($external);
        $b->internal = trim($internal);
        $b->save();
        return response()->json(['error'=>false, 'b'=>$b]);
    }

    public function delete($path = 'educators', $id){
        Biology::destroy($id);
        return response()->json(['success'=>true]);
    }
}
