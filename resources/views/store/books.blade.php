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
                <span style="font-weight: bold;color:#9acd57">Books</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- dynamic products -->
            @foreach($books as $book)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$book->slug) }}">
                    @if($book->images->isNotEmpty())
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$book->images->first()->image) }}" alt="{{$book->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$book->name}}">
                    @endif
                    <p>{{$book->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $book->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>
</section>
@endsection
