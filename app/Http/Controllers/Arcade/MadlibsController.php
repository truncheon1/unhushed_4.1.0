<?php

namespace App\Http\Controllers\Arcade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MadlibsController extends Controller
{
    //Madlibs Game
    public function madlibs($path = 'educators'){
        return view('free-content.arcade.madlibs')
        ->with('path', get_path($path));
    }
    public function madlibs_play($path = 'educators'){
        return view('free-content.arcade.madlibs-play')
        ->with('path', get_path($path));
    }

    //Story Form
    public function mad1(Request $req, $path = 'educators'){
        //validate parts of speech
        try {
            $req->validate([
                'adj1' => 'required|min:3',
                'noun1' => 'required|min:3',
                'place' => 'required|min:3',
                'adj2' => 'required|min:3',
                'adj3' => 'required|min:3',
                'noun2' => 'required|min:3',
                'verb' => 'required|min:3',
                'name' => 'required|min:3',
                'adj4' => 'required|min:3',
                'color' => 'required|min:3',
                'food' => 'required|min:3',
                'veggie' => 'required|min:3',
                'animal' => 'required|min:3',
                'adj5' => 'required|min:3',
                'family' => 'required|min:3',
                'adj6' => 'required|min:3',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => true, 'reason' => $e->errors()]);
        }
        $req->session()->put([
            'adj1' => $req->input('adj1'),
            'noun1' => $req->input('noun1'),
            'place' => $req->input('place'),
            'adj2' => $req->input('adj2'),
            'adj3' => $req->input('adj3'),
            'noun2' => $req->input('noun2'),
            'verb' => $req->input('verb'),
            'name' => $req->input('adj1'),
            'adj4' => $req->input('adj4'),
            'color' => $req->input('color'),
            'food' => $req->input('food'),
            'veggie' => $req->input('veggie'),
            'animal' => $req->input('animal'),
            'adj5' => $req->input('adj5'),
            'family' => $req->input('family'),
            'adj6' => $req->input('adj6'),
        ]);
        return $req->session()->flash('test',
            array('adj1', 'noun1', 'place', 'adj2', 'adj3', 'noun2', 'verb', 'name', 'adj4', 'color', 'food', 'veggie', 'animal', 'adj5', 'family', 'adj6',
        ));
    }
    public function mad2($path = 'educators'){
        return view('free-content.arcade.madlibs-story')
        ->with('path', get_path($path));
    }
}
