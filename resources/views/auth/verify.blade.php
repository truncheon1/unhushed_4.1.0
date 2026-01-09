@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/login') }}">Login </a> |
            <span style="font-weight: bold;color:#9acd57">Reset Link Sent</span>
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
                <p class="px-4">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }} click resend.
                </p>
                <form class="d-inline" method="POST" action="{{ url($path.'/verification.resend') }}">
                    @csrf
                    <input name="resend" id="resend" class="btn btn-primary" type="submit" value="Resend" data-toggle="button" aria-pressed="false" autocomplete="off">
                </form>
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
