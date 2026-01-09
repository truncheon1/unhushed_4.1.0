@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-hs.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">The High School Curriculum</span>
            </div>
        </div>

        <!-- Info Row -->
        <div class="row mx-auto text-center">
            <div class="col">
                <p style="font-size: 18px">
                    Monday June 26th thru Friday June 30th, 10:00am-2:00pm CST.
                </p>
                <a href="https://us06web.zoom.us/j/89380144314?pwd=9OUWanIAIELQloGuZK5nhZb4O8EdLd.1" target="_blank" class="pr-1">
                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                Zoom: This is the same link each day this week.</a>
            </div>
            <div class="col-12">&nbsp;</div>
        </div>

        <!-- Days -->
        <div class="d-flex justify-content-center align-items-md-center flex-wrap pb-2">
            <!-- 1 -->
            <div class="smCard">
                <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/1') }}"><img class="smImg" src="{{ asset('img/cards-med/hello.png') }}" alt="Lights bulbs waiving hello"></a>
                <div class="smText">Day 1: Introductions</div>
            </div>
            <!-- 2 -->
            <div class="smCard">
                <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/2') }}"><img class="smImg" src="{{ asset('img/cards-med/idea.png') }}" alt="Colorful light bulb"></a>
                <div class="smText">Day 2: Facilitation</div>
            </div>
            <!-- 3 -->
            <div class="smCard">
                <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/3') }}"><img class="smImg" src="{{ asset('img/cards-med/design.png') }}" alt="White light bulb and bar graph on black background"></a>
                <div class="smText">Day 3: How to design an UN|HUSHED program</div>
            </div>
            <!-- 4 -->
            <div class="smCard">
                <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/4') }}"><img class="smImg" src="{{ asset('img/cards-med/policy.png') }}" alt="Light bulb with gears inside"></a>
                <div class="smText">Day 4: Management<br/> and policy</div>
            </div>
            <!-- 5 -->
            <div class="smCard">
                <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/5') }}"><img class="smImg" src="{{ asset('img/cards-med/practise.png') }}" alt="Light bulb made of yellow paper"></a>
                <div class="smText">Day 5: Practice</div>
            </div>
        </div>
    </div>
</section>
@endsection





