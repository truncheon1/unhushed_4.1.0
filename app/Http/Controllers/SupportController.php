<?php

namespace App\Http\Controllers;

class SupportController extends Controller
{
    //Suport Page
    public function support($path = 'educators'){
        return view('support.support')
        ->with('path', get_path($path));
    }

    //Contact Us Form - Active Campaign
    public function contact($path = 'educators'){
        return view('support.contact')
        ->with('path', get_path($path));
    }

    //Curriculum Review Form - Active Campaign
    public function cReview($path = 'educators'){
        return view('support.c-review')
        ->with('path', get_path($path));
    }

    //Bug Form - Active Campaign
    public function bug($path = 'educators'){
        return view('support.bug')
        ->with('path', get_path($path));
    }

    //Thanks for booking - Calendly
    public function booked($path = 'educators'){
        return view('support.booked')
        ->with('path', get_path($path));
    }

}
