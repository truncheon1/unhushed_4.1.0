@extends('layouts.app')
@section('content')
<meta property="og:site_name" content="UN|HUSHED">
<meta property="og:title" content="High School Community Class">
<meta property="og:description" content="">
<meta property="og:type" content="website">
<meta property="og:locale" content="en">
<meta property="og:url" content="https://unhushed.org/">
<meta property="og:image" content="{{ asset('img/octos/ollie-Qbox.png') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="ATTENTION PARENTS OF 9th & 10th GRADERS! Would you like your youth to get the sex ed you wish you’d had? Then we’ve got you covered!">
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/parents') }}">Home </a> |
            <span style="font-weight: bold;color:#9acd57">High School Classes</span>
        </div>
    </div>

    <!-- MS ClASS HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-Qbox.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">High School Classes</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:680px">ATTENTION PARENTS OF 9th & 10th GRADERS!
                    <br/>Would you like your youth to get the sex ed you wish you’d had?
                    <br/>Then we’ve got you covered!</p>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row pt-3" style="text-align:justify">
            <div class="col-6 mx-auto">
                <p>Let's chat about taking a fully comprehensive 38 hour human sexuality course. There's no homework. 
                    The classes are fun and informative. Participants learn super valuable info they'll use for the rest of their lives.</p>
                <p><b>STEP 1:</b> Parents (this means any legal guardian, mom, dad, grandma, uncle, etc.) sign up and attend the the next in 
                person or online orientation to get the necessary info and sign your youth up for classes that start this fall.*
                    <ul class="green">
                        <b>*NOTE:</b> Attending one of the orientations is required in order to sign your youth up for class.
                        <li class="pl-3">September 17th, 5:00pm - 7:00pm <a href="https://us06web.zoom.us/meeting/register/tZMucO2rrz8tGdWIeCGKjcmkPYCc30XzCg3u" target="_blank">Zoom Registration</a></li> 
                        <li class="pl-3">September 20th, noon - 2:00pm <a href="https://us06web.zoom.us/meeting/register/tZMvd-GtqTMqHNGZX6PbbgxBRgIGTjmewkcD" target="_blank">Zoom Registration</a></li> 
                    </ul>
                </p>
                <p><b>STEP 2:</b> Register for class! (This opens September 18th)
                    <ul class="green">
                        <li class="pl-3">I have attended the required parent/guardain orientation and know what I'm signing my youth up for.</li>
                        <li class="pl-3">I'm ready to <a href="{{ url($path.'/high-school-registration') }}"><b>REGISTER MY YOUTH</b></a></li>
                    </ul>
                </p>
                <p><b>MORE INFO</b>
                    <ul class="green">
                        <li class="pl-3">2023-2024 Classes Sessions are Sundays 5:00 pm - 7:00 pm.</li>
                        <li class="pl-3">Look over the curriculum activity list <a href="{{ url($path.'/nine-thru-twelve') }}">here.</a></li>
                        <li class="pl-3"><a href="{{ url($path.'/news') }}">Sign up for our newsletter here</a> to stay up to date on future classes and all things UN|HUSHED.</li>
                        <li class="pl-3">Visit frequently asked questions <a href="{{ url($path.'/subscription-info') }}">here.</a></li>
                        <li class="pl-3">For more information or questions, <a href="mailto: info@unhushed.org">email us at info@unhushed.org.</a></li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
