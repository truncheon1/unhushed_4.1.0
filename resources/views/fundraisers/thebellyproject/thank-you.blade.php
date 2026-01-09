@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}">About </a> |
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/thebellyproject') }}"><span style="font-weight: bold;color:#9acd57">Back</span></a>
        </div>
    </div>

    <!-- THE BELLY PROJECT -->
    <div class="container pb-5">
        <div class="loginCard">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                <p class="diazo">The Belly Project</p>
                <p class="login-card-footer-text mt-3">Thank you for your submission.</p>
                <p>Once we've raised the funds to continue updating this
                <br/>amazing project your entry will be a valued addition!
                <br/>In the meantime you can see the original <a href="https://thebellyproject.wordpress.com" target="_blank">project here</a>.
                <br/>Please consider donating to help us reach our goal.</p>
                <!-- Donate -->
                <div class="row">
                    <div class="mx-auto p-1">
                        <a href="https://www.paypal.com/donate/?campaign_id=7KMPGCBPFRKLE" class="btn btn-secondary add-cart" style="color:#fff" target="_blank">DONATE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

