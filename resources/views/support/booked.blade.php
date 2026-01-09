@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Meeting Booked</span>
        </div>
    </div>

    <!-- BOOKED CARD-->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard">
                    <div class="card-body">
                        <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                        <p class="diazo" style="font-size:30px; text-align:center;">We're excited to meet with you!</p>
                        <p class="login-card-description">In the meantime, want to play some games to celebrate?</p>
                            <p class="sketchnote-square" style="line-height:30px;">To the <a href="{{ url($path.'/arcade') }}">ARCADE</a>!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        main{
            background:#f0f3fa;
        }
    </style>
</section>
@endsection
