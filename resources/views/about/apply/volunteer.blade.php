@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <a href="{{ url($path.'/apply') }}">Apply </a> |
            <span style="font-weight: bold;color:#9acd57">Volunteers</span>
        </div>
    </div>

    <div class="container">
        <!-- VOLUNTEER HEADER-->
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-globe.png') }}" alt="Ollie the octopus holding a globe"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Apply to join the volunteer crew</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:800px">Click on volunteer titles below to see more information and/or apply.</p>
                </div>
            </div>
        </div>

        <!-- VOLUNTEER CONTENT-->
        <div class="row justify-content-center mx-auto" style="max-width:800px">
            <!-- General Volunteer -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/T4etVWpQh4B4x1JY8" target="_blank">General Volunteer</a></span></p>
                    <p>UN|HUSHED volunteer crew - Any location
                    <br>Part-Time
                    <br>Have an idea of how you could help out? We'd love to hear about it! Please give us a few days to get back to you as we are currently gearing up for a few big projects.</p>
                    <!-- Apply here -->
                    <a href="https://forms.gle/T4etVWpQh4B4x1JY8" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>

            <!-- Student Intern -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/1TUte8PmFpJCHhBf7" target="_blank">Intern</a></span></p>
                    <p>UN|HUSHED volunteer crew - Any location
                    <br>Part-Time
                    <br>Are you currently a student looking for intern hours? Do you like doing research, admin, coding, or anything related to helping dismantle the social stigma around human sexuality? Then click the apply button my friend!</p>
                    <!-- Apply here -->
                    <a href="https://forms.gle/1TUte8PmFpJCHhBf7" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>

            
        </div>
    </div>

</section>
@endsection
