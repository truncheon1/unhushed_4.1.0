@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}">About</a> |
            <span style="font-weight: bold;color:#9acd57">The Belly Project</span>
        </div>
    </div>

    <!-- BLOG -->
    <div class="container pb-5">
        <!-- BLOG HEADER -->
        <div class="d-flex">
            <div class="row">
                <div class="col-auto mx-auto">
                    <img src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie holding a laptop." style="max-width:100px">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo" style="font-size=18px">The Belly Project</p>
                </div>
            </div>
        </div>

        <!-- BLOG POSTS-->
        @foreach ($posts as $post)
        <hr>
            <div class="row mt-2">
                <div class="col-6 text-right">
                    @foreach ($images as $image)
                        @if($image->sort > 0)
                            <img src="{{url('uploads/thebellyproject/'.$image->image)}})">
                        @endif
                        @endforeach
                </div>
                <div class="col-6">
                    <h2>
                        <div class="title">{{$post['title']}}</div>
                    </h2>
                    <p style="color:#808080">
                        <i class="fad fa-calendar-alt fa-fw"></i> <span class="mr-2">{{$post['date']}}</span>
                    </p>
                    <p style="color:#808080">
                        I am a {{$post['age']}} year old {{$post['gender']}} from {{$post['country']}} with a total of
                        <br>pregnancies = {{$post['pregnancies']}} | miscarriages = {{$post['miscarriages']}}
                        <br>vaginal births = {{$post['vbirths']}} | c-section births = {{$post['cbirths']}} | abortions = {{$post['abortions']}}
                    </p>
                    <p class="my-4 mx-2 text-justify">
                        {!! $post['bio'] !!}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .title {
            color: #000;
        }
        .title:hover{
            color: #01a7d3;
        }
    </style>

</section>
@endsection

