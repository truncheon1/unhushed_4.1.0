<?php

namespace App\Http\Controllers\About;
use App\Http\Controllers\Controller;

class ApplyController extends Controller
{
    //about team
    public function apply($path = 'educators'){
        return view('about.apply.apply')
        ->with('path', get_path($path));
    }

    //board
    public function board($path = 'educators'){
        return view('about.apply.board')
        ->with('path', get_path($path));
    }
    //councils
    public function councils($path = 'educators'){
        return view('about.apply.councils')
        ->with('path', get_path($path));
    }
    //volunteer
    public function volunteer($path = 'educators'){
        return view('about.apply.volunteer')
        ->with('path', get_path($path));
    }
}
