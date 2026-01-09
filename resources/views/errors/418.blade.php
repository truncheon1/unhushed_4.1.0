@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <div class="mx-auto" style="max-width: 500px;">
            <div class="loginCard" style="max-width: 100%;">
                <div class="col">
                    <div class="card-body">
                        <img src="{{ asset('img/octos/ollie-404.png') }}" alt="UN|HUSHED">
                        <p class="diazo">418</p>
                        <p class="sketchnote-square" style="line-height:30px;">This product is no longer available.</p>
                        <p class="login-card-description" style="line-height:30px;">Can we interest you in a different product? A <a href="{{ url('/educators/store') }}">teapot</a> perhaps?</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

