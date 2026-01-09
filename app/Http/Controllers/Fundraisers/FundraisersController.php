<?php

namespace App\Http\Controllers\Fundraisers;
use App\Http\Controllers\Controller;

class FundraisersController extends Controller
{
    //Birthday Fundraisers
    public function bday($path = 'educators'){
        session(['bday'=> true]);
        return view('fundraisers.bday.2025-10-24')
        ->with('path', get_path($path));
    }
    public function bday2023($path = 'educators'){
        session(['bday'=> true]);
        return view('fundraisers.bday.2023-10-21')
        ->with('path', get_path($path));
    }
    public function bday2022($path = 'educators'){
        session(['bday_2022'=> true]);
        return view('fundraisers.bday.2022-10-22')
        ->with('path', get_path($path));
    }
    public function bday2021($path = 'educators'){
        session(['bday_2021'=> true]);
        return view('fundraisers.bday.2021-10-23')
        ->with('path', get_path($path));
    }
    //BoD retreat Fundraisers
    public function BoD($path = 'educators'){
        return view('fundraisers.retreat.bod-retreat')
        ->with('path', get_path($path));
    }
    //The Belly Project Fundraisers
    public function thebellyproject($path = 'educators'){
        session(['belly'=> true]);
        return view('fundraisers.thebellyproject.thebellyproject')
        ->with('path', get_path($path));
    }
}
