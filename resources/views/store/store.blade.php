@extends('layouts.app')
@section('content')
@include('layouts.storebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerStore">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <span style="font-weight: bold;color:#9acd57">Store</span>
            </div>
        </div>
        <!-- STORE CARDS -->
        <div class="d-flex row justify-content-center">
            <!-- dynamic products -->
            @foreach($products as $product)
            <div class="storeCard">
                <a href="{{ url($path.'/store/'.$product->slug) }}">
                    @php($img = $product->primary_image_path)
                    @if($img && $img !== 'placeholder.png')
                        <img class="lrgImg" src="{{ asset('uploads/products/'.$img) }}" alt="{{$product->name}}">
                    @else
                        <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$product->name}}">
                    @endif
                    <p>{{$product->name}}
                    <br><span style="position: absolute; bottom: 0; right: 0;">{{ $product->display_price }}</span></p>
                </a>
            </div>
            @endforeach
            <!-- end products -->
        </div>
    </div>

    <style type="text/css">
        /*iPhone*/
        @media (max-width: 767px) {
            .desk {
                visibility: hidden;
                clear: both;
                display: none;
            }
        }
        /*desktop*/
        @media (min-width: 768px) {
            .phone {
                visibility: hidden;
                clear: both;
                display: none;
            }
        }
    </style>
</section>
@endsection
