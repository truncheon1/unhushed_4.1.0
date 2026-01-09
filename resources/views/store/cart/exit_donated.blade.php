@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <span style="font-weight: bold;color:#9acd57">Thank you for donating!</span>
        </div>
    </div>

    <!-- CARTS HEADER-->
    <div class="container pb-5 mb-5">
        <div class="d-flex justify-content-center">
            <div class="row">
            <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-success.png') }}" alt="Ollie smiling and holding a box.">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Thank you</p>
                    <hr>
                        <p class="sketchnote-square" style="line-height:30px;">Your donation went through successfully!</p>
                        <p class="login-card-description" style="line-height:30px;">Wanna <a href="{{ url('/educators/arcade') }}">go play video games</a>?</p>
                    </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row py-5 mx-auto pb-5 mb-5">
                <div class="col-auto">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
