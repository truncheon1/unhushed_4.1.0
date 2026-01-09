@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url()->previous() }}">Back </a> |
            <span style="font-weight: bold;color:#9acd57">Login to complete your purchase</span>
        </div>
    </div>
    
    <!-- CART-LOGIN HEADER-->
    <div class="container">
        @include('store.cart.statusbar')
        <!-- LOGIN CODE-->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                @if(!auth()->user())
                <!-- LOGIN CARD-->
                <div class="col-md-12 col-lg-5 text-center">
                    <div class="card-body">
                        <p class="diazo" style="font-size: 26px">Returning Customer Login</p>
                        <form method="POST" action="{{ url($path.'/login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                            <input name="login" id="login" class="btn btn-secondary" type="submit" value="LOGIN" data-toggle="button" aria-pressed="false" autocomplete="off">
                        </form>
                        <p class="login-card-footer-text mt-3">Don't have an account? <a href="{{ url($path.'/register') }}">Register here</a></p>
                        <nav class="login-card-footer-nav">
                            <a href="{{ url($path.'/forgot') }}" class="forgot-password-link">Forgot password?</a>
                        </nav>
                    </div>
                </div>
                <!-- OR -->
                <div class="col-md-2 col-lg-2 vl" style="display:table; overflow:hidden;">
                    <div style="display:table-cell; vertical-align:middle;">
                        <div>
                            <p class="diazo text-center pt-5">OR</p>
                        </div>
                    </div>
                </div>
                <!-- REGISTER CARD-->
                <div class="col-md-12 col-lg-5 text-center">
                    <div class="card-body">
                        <p class="diazo" style="font-size: 26px">NEW CUSTOMER REGISTRATION</p>
                        <form method="POST" action="{{ url($path.'/register') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="sr-only">Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address">
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" name="newsletter" type="checkbox" value="1" id="flexCheckChecked" checked>
                                <label class="form-check-label" for="flexCheckChecked">
                                Opt into our monthly newsletter.
                                </label>
                            </div>
                            <input name="register" id="register" class="btn btn-secondary" type="submit" value="Register" data-toggle="button" aria-pressed="false" autocomplete="off">
                        </form>
                        <nav class="login-card-footer-nav mt-3">
                            <a href="{{ url($path.'/terms') }}">Terms of use</a> |
                            <a href="{{ url($path.'/privacy') }}">Privacy policy</a>
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style type="text/css">
        a.btn{
            color: #fff;
        }
        .rem{
            color: #f00;
            cursor: pointer;
        }
        .lds-ellipsis {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-ellipsis div {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #265a8e;
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }
        .vl {
            background: rgb(38,90,142) no-repeat center/2px 100%;
            background: linear-gradient(45deg, rgba(38,90,142,1) 0%, rgba(240,243,250,1) 40%, rgba(240,243,250,1) 60%, rgba(38,90,142,1)) no-repeat center/2px 100%;
        }
        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }
        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(24px, 0);
            }
        }
        .loading{
            display: none;
            margin: 0 auto;
        }
    </style>

    <script type="text/javascript">
        let showLoading = () => {
            $('.loading').css({'display': 'block'})
            $('.submit-btn').css({'display' : 'none'});
        }
        let hideLoading = () => {
            $('.loading').css({'display': 'none'})
            $('.submit-btn').css({'display' : 'inline-block'});
        }
        $(document).ready(function(){
            $("#continue").on('click', function(e){
                e.preventDefault();
                showLoading();
                _url = $("#address-form").attr('action');
                _data = $("#address-form").serialize();
                $.ajax({
                    url: _url,
                    type: 'post',
                    data: _data,
                    success: function(response){
                        console.log(response);
                        if(response.error === true){
                            string = '';
                            for(r in response.reason){
                                console.log(r);
                                for(rsn in response.reason[r]){
                                    console.log(response.reason[r][rsn]);
                                    string += response.reason[r][rsn] + '\n';
                                }
                            }
                            alert(string);
                            hideLoading();
                        }else{
                            document.location = _url;
                        }
                    },
                    fail: function(){ alert("Error"); hideLoading()}
                });
            })
        })
    </script>
</section>
@endsection
