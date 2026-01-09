<?php

namespace App\Http\Controllers\Trainings;

use App\Http\Controllers\Controller;

class EeslController extends Controller
{
    public function index($path = 'educators'){
        return view('backend.content-trainings.eesl.index')
        ->with('section', 'eesl')
        ->with('path', get_path($path));
    }
    public function una($path = 'educators'){
        return view('backend.content-trainings.eesl.1')
        ->with('section', 'una')
        ->with('path', get_path($path));
    }
    public function dos($path = 'educators'){
        return view('backend.content-trainings.eesl.2')
        ->with('section', 'dos')
        ->with('path', get_path($path));
    }
    public function tres($path = 'educators'){
        return view('backend.content-trainings.eesl.3')
        ->with('section', 'tres')
        ->with('path', get_path($path));
    }
    public function cuatro($path = 'educators'){
        return view('backend.content-trainings.eesl.4')
        ->with('section', 'cuatro')
        ->with('path', get_path($path));
    }
    public function cinco($path = 'educators'){
        return view('backend.content-trainings.eesl.5')
        ->with('section', 'cinco')
        ->with('path', get_path($path));
    }
    public function seis($path = 'educators'){
        return view('backend.content-trainings.eesl.6')
        ->with('section', 'seis')
        ->with('path', get_path($path));
    }
    public function siete($path = 'educators'){
        return view('backend.content-trainings.eesl.7')
        ->with('section', 'siete')
        ->with('path', get_path($path));
    }
    public function ocho($path = 'educators'){
        return view('backend.content-trainings.eesl.8')
        ->with('section', 'ocho')
        ->with('path', get_path($path));
    }
    public function nueve($path = 'educators'){
        return view('backend.content-trainings.eesl.9')
        ->with('section', 'nueve')
        ->with('path', get_path($path));
    }
    public function diez($path = 'educators'){
        return view('backend.content-trainings.eesl.10')
        ->with('section', 'diez')
        ->with('path', get_path($path));
    }
}

