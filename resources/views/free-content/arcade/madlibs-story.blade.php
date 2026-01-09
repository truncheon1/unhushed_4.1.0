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


    <div class="mx-auto" style="max-width: 780px;">
        <div class="loginCard m-5" style="max-width: 100%;">
            <div class="col">
                <div class="card-body pt-1 px-5">
                    <p class="diazo">The Play's the Thing!</p>
                    <p><h4><span style="font-weight: bold; color:#9acd57">Mab Lib 1</span></h4></p>
                    <p class="text-justify" style="font-size: 18px">
                        <span style="color:#01a7d3"><b>Person 1:</b> Do you want to play this new video game with me? It’s called “{{ session('adj1') }} {{ session('noun1') }} goes to {{ session('place') }}.”</span>
                        <br><b>Person 2:</b> That sounds {{ session('adj2') }}. Can I use the {{ session('adj3') }} controller?
                        <br><span style="color:#01a7d3"><b>Person 1:</b> Sure. Just make sure not to spill {{ session('noun2') }} on it this time.</span>
                        <br><b>Person 2:</b> I’ll {{ session('verb') }} my best!
                    </p>
                    <hr>
                    <p><h4><span style="font-weight: bold; color:#9acd57">Mab Lib 2</span></h4></p>
                    <p class="text-justify" style="font-size: 18px">
                        <span style="color:#01a7d3"><b>Person 1:</b> Wow, {{ session('name') }}, what are you eating? It smells really {{ session('adj4') }}.</span>
                        <br><b>Person 2:</b> It’s {{ session('color') }} {{ session('food') }} with {{ session('veggie') }}. I think it tastes kind of like {{ session('animal') }}. Do you want to try it?
                        <br><span style="color:#01a7d3"><b>Person 1:</b> Okay. Oh! This tastes really {{ session('adj5') }}. Who made it?</span>
                        <br><b>Person 2:</b> My {{ session('family') }}. They’re a very {{ session('adj6') }} cook!
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
