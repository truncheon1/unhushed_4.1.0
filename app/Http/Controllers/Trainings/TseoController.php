<?php

namespace App\Http\Controllers\Trainings;

use App\Http\Controllers\Controller;

class TseoController extends Controller
{
    public function index($path = 'educators'){
        return view('backend.content-trainings.tseo.index')
        ->with('section', 'tseo')
        ->with('path', get_path($path));
    }
    public function one($path = 'educators'){
        return view('backend.content-trainings.tseo.1')
        ->with('section', 'one')
        ->with('path', get_path($path));
    }
    public function two($path = 'educators'){
        return view('backend.content-trainings.tseo.2')
        ->with('section', 'two')
        ->with('path', get_path($path));
    }
    public function three($path = 'educators'){
        return view('backend.content-trainings.tseo.3')
        ->with('section', 'three')
        ->with('path', get_path($path));
    }
    public function four($path = 'educators'){
        return view('backend.content-trainings.tseo.4')
        ->with('section', 'four')
        ->with('path', get_path($path));
    }
    public function five($path = 'educators'){
        return view('backend.content-trainings.tseo.5')
        ->with('section', 'five')
        ->with('path', get_path($path));
    }
    public function six($path = 'educators'){
        return view('backend.content-trainings.tseo.6')
        ->with('section', 'six')
        ->with('path', get_path($path));
    }
    public function seven($path = 'educators'){
        return view('backend.content-trainings.tseo.7')
        ->with('section', 'seven')
        ->with('path', get_path($path));
    }
    public function eight($path = 'educators'){
        return view('backend.content-trainings.tseo.8')
        ->with('section', 'eight')
        ->with('path', get_path($path));
    }
    public function nine($path = 'educators'){
        return view('backend.content-trainings.tseo.9')
        ->with('section', 'nine')
        ->with('path', get_path($path));
    }
    public function ten($path = 'educators'){
        return view('backend.content-trainings.tseo.10')
        ->with('section', 'ten')
        ->with('path', get_path($path));
    }
    public function bonus($path = 'educators'){
        return view('backend.content-trainings.tseo.bonus')
        ->with('section', 'bonus')
        ->with('path', get_path($path));
    }
}

