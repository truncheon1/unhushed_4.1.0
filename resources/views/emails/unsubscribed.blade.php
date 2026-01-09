@extends('layouts.app')

@section('content')
<section>
    <!-- UNSUBSCRIBED CARD-->
    <div class="containerNoCrumb">
        <div class="loginCard">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop-sad.png') }}" alt="UN|HUSHED">
                <p class="diazo">unsubscribed</p>
                <p class="login-card-description">We're sorry to see you go.
                <br>Want to go play some games to feel better about it all?</p>
                <p class="sketchnote-square" style="line-height:30px;">To the <a href="{{ url($path.'/arcade') }}">ARCADE</a>!</p>
            </div>
        </div>
    </div>

</section>
@endsection
