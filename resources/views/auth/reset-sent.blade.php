@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/login') }}">Login </a> |
            <span style="font-weight: bold;color:#9acd57">Reset Password Link Sent</span>
        </div>
    </div>

    <!-- LOGIN CARD -->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard p-3">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                <p class="diazo">Sent!</p>
                <p class="login-card-description">Please check your email (and spam folder) for the reset link.
                <br/>Keep in mind it expires in 10 mins.</p>
                <div class="col-12 p-3" style="text-align:center;">
                    <p class="login-card-description">Has it been more than 2 minutes and you didn't get the email?</p>
                    <button type="button" class="btn btn-secondary px-3 mp-3" onclick="refreshPage()">RESEND</button>
                </div>
                    <p></p>
                    <nav class="login-card-footer-nav mt-3">
                        <a href="{{ url($path.'/terms') }}">Terms of use</a> |
                        <a href="{{ url($path.'/privacy') }}">Privacy policy</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <style>
        main{
            background:#f0f3fa;
        }
    </style>
    <script>
        function refreshPage() {
            window.location.reload();
        }
    </script>
</section>
@endsection

