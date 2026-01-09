@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'more') }}">More Stuff! </a> |
            <a href="{{ url($path.'arcade') }}">Arcade</a> |
            Blog |
            <a href="{{ url($path.'contact') }}">Contact Us</a> |
            <a href="{{ url($path.'give') }}">More Ways to Give</a> |
            <a href="{{ url($path.'social') }}">Social Media</a>
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
                    <p class="diazo" style="font-size=18px">Ollie's Blog</p>
                </div>
            </div>
        </div>

        <!-- BLOG POSTS-->
        @foreach ($posts as $post)
        <hr>
            <div class="row mt-2">
                <div class="col-6 text-right">
                    <a href="{{ url($path.'/blog/'.$post->slug) }}"><img style="max-width: 500px" src="{{ url('uploads/blog/'.$post->image) }}" alt="{{$post->title}}"/></a>
                </div>
                <div class="col-6">
                    <h2>
                        <a href="{{ url($path.'/blog/'.$post->slug) }}"><div class="title">{{ $post->title }}</div></a>
                    </h2>
                    <p style="color:#808080">
                        <i class="fad fa-calendar-alt fa-fw"></i> <span class="mr-2">{{ date('M, jS Y', strtotime($post->updated_at)) }}</span>
                        <i class="fas fa-user fa-fw"></i> {{ $post->user->name }}
                    </p>
                    <p class="my-4 mx-2 text-justify">
                        <a href="{{ url($path.'/blog/'.$post->slug) }}">
                        {{ $post->description }}
                        ...</a>
                    </p>
                    <p class="my-4 mx-2 text-justify" style="color:#808080">
                        <i class="fas fa-th-list fa-fw"></i> <span class="mr-2">{{ $post->tags }}</span>
                        <br>
                        <i class="fas fa-eye fa-fw"></i> <span class="mr-2">{{ $post->views ?? '0' }}</span>
                        <i class="fas fa-comments fa-fw"></i> <span class="mr-2">{{ $post->comments }}</span>
                        <i class="fas fa-thumbs-up fa-fw"></i> <span class="mr-2">{{ $post->likes }}</span>
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

