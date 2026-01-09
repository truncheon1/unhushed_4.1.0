<!-- THIS PAGE DOESN'T WORK RIGHT NOW, GO TO WEB.PHP TO UNCOMMENT OUT PATH -->


@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/more') }}">More Stuff! </a> |
            <a href="{{ url($path.'/arcade') }}">Arcade</a> |
            <a href="{{ url($path.'/blog') }}">Blog</a> |
            <a href="{{ url($path.'/contact') }}">Contact Us</a> |
            <a href="{{ url($path.'/give') }}">More Ways to Give</a> |
            <a href="{{ url($path.'/social') }}">Social Media</a>
        </div>
    </div>
    <!-- ACTIVE CAMPAIGN CODE -->
    <div class="mx-auto mt-5" style="max-width: 550px;">
        <div class="loginCard" style="max-width: 100%;">
            <div class="col">
                <div class="card-body">
                    <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                    <p class="diazo" style="font-size:35px; text-align:center;">Thanks for subscribing!</p>
                    <p class="login-card-description">We're so glad you're here. Want to play some games to celebrate?</p>
                        <p class="sketchnote-square" style="line-height:30px;">To the <a href="{{ url($path.'/arcade') }}">ARCADE</a>!</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
