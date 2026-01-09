@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <div class="mx-auto" style="max-width: 500px;">
            <div class="loginCard" style="max-width: 100%;">
                <div class="col">
                    <div class="card-body">
                        <img src="{{ asset('img/octos/ollie-403.png') }}" alt="UN|HUSHED">
                        <p class="diazo">403</p>
                        <p class="sketchnote-square" style="line-height:30px;">Access denied!</p>
                        <p class="login-card-description" style="line-height:30px;">Try <a href="{{ url($path.'/login') }}">logging in</a>?</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
