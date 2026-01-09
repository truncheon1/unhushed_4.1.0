@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            Space Sharks: A Choose Your Own Adventure
        </div>
    </div>


    <div class="container pt-5 pb-5">
        <!-- GAME -->
        <div class="card rounded">
            <!-- images -->
            <div class="row mx-auto p-5">
                <div class="col-4">
                    <img class="gameBox2" src="{{ asset('img/games/space-sharks/cover.png') }}">
                </div>
                <div class="col-lg-8 col-md-12">
                    <!-- title -->
                    <p class="diazo">SPACE SHARKS</p>
                    <!-- description -->
                    <p class="sketchnote-square">You're in training and this is your third and final mission to become a full fledged special agent. Safely navigate a chat with a stranger in the hottest online game, Space Sharks!
                    <p class="sketchnote-square">Pick your path by deciding what information to share, how you might use your parent's credit card, and what victory snacks to eat.</p>
                    <p class="sketchnote-square">This choose your own adventure game is part of <i>UN|HUSHED: The Elementary School Curriculum's, 5th Grade, Session 05: Instafraud</i>
                        where we talk about internet safety and how to fact check information.</p>
                </div>
                <div class="col-12 mx-auto">
                    <p class="diazo text-center" style="font-size:40px">
                        <a href="{{ url($path.'/space-sharks-1') }}" target="_blank"
                        onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=960,height=600');
                        return false;"><b>CLICK TO PLAY</b>
                        </a>
                    </p>
                </div>
            </div>
        </div>
</section>
@endsection
