@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <span style="font-weight: bold;color:#9acd57">Arcade</span>
        </div>
    </div>

    <!-- ORG VALUES HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center pt-2">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-arcade.png') }}" alt="Ollie the octopus playing an arcade game"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Welcome to the Arcade!</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:600px">Here at UN|HUSHED we are curricula writers, book authors, trainers, educators, designers, and so much more-including game developers! So choose your own adventure or guess at some old timey slang terms in our games below.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="containerArcade">
        <div class="d-flex justify-content-center py-3">
            <div class="row no-gutters">
                <!-- Memory game -->
                <div class="col">
                    <a href="{{ url($path.'/living-things') }}"><img class="gameBox1" src="{{ asset('img/games/living/cover.png') }}" alt="The Play's the Things!"></a>
                    <p class="smText">6yrs +</p>
                </div>
                <!-- Madlibs game -->
                <div class="col">
                    <a href="{{ url($path.'/madlibs') }}"><img class="gameBox1" src="{{ asset('img/games/madlibs/cover.png') }}" alt="The Play's the Things!"></a>
                    <p class="smText">9yrs +</p>
                </div>
                <!-- Space Sharks game -->
                <div class="col">
                    <a href="{{ url($path.'/space-sharks') }}"><img class="gameBox1" src="{{ asset('img/games/space-sharks/cover.png') }}" alt="Space Sharks"></a>
                    <p class="smText">10yrs +</p>
                </div>
                <!-- Friendzone game -->
                <div class="col">
                    <a href="{{ url($path.'/friendzone') }}"><img class="gameBox1" src="{{ asset('img/games/friendzone/cover.png') }}" alt="The Friendzone"></a>
                    <p class="smText">11yrs +</p>
                </div>
                <!-- Slang game -->
                <div class="col">
                    <a href="{{ url($path.'/ringerangaroo') }}"><img class="gameBox1" src="{{ asset('img/games/ring/cover.png') }}" alt="Ringerangaroo"></a>
                    <p class="smText">11yrs +</p>
                </div>
                <!-- Lifespan game -->
                <div class="col">
                    <a href="{{ url($path.'/lifespan') }}"><img class="gameBox1" src="{{ asset('img/games/lifespan/cover.png') }}" alt="A Lifespan of Sexuality"></a>
                    <p class="smText">15yrs +</p>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
