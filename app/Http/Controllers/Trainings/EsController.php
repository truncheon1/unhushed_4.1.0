<?php

namespace App\Http\Controllers\Trainings;

use App\Http\Controllers\Controller;

class EsController extends Controller
{
    public function index($path = 'educators'){
        return view('backend.content-trainings.c-es.index')
        ->with('section', 'es')
        ->with('path', get_path($path));
    }
    public function one($path = 'educators'){
        return view('backend.content-trainings.c-es.2025.1')
        ->with('section', 'one')
        ->with('path', get_path($path));
    }
    public function two($path = 'educators'){
        return view('backend.content-trainings.c-es.2025.2')
        ->with('section', 'two')
        ->with('path', get_path($path));
    }
    public function three($path = 'educators'){
        return view('backend.content-trainings.c-es.2025.3')
        ->with('section', 'three')
        ->with('path', get_path($path));
    }
    public function four($path = 'educators'){
        return view('backend.content-trainings.c-es.2025.4')
        ->with('section', 'four')
        ->with('path', get_path($path));
    }
    public function five($path = 'educators'){
        return view('backend.content-trainings.c-es.2025.5')
        ->with('section', 'five')
        ->with('path', get_path($path));
    }
    public function alumni($path = 'educators'){
        return view('backend.content-trainings.c-es.alumni')
        ->with('section', 'es')
        ->with('path', get_path($path));
    }
}
