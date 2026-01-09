@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            Ringerangaroo: The Old Timey Quiz Game
        </div>
    </div>


    <div class="container pt-5 pb-5">
        <!-- GAME -->
        <div class="card rounded">
            <!-- images -->
            <div class="row mx-auto p-5">
                <div class="col-4">
                    <img class="gameBox2" src="{{ asset('img/games/ring/cover.png') }}">
                </div>
                <div class="col-lg-8 col-md-12">
                    <!-- title -->
                    <p class="diazo">Ringerangaroo</p>
                    <!-- description -->
                    <p class="sketchnote-square">Can you guess which sex act these old-timey slang terms are referring to? Click start to give it a whirl!</p>
                    <p class="sketchnote-square">This quiz game is part of UN|HUSHED: The Middle School Curriculum's, Unit 04: Safer Sex Decisions, Session 02: Contraception.
                        The terms gathered for this activity are from the work of lexicographer Jonathon Green. Many of his timelines of slang can be found at:
                        <a href="https://thetimelinesofslang.tumblr.com/">www.thetimelinesofslang.tumblr.com</a></p>
                    </div>
                    <div class="col-12 mx-auto">
                        <p class="diazo text-center" style="font-size:40px">
                            <a href="{{ url($path.'/ringerangaroo-play') }}" target="_blank"
                            onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=960,height=600');
                            return false;"><b>CLICK TO PLAY</b>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
