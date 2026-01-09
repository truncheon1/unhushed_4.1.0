@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/login') }}">Login </a> |
            <span style="font-weight: bold;color:#9acd57">Sign Up</span>
        </div>
    </div>

    <!-- REGISTER CARD -->
    <div class="container">
        <div class="loginCard p-3 mb-5">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                <p class="diazo">SIGN UP</p>
                <form method="POST" action="{{ url($path.'/register') }}">
                    @csrf
                    <div class="form-group px-5">
                        <label for="name" class="sr-only">Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                    </div>
                    <div class="form-group px-5">
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address">
                    </div>
                    <div class="form-group mb-4 px-5">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                    </div>
                    <div class="form-group mb-4 px-5">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                    </div>
                    <!--<div class="form-check mb-4 px-5">
                        <input class="form-check-input" name="newsletter" type="checkbox" value="1" id="flexCheckChecked" checked>
                        <label class="form-check-label" for="flexCheckChecked">
                        Opt into our monthly newsletter.
                        </label>
                    </div>-->
                    <input name="register" id="register" class="btn btn-secondary" type="submit" value="Sign Up" data-toggle="button" aria-pressed="false" autocomplete="off">
                </form>
                <p class="login-card-footer-text mt-3">Already have an account? <a href="{{ url($path.'/login') }}">Login here</a></p>
                <nav class="login-card-footer-nav">
                    <a href="{{ url($path.'/forgot') }}" class="forgot-password-link">Forgot password?</a> |
                    <a href="{{ url($path.'/terms') }}">Terms of use</a> |
                    <a href="{{ url($path.'/privacy') }}">Privacy policy</a>
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
