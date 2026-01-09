@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            Your carts
        </div>
    </div>

    <!-- CARTS HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
            <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-cancel.png') }}" alt="Ollie crying and holding a box.">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Order Canceled</p>
                    <hr>
                    <p class="sketchnote-square text-justify" style="max-width:400px">Your order didn't go through!</p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row py-5 mx-auto">
                <div class="col-auto">
                    <p>Your order didn't go through. Try going <a href="{{ url($path.'/carts') }}">back</a>?</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


