<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    //pre-k
    public function prek($path = 'parents'){
        return view('parents.pre-k')->with('path', get_path($path));
    }

    //k-5
    public function kthru5($path = 'parents'){
        return view('parents.k-thru-5')->with('path', get_path($path));
    }

    //6-8
    public function sixthrueight($path = 'parents'){
        return view('parents.six-thru-eight')->with('path', get_path($path));
    }
        public function classMs($path = 'parents'){
            return view('parents.class-ccms')->with('path', get_path($path));
        }
        public function classMsReg($path = 'parents'){
            session(['class-ms-reg'=> true]);
            return view('parents.class-ms-reg')
            ->with('path', get_path($path));
        }

    //9-12
    public function ninethrutwelve($path = 'parents'){
        return view('parents.nine-thru-twelve')->with('path', get_path($path));
    }
        public function classHs($path = 'parents'){
            return view('parents.class-hs')->with('path', get_path($path));
        }
        public function classHsReg($path = 'parents'){
            session(['class-hs-reg'=> true]);
            return view('parents.class-hs-reg')
            ->with('path', get_path($path));
        }

    //parents
    public function parents($path = 'parents'){
        return view('parents.parents')->with('path', get_path($path));
    }

    public function index($path = 'parents'){
            return view('backend.content-parents.index')
            ->with('path', get_path($path));
        }

}
