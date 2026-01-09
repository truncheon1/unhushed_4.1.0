@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <span style="font-weight: bold;color:#9acd57">Apply to Join Us</span>
        </div>
    </div>

    <div class="container">
        <!-- APPLY HEADER-->
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-globe.png') }}" alt="Ollie holding a globe.">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Want to Join the Team?</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:600px">The UN|HUSHED team is made up of authors, trainers, educators, designers, and so much more! If the UN|HUSHED mission, values, and your availabilty to commit allow, we
                        invite you to apply to join our ever growing team below.</p>
                </div>
            </div>
        </div>

        <!-- APPLY FLEX-->
        <div class="row pt-4">
            <div class="d-inline-flex justify-content-center flex-wrap mx-auto">
                <div class="iImg1Inactive">
                    <span>Paid Staff</span>
                    <a href="#"><img src="{{ asset('img/cards-sm/w_news.png') }}" alt="Paid Staff"></a>
                </div>
                <div class="iImg2">
                    Councils
                    <a href="{{ url($path.'/councils') }}"><img src="{{ asset('img/cards-sm/w_YAC.png') }}" alt="Councils"></a>
                </div>
                <div class="iImg3">
                    Board
                    <a href="{{ url($path.'/board') }}"><img src="{{ asset('img/cards-sm/w_board.png') }}" alt="Our Board"></a>
                </div>
                <div class="iImg1">
                    Volunteers
                    <a href="{{ url($path.'/volunteer') }}"><img src="{{ asset('img/cards-sm/w_volunteers.png') }}" alt="Volunteers"></a>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
