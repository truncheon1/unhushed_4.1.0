<?php

namespace App\Http\Controllers\Trainings;

use App\Http\Controllers\Controller;

class SingleDayController extends Controller
{
    public function head($path = 'educators'){
        return view('backend.content-trainings.single-day.head')
        ->with('section', 'training')
        ->with('path', get_path($path));
    }

    public function hcwp($path = 'educators'){
        return view('backend.content-trainings.single-day.hcwp')
        ->with('section', 'training')
        ->with('path', get_path($path));
    }

    public function hmhp($path = 'educators'){
        return view('backend.content-trainings.single-day.hmhp')
        ->with('section', 'training')
        ->with('path', get_path($path));
    }

    public function tiktoxic($path = 'educators'){
        return view('backend.content-trainings.single-day.tiktoxic')
        ->with('section', 'training')
        ->with('path', get_path($path));
    }

    public function tiktoxic2($path = 'educators'){
        return view('backend.content-trainings.single-day.tiktoxic-to-optimistic')
        ->with('section', 'training')
        ->with('path', get_path($path));
    }

    public function wlntp($path = 'educators'){
        return view('backend.content-trainings.single-day.wlntp')
        ->with('section', 'training')
        ->with('path', get_path($path));
    }
}
