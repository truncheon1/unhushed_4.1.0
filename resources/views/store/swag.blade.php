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
                <span style="font-weight: bold;color:#9acd57">Swag</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- hardcoded products -->
            <div class="storeCard">
                <a href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q" target="_blank">
                    <img class="lrgImg" src="{{ asset('img/octos/ollie-donate.png') }}" alt="Donate Today">
                    <p>Donate Today!</p>
                </a>
            </div>
            <!-- dynamic products -->
            @foreach($swags as $swag)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$swag->slug) }}">
                    @if($swag->images->isNotEmpty())
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$swag->images->first()->image) }}" alt="{{$swag->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$swag->name}}">
                    @endif
                    <p>{{$swag->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $swag->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>
</section>
@endsection
