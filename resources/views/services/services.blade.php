@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <span style="font-weight: bold;color:#9acd57">Services Offered</span>
        </div>
    </div>

    <!-- ORG VALUES HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto mx-auto">
                    <img src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Services</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:800px">We are curricula writers, book authors, trainers, educators, designers, and so much more!
                        Click below to learn more about our curricula, sign up as a parent or youth for our online classes, register for a virtual training, or check out some of our amazing free resources.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <!-- 
            <div class="lrgCard">
                <a href="{{ url($path.'/classes') }}">
                    <img class="lrgImg" src="{{ asset('img/cards-lrg/laptop.png') }}" alt="Classes">
                    <p class="lrgText">Classes</p>
                </a>
            </div>product -->
            <!-- product -->
            @if ($path == 'organizations')
            <div class="lrgCard">
                <a href="{{ url($path.'/consulting') }}">
                    <img class="lrgImg" src="{{ asset('img/cards-lrg/phone.png') }}" alt="Consulting">
                    <p class="lrgText">Consulting</p>
                </a>
            </div>
            @endif
            <!-- product -->
            <div class="lrgCard">
                <a href="{{ url($path.'/subscription-info') }}">
                    <img class="lrgImg" src="{{ asset('img/products/c-hs.png') }}" alt="Curricula">
                    <p class="lrgText">Curricula</p>
                </a>
            </div>
            <!-- product -->
            <div class="lrgCard">
                <a href="{{ url($path.'/trainings') }}">
                    <img class="lrgImg" src="{{ asset('img/products/wt-tseo.png') }}" alt="Training">
                    <p class="lrgText">Training</p>
                </a>
            </div>
        </div>
    </div>

</section>
@endsection
