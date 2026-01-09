@extends('layouts.app')
@section('content')
@include('layouts.storebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerStore">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/store') }}"> Store</a> |
                <span style="font-weight: bold;color:#9acd57">Games</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- dynamic products -->
            @foreach($games as $game)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$game->slug) }}">
                    @if($game->images->isNotEmpty())
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$game->images->first()->image) }}" alt="{{$game->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$game->name}}">
                    @endif
                    <p>{{$game->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $game->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>
</section>
@endsection
