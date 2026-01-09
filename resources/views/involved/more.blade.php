@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <span style="font-weight: bold;color:#9acd57">More</span>
        </div>
    </div>

    <!-- MORE HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-Qbox.png') }}" alt="Ollie the octopus holding a question box."></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Want more?</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:660px">We've got you covered! Fun articles on our blog? You bet!
                    <br>Online games to play for fun and with your participants? Heck yes!
                    <br>We're also fun and charming on social media, so go show us some love.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <!-- more -->
            <div class="lrgCard">
                <a href="{{ url($path.'/blog') }}">
                    <img class="lrgImg" src="{{ asset('img/cards-med/blog.png') }}" alt="Blog">
                    <p class="lrgText">Read our Blog!</p>
                </a>
            </div>
            <!-- more -->
            <div class="lrgCard">
                <a href="{{ url($path.'/contact') }}">
                    <img class="lrgImg pt-4 pb-3" src="{{ asset('img/cards-sm/w_feedback.png') }}" alt="Contact">
                    <p class="lrgText">Contact us via email</p>
                </a>
            </div>
            <!-- more -->
            <div class="lrgCard">
                <a href="{{ url($path.'/give') }}">
                    <img class="lrgImg p-3" src="{{ asset('img/cards-med/orientation.png') }}" alt="Give">
                    <p class="lrgText">More ways to give</p>
                </a>
            </div>
            <!-- more -->
            <div class="lrgCard">
                <a href="{{ url($path.'/news') }}">
                    <img class="lrgImg p-3" src="{{ asset('img/cards-med/blog.png') }}" alt="Newsletter">
                    <p class="lrgText">Sign up for our newsletter</p>
                </a>
            </div>
            <!-- discord 
            <div class="lrgCard">
                <iframe src="https://discordapp.com/widget?id=1041864953327525918&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
            </div>-->
        </div>
    </div>

</section>
@endsection
