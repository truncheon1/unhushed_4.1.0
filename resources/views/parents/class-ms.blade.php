@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/parents') }}">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Austin Middle School Community Classes</span>
        </div>
    </div>

    <!-- MS CLASS HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-Qbox.png') }}" alt="Ollie the octopus holding a question box."></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Austin Community Classes for 7th & 8th Graders</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:680px">ATTENTION PARENTS OF 7th & 8th GRADERS!
                    <br/>Would you like your youth to get the sex ed you wish you’d had?
                    <br/>Then we’ve got you covered!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row pt-3" style="text-align:justify">
            <div class="col-6 mx-auto">
                <p>Let's chat about taking a fully comprehensive 40 hour human sexuality course. There's no homework. 
                    The classes are fun and informative. Participants learn super valuable info they'll use for the rest of their lives.</p>
                <p><b>STEP 1:</b> Parents (this means any legal guardian, mom, dad, grandma, uncle, etc.) sign up and attend the the next in 
                person or online orientation to get the necessary info and sign your youth up for classes that start this fall.*

                <p class="sketchnote-square" style="max-width:680px">ATTENTION PARENTS OF 7th & 8th GRADERS!
                    <br/>Would you like your youth to get the sex ed you wish you’d had?
                    <br/>Then we’ve got you covered!</p>

                    <ul class="green">
                        <b>*NOTE:</b> Attending one of the orientations is required in order to sign your youth up for class.
                        <!--<li class="pl-3">Sunday, August 25th, 2:00pm - 4:00pm <a href="https://forms.gle/BSyNnYju3pJwptrc9" target="_blank">IN PERSON REGISTRATION</a> at Brentwood Social House</li>-->
                        <li class="pl-3">Thursday, September 12th, 7:00pm - 9:00pm <a href="https:unhush.us/parent-zoom" target="_blank">ZOOM REGISTRATION</a></li>
                        <li class="pl-3">If you’ve already attended a parent orientation for an older child, you’re welcome to <a href="mailto: info@unhushed.org">email us at info@unhushed.org</a> to enroll your youth now.</li> 
                    </ul>
                </p>
                <p><b>STEP 2:</b> Register for class!
                    <ul class="green">
                        <li class="pl-3">I have attended the required parent/guardain orientation and know what I'm signing my youth up for.</li>
                        <li class="pl-3">I'm ready to <a href="#"><b>REGISTER MY YOUTH</b></a></li>
                        <!--<a href="{{ url($path.'/middle-school-registration') }}">-->
                    </ul>
                </p>
                <p><b>MORE INFO</b>
                    <ul class="green">
                        <li class="pl-3">2024-2025 Class Sessions are Sundays 2:00 pm - 4:00 pm starting September 15th, 2024.</li>
                        <li class="pl-3">Look over the curriculum activity list <a href="{{ url($path.'/six-thru-eight') }}">here.</a></li>
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
