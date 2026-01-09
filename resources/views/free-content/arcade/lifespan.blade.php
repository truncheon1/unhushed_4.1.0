@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            A Lifespan of Sexuality: The Timeline Game
        </div>
    </div>


    <div class="container pt-5 pb-5">
        <!-- GAME -->
        <div class="card">
            <!-- images -->
            <div class="row mx-auto p-5">
                <div class="col-4">
                    <img class="gameBox2" src="{{ asset('img/games/lifespan/cover.png') }}">
                </div>
                <div class="col-lg-8 col-md-12 mx-auto">
                    <!-- title -->
                    <p class="diazo">A Lifespan of Sexuality:
                        <br>The Timeline Game</p>
                    <!-- description -->
                    <p class="sketchnote-square">Howdy! Are you ready to dive into a conversation about what it means that people are sexual beings from the time they’re born until they die?
                    To bust myths about how many teenagers are actually having sex? To talk about what it means if someone experiences a milestone years before or after a partner?
                    We have the perfect classroom and clinical setting tool for you!</p>
                </div>
                <div class="col-12 mx-auto">
                    <p class="diazo text-center" style="font-size:40px">
                        <a href="{{ url($path.'/lifespan-play') }}" target="_blank"
                        onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=960,height=890');
                        return false;"><b>CLICK TO PLAY</b>
                        </a>
                    </p>
                </div>
            </div>
        </div>
</section>
@endsection
