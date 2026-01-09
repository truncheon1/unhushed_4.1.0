@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <div class="mx-auto" style="max-width: 500px;">
            <div class="loginCard" style="max-width: 100%;">
                <div class="col">
                    <div class="card-body">
                        <img src="{{ asset('img/octos/ollie-404.png') }}" alt="UN|HUSHED">
                        <p class="diazo">404</p>
                        <p class="sketchnote-square" style="line-height:30px;">Whoops, page not found!</p>
                        <p class="login-card-description" style="line-height:30px;">Wanna just <a href="{{ url('/educators/arcade') }}">go play video games</a>?</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

