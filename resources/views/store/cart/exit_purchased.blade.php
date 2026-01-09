@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <span style="font-weight: bold;color:#9acd57">Thank you for your order!</span>
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
                    <p class="sketchnote-square text-justify" style="max-width:400px">Your order went through successfully!</p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row py-5 mx-auto pb-5 mb-5">
                <div class="col-auto">
                    <p>You can visit your <a href="{{ url($path.'/dashboard/billing') }}"> ORDERS/BILLING </a> page for tracking info and order details.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
