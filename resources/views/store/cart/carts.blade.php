@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <span style="font-weight: bold;color:#9acd57">Your carts</span>
        </div>
    </div>

    <!-- CARTS -->
    <div class="row d-flex justify-content-center m-5 ">
        <!-- LOGIN CARD-->
        <div class="col-md-12 col-lg-3 d-flex justify-content-center">
            <div class="card-body">
                <div class="lrgCard">
                    <a href="{{ url($path.'/cart_subscriptions') }}">
                        @php 
                            $c = new \App\Http\Controllers\Store\CartSubscriptController(); $count = $c->subscription_count() 
                        @endphp
                        @if($count > 0)
                        <span class="box-count"> {{$count}} </span>
                        @endif
                        <img class="lrgImg" src="{{ url('img/cards-lrg/box.png') }}" alt="Subscriptions" />
                        <p class="lrgText">
                            Subscription Cart
                            <br>*curricula
                        </p>
                    </a>
                </div>
            </div>
        </div>
        <!-- OR -->
        <div class="col-md-2 col-lg-2 vl" style="display:table; overflow:hidden;">
            <div style="display:table-cell; vertical-align:middle;">
                <div>
                    <p class="diazo text-center pt-5">OR</p>
                </div>
            </div>
        </div>
        <!-- REGISTER CARD-->
        <div class="col-md-12 col-lg-3 d-flex justify-content-center">
            <div class="card-body">
                <div class="lrgCard">
                    <a href="{{ url($path.'/cart_products') }}">
                        @php 
                            $c = new \App\Http\Controllers\Store\CartProductController(); $count = $c->cart_count() 
                        @endphp
                        @if($count > 0)
                        <span class="box-count"> {{$count}} </span>
                        @endif
                        <img class="lrgImg" src="{{ url('img/cards-lrg/box.png') }}" alt="Products" />
                        <p class="lrgText">
                            Product Cart
                            <br>*one time purchase
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        .box-count{
            border-radius: 50%;
            font-size: 18px;
            display: inline-block;
            position: relative;
            left: 55px;
            top: -15px;
            color: #fff;
            width: 20px;
            height: 20px;
            text-align: center;
            font-weight: bold;
            z-index: 100px;
        }
    </style>
</section>
@endsection
