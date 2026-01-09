@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <span style="font-weight: bold;color:#9acd57">Social Media</span>
        </div>
    </div>

    <!-- SOCIAL HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-social.png') }}" alt="Ollie juggling social media icons.">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Let's get social!</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:600px">We love keeping in touch, learning new things, spreading medically accurate info. Click below to join us!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SOCIAL MEDIA -->
    <div class="d-flex justify-content-center py-3 mb-5">
        <div class="row justify-content-center">
            <!-- media newsletter -->
            <div class="socialCard">
                <a href="{{ url($path.'/news') }}"><img src="{{ asset('img/social/news.png') }}" width="200" height="200"
                onMouseOver="this.src='{{ asset("img/social/newsH.png") }}';" onMouseOut="this.src='{{ asset("img/social/news.png") }}';" alt="Newsletter"></a>
            </div>
            <!-- media facebook -->
            <div class="socialCard">
                <a href="{{ url('https://www.facebook.com/unhushed') }}" target="_blank"><img src="{{ asset('img/social/fb.png') }}" width="200" height="200"
                onMouseOver="this.src='{{ asset("img/social/fbH.png") }}';" onMouseOut="this.src='{{ asset("img/social/fb.png") }}';" alt="Facebook"></a>
            </div>
            <!-- media instagram -->
            <div class="socialCard">
                <a href="https://www.instagram.com/accounts/login/?next=%2F_unhushed_%2F&source=follow"><img src="{{ asset('img/social/insta.png') }}" width="200" height="200"
                onMouseOver="this.src='{{ asset("img/social/instaH.png") }}';" onMouseOut="this.src='{{ asset("img/social/insta.png") }}';" alt="Instagram"></a>
            </div>
            <!-- media twitter -->
            <div class="socialCard">
                <a href="https://twitter.com/_unhushed?ref_src=twsrc%5Etfw"><img src="{{ asset('img/social/twitter2.png') }}" width="200" height="200"
                onMouseOver="this.src='{{ asset("img/social/twitter2H.png") }}';" onMouseOut="this.src='{{ asset("img/social/twitter2.png") }}';" alt="Twitter"></a>
            </div>
        </div>
    </div>

</section>

<style>
    .socialCard {
        width: 200px;
        height: 200px;
        border-radius: 10px;
        margin: 10px;
        overflow: hidden;
        -moz-box-shadow:    4px 5px 6px 4px #ccc;
        -webkit-box-shadow: 4px 5px 6px 4px #ccc;
        box-shadow:         4px 5px 6px 4px #ccc;
        padding: 0;
    }
</style>
@endsection
