<?php

namespace App\Http\Controllers\Trainings;

use App\Http\Controllers\Controller;

class SealController extends Controller
{
    public function index($path = 'educators'){
        return view('backend.content-trainings.se&l.index')
        ->with('section', 'se&l')
        ->with('path', get_path($path));
    }
    public function one($path = 'educators'){
        return view('backend.content-trainings.se&l.1')
        ->with('section', 'one')
        ->with('path', get_path($path));
    }
    public function two($path = 'educators'){
        return view('backend.content-trainings.se&l.2')
        ->with('section', 'two')
        ->with('path', get_path($path));
    }
    public function three($path = 'educators'){
        return view('backend.content-trainings.se&l.3')
        ->with('section', 'three')
        ->with('path', get_path($path));
    }
    public function four($path = 'educators'){
        return view('backend.content-trainings.se&l.4')
        ->with('section', 'four')
        ->with('path', get_path($path));
    }
    public function five($path = 'educators'){
        return view('backend.content-trainings.se&l.5')
        ->with('section', 'five')
        ->with('path', get_path($path));
    }
    public function six($path = 'educators'){
        return view('backend.content-trainings.se&l.6')
        ->with('section', 'six')
        ->with('path', get_path($path));
    }
    public function seven($path = 'educators'){
        return view('backend.content-trainings.se&l.7')
        ->with('section', 'seven')
        ->with('path', get_path($path));
    }
    public function eight($path = 'educators'){
        return view('backend.content-trainings.se&l.8')
        ->with('section', 'eight')
        ->with('path', get_path($path));
    }
    public function nine($path = 'educators'){
        return view('backend.content-trainings.se&l.9')
        ->with('section', 'nine')
        ->with('path', get_path($path));
    }
    public function ten($path = 'educators'){
        return view('backend.content-trainings.se&l.10')
        ->with('section', 'ten')
        ->with('path', get_path($path));
    }
    public function eleven($path = 'educators'){
        return view('backend.content-trainings.se&l.11')
        ->with('section', 'eleven')
        ->with('path', get_path($path));
    }
    public function twelve($path = 'educators'){
        return view('backend.content-trainings.se&l.12')
        ->with('section', 'twelve')
        ->with('path', get_path($path));
    }
}
