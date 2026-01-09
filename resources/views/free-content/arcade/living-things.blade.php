@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            Living Things: A Memory Game
        </div>
    </div>


    <div class="container pt-5 pb-5">
        <!-- GAME -->
        <div class="card">
            <!-- images -->
            <div class="row mx-auto p-5">
                <div class="col-4">
                    <img class="gameBox2" src="{{ asset('img/games/living/cover.png') }}">
                </div>
                <div class="col-lg-8 col-md-12 mx-auto">
                    <!-- title -->
                    <p class="diazo">Living Things:
                        <br>A Memory Game</p>
                    <!-- description -->
                    <p class="sketchnote-square">Match the reproduction type with the creature that reproduces that way!
                        Circles are creatures, squares are reproduction types.
                        You can also match by card colors.</p>
                </div>
                <div class="col-12 mx-auto">
                    <p class="diazo text-center" style="font-size:40px">
                        <a href="{{ url($path.'/living-things-play') }}" target="_blank"
                        onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=800');
                        return false;"><b>CLICK TO PLAY</b>
                        </a>
                    </p>
                </div>
            </div>
        </div>
</section>
@endsection
