<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class YouthController extends Controller
{
    //high school drop in
    public function hsDropIn($path = 'youth'){
        return view('youth.hs-drop-in')->with('path', get_path($path));
    }

}
