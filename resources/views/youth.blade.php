@extends('layouts.app')
@section('content')
<section>
    <!-- INDEX HEADER -->
    <div class="containerNoCrumb">
        <!--
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <a href="{{ url($path.'/values') }}"><img src="{{ asset('img/octos/ollie-pride.png') }}"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">WE ARE PROUD TO BE
                    <br><span style="font-size:66px; line-height:20px;">VALUES DRIVEN</span></p>
                    <hr>
                    <p class="rollerscript-rough">Breaking the silence surrounding human sexuality.</p>
                </div>
            </div>
        </div>
    </div>-->

    <!-- FACILITATORS -->
    <div class="bg-paint">
        <div class="iBox1 pt-lg-5 pb-lg-5">
            <div class="d-inline-flex justify-content-center flex-wrap mx-auto">
                <div class="iImg1">
                    Arcade
                    <a href="{{ url($path.'/arcade') }}"><img src="{{ asset('img/cards-sm/w_arcade.png') }}" alt="Arcade"></a>
                </div>
                <div class="iImg2">
                    Join our YAC
                    <a href="{{ url($path.'/councils') }}"><img src="{{ asset('img/cards-sm/w_YAC.png') }}" alt="YAC"></a>
                </div>
                <div class="iImg3">
                    Contact Us
                    <a href="{{ url($path.'/contact') }}"><img src="{{ asset('img/cards-sm/w_feedback.png') }}" alt="Feedback"></a>
                </div>
                <div class="homeText">
                    <p class="diazo" style="font-size:35px; line-height:20px;">FOR YOUTH</p>
                    <hr>
                    <p class="sketchnote-square" style="line-height:30px;">Go check out our online games, or apply to be on our Youth Advisory Council (18-25 yr olds)!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MORE -->
    <div class="iBox1 mb-5">
        <div class="d-inline-flex justify-content-center flex-wrap mx-auto">
            <div class="homeText">
                <p class="diazo" style="font-size:35px; line-height:20px;">... and a little more?</p>
                <hr>
                <p class="sketchnote-square" style="line-height:30px;">Explore the site, check out all that we do (it's a lot!)</p>
            </div>
            <div class="iImg1">
                Newsletter
                <a href="{{ url($path.'/news') }}"><img src="{{ asset('img/cards-sm/w_news.png') }}" alt="Newsletter"></a>
            </div>
            <div class="iImg2">
                Donate
                <a href="{{ url($path.'/donate') }}"><img src="{{ asset('img/cards-sm/w_donate.png') }}" alt="Donate"></a>
            </div>

        </div>
    </div>

</section>
@endsection
