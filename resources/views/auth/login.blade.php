@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Login</span>
        </div>
    </div>

    <!-- LOGIN CARD -->
    <div class="container">
        <div class="row">
            <div class="col-12 ml-auto py-1">
                <div class="loginCard p-3">
                        <div class="card-body">
                            <img src="{{ asset('img/octos/ollie-laptop.png') }}" alt="UN|HUSHED">
                            <p class="diazo">Login!</p>
                            <form method="POST" action="{{ url(get_path($path).'/login') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="col-12 mb-1 px-5">
                                        <label for="email" class="sr-only">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                                    </div>
                                    <div class="col-12 mb-3 px-5">
                                        <label for="password" class="sr-only">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                        <span toggle="#password-field" class="pr-4 fa fa-fw fa-eye field_icon toggle-password"></span>
                                    </div>
                                </div>
                                <input name="login" id="login" class="btn btn-secondary" type="submit" value="LOGIN" data-toggle="button" aria-pressed="false" autocomplete="off">
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
        .field_icon {
            float: right;
            margin-left: 10px;
            margin-top: -25px;
            position: relative;
            max-width: 20px;
            max-height: 20px;
        }
        main{
            background:#f0f3fa;
        }
    </style>
    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#password");
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        });
    </script>
</section>
@endsection
