<?php

namespace App\Http\Controllers\Arcade;
use App\Http\Controllers\Controller;

class SpaceSharksController extends Controller
{
    //SpaceSharks Game
    public function spacesharks($path = 'educators'){
        return view('free-content.arcade.space-sharks')
        ->with('path', get_path($path));
    }
    public function spacesharks_1($path = 'educators'){
        return view('free-content.arcade.space-sharks-1')
        ->with('path', get_path($path));
    }
}
