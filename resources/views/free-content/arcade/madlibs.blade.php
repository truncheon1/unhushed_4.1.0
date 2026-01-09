@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            The Play's the Thing!: A Madlib Game
        </div>
    </div>


    <div class="container pt-5 pb-5">
        <!-- GAME -->
        <div class="card rounded">
            <!-- images -->
            <div class="row mx-auto p-5">
                <div class="col-4">
                    <img class="gameBox2" src="{{ asset('img/games/madlibs/cover.png') }}">
                </div>
                <div class="col-lg-8 col-md-12">
                    <!-- title -->
                    <p class="diazo">The Play's the Thing!</p>
                    <!-- description -->
                    <p class="sketchnote-square">This Madlibs style game is part of <i>UN|HUSHED: The Elementary School Curriculum's, 4th Grade Unit, Session 04: Give 'em Something to Talk About</i> where we
                    introduce the four basic communication styles (passive, aggressive, passive-aggressive, and assertive. This online game is used in the activity <i>The Play's The Thing</i> were students demonstrate examples of assertive communication.</p>
                </div>
                <div class="col-12 mx-auto">
                    <p class="diazo text-center" style="font-size:40px">
                        <a href="{{ url($path.'/madlibs-play') }}"><b>CLICK TO PLAY</b>
                        </a>
                    </p>
                </div>
            </div>
        </div>
</section>
@endsection
