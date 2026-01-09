<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    //Wild card setup
    const ACCEPTED_PATHS = [
        'educators',
        'parents',
        'youth',
        'organizations'
    ];
    private function get_path($path){
        return in_array($path, self::ACCEPTED_PATHS)? $path : 'educators';
    }



}
