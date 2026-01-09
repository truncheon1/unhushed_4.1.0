@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/login') }}">Login </a> |
            <span style="font-weight: bold;color:#9acd57">Verify Password</span>
        </div>
    </div>

    <!-- LOGIN CARD -->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard p-3">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                <p class="diazo">Howdy!</p>
                <p class="text-justify px-4">
                    You're seeing this page because it is your fist time back since creating your new account.
                    Check your inbox (and spam folder) for a verification email, and click the link inside it.
                </p>
                <form method="POST" action="{{ url($path.'/verification.send') }}">
                    @csrf
                    <input name="resend" id="resend" class="btn btn-primary" type="submit" value="Resend" data-toggle="button" aria-pressed="false" autocomplete="off">
                </form>
                <p></p>
                <nav class="login-card-footer-nav mt-3">
                    <a href="{{ url($path.'terms') }}">Terms of use</a> |
                    <a href="{{ url($path.'privacy') }}">Privacy policy</a>
                </nav>
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
