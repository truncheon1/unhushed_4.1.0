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
                <span style="font-weight: bold;color:#9acd57">Activities & Curriculum</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- dynamic products -->
            @foreach($activities as $activity)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$activity->slug) }}">
                    @if($activity->images->isNotEmpty())
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$activity->images->first()->image) }}" alt="{{$activity->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$activity->name}}">
                    @endif
                    <p>{{$activity->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $activity->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>
</section>
@endsection
