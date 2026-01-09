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
                <span style="font-weight: bold;color:#9acd57">Trainings</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- dynamic products -->
            @foreach($trainings as $training)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$training->slug) }}">
                    @if($training->images->isNotEmpty())
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$training->images->first()->image) }}" alt="{{$training->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$training->name}}">
                    @endif
                    <p>{{$training->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $training->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>
</section>
@endsection
