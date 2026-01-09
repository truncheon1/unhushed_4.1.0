@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Set Your New Password</span>
        </div>
    </div>

    <!-- LOGIN CARD -->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard p-3">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                <p class="diazo">Set Your Password</p>
                <form method="POST" action="{{ url('/educators/password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group px-2">
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" readonly name="email" id="email" class="form-control" value="{{ $email }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group px-2">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group px-2">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                    </div>
                    <input name="set" id="set" class="btn btn-secondary" type="submit" value="SET">
                </form>
                <nav class="login-card-footer-nav mt-3">
                    <a href="{{ url('/educators/terms') }}">Terms of use</a> |
                    <a href="{{ url('/educators/privacy') }}">Privacy policy</a>
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
</section>
@endsection
