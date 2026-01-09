@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <span style="font-weight: bold;color:#9acd57">Free Content</span>
        </div>
    </div>

    <!-- FREE HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Who doesn't love free?</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:620px">We’re all about helping our fellow humans, medically accurate information, and providing everything we do on a sliding scale down to zero! Check out some of our FREE quality resources below.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <!-- freebie -->
            <div class="lrgCard">
                <a href="{{ url($path.'/free-activities') }}">
                    <img class="lrgImg" src="{{ asset('img/products/c-corona.png') }}" alt="Activities">
                    <p class="lrgText">Activities</p>
                </a>
            </div>
            <!-- freebie -->
            <div class="lrgCard">
                <a href="{{ url($path.'/sex-ed-dictionary') }}">
                    <img class="lrgImg" src="{{ asset('img/cards-med/dictionary.png') }}" alt="Dictionaries">
                    <p class="lrgText">Dictionaries</p>
                </a>
            </div>
            <!-- freebie -->
            <div class="lrgCard">
                <a href="{{ url($path.'/research') }}">
                    <img class="lrgImg" src="{{ asset('img/cards-med/science.png') }}" alt="Research">
                    <p class="lrgText">Research</p>
                </a>
            </div>
            <!-- freebie -->
            <div class="lrgCard">
                <a href="{{ url($path.'/register') }}">
                    <img class="lrgImg" src="{{ asset('img/products/c-account.png') }}" alt="Want More?">
                    <p class="lrgText">Want More?
                    <br>sign up for a free account</p>
                </a>
            </div>
        </div>
    </div>

</section>
@endsection
