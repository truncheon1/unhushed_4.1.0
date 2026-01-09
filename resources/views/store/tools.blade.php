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
                <span style="font-weight: bold;color:#9acd57">Teaching Tools</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- dynamic products -->
            @foreach($tools as $tool)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$tool->slug) }}">
                    @if($tool->images->isNotEmpty())
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$tool->images->first()->image) }}" alt="{{$tool->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$tool->name}}">
                    @endif
                    <p>{{$tool->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $tool->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>
</section>
@endsection
