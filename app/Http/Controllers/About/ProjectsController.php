<?php

namespace App\Http\Controllers\About;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{
    //projects
    public function projects($path = 'educators'){
        return view('about.projects')
        ->with('path', get_path($path));
    }
}
