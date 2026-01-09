@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <a href="{{ url($path.'/team') }}">Team </a> |
            <span style="font-weight: bold;color:#9acd57">{{$member->first}}</span>
        </div>
    </div>

    <div class="container pt-5 pb-5">
        <!-- TEAM MEMBER -->
        <div class="row">
            <div class="col-12"><!-- title -->
                <p class="diazo">{{$member->first}} {{$member->last}}</p>
                <p class="sketchnote-square">{{$member->title}}</p>
                <hr>
            </div>
            <div class="col-12"><!-- images -->
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <img class="img mr-3" src="{{ asset('uploads/team/'.$member->image) }}">
                            <div class="bio"><!-- description -->
                                <p class="bio">{!!$member->bio!!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .bio{
        font-size: 18px;
        text-align: justify;
    }
    .img{
        float: left;
        width: 200px;
        height: 200px;
        margin: 5px;
    }
</style>

@endsection
