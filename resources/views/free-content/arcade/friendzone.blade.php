@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            The Friendzone: A Choose Your Own Adventure
        </div>
    </div>


    <div class="container pt-5 pb-5">
        <!-- GAME -->
        <div class="card rounded">
            <!-- images -->
            <div class="row mx-auto p-5">
                <div class="col-4">
                    <img class="gameBox2" src="{{ asset('img/games/friendzone/cover.png') }}">
                </div>
                <div class="col-lg-8 col-md-12">
                    <!-- title -->
                    <p class="diazo">THE FRIENDZONE</p>
                    <!-- description -->
                    <p class="sketchnote-square">Your name is Chris and you meet a girl named Mel on your first day as a freshman at a new school.
                        She’s super nice and you think you might have a crush on her.
                    <p class="sketchnote-square">Pick your path to being a good friend or a super creep. See how things go and try to end the game with a healthy relationship.</p>
                    <p class="sketchnote-square">This choose your own adventure game is part of <i>UN|HUSHED: The Middle School Curriculum's, Unit 05: Violence & Consent, Session 04:
                    Heartbreaker</i> where we talk about the concept of the "friendzone" and how that can be damaging to both people involved in a potentially one-sided love.</p>
                </div>
                <div class="col-12 mx-auto">
                    <p class="diazo text-center" style="font-size:40px">
                        <a href="{{ url($path.'/friendzone-play') }}" target="_blank"
                        onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=960,height=600');
                        return false;"><b>CLICK TO PLAY</b>
                        </a>
                    </p>
                </div>
            </div>
        </div>
</section>
@endsection
