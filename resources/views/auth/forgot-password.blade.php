@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/login') }}">Login </a> |
            <span style="font-weight: bold;color:#9acd57">Reset Password</span>
        </div>
    </div>

    <!-- FORGOT PASSWORD CARD-->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard p-3">
                    <div class="card-body">
                        <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                        <p class="diazo">Reset Password</p>
                            <form method="POST" action="{{ url($path.'/password.request') }}">
                                @csrf
                                <div class="form-group px-5">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                                </div>
                                <input name="reset" id="reset" class="btn btn-secondary" type="submit" value="RESET" data-toggle="button" aria-pressed="false" autocomplete="off">
                            </form>
                            <p class="login-card-footer-text mt-3">Don't have an account? <a href="{{ url($path.'/register') }}">Register here</a></p>
                            <nav class="login-card-footer-nav">
                                <a href="{{ url($path.'/forgot') }}" class="forgot-password-link">Forgot password?</a> |
                                <a href="{{ url($path.'/terms') }}">Terms of use</a> |
                                <a href="{{ url($path.'/privacy') }}">Privacy policy</a>
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
