@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Expired Link</span>
        </div>
    </div>

    <!-- LINK EXPIRED CARD-->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard p-3">
                    <div class="card-body">
                        <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                        <p class="diazo">Link Expired!</p>
                        <p class="login-card-description">Let's try another way. Click below to send another link.
                        <br/><b>Keep in mind it expires in 10 mins.</b></p>
                        <a href="{{ url('educators/forgot') }}">
                            <button name="try" id="try" class="btn btn-secondary">TRY AGAIN</button>
                        </a>
                            <p></p>
                            <nav class="login-card-footer-nav mt-3">
                                <a href="{{ url('educators/terms') }}">Terms of use</a> |
                                <a href="{{ url('educators/privacy') }}">Privacy policy</a>
                            </nav>
                        </div>
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

