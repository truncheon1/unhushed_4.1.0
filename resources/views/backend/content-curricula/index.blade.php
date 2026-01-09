@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <span style="font-weight: bold;color:#9acd57">Curricula, Games, & Activites</span>
            </div>
        </div>

        <!-- CURRICULUM CARDS -->
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    GROUP BASED K-12 CURRICULA
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    @if($showFree && $freeProduct)
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/curricula/free-resources') }}"><img class="smImg" src="{{ asset('img/cards-med/free.png') }}" alt="Free Resources"></a>
                        <div class="smText">Free Resources</div>
                    </div>
                    @endif
                    <!-- paid curricula -->
                    @forelse($subscriptions as $package)
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/curricula/'.$package->slug) }}"><img class="smImg" src="{{ asset('uploads/products/'.$package->primary_image_path) }}" alt="{{ $package->name }}"></a>
                        <div class="smText">{{ $package->name }}</div>
                    </div>
                    @empty
                    <div class="smCard">
                    <div class="" style="margin-top:20px;"><b>Want More?</b></div>
                    <div class="smText" style="padding:10px;"><a href="{{ url($path.'/curricula?s[]=3') }}">Add curricula to your account today!</a></div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    UN|HUSHED AT HOME: FAMILY LED SEX ED
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    <!-- Always show unhushed at home bundle (includes free Getting Started + purchased courses) -->
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/curricula/unhushed-at-home-ages-12-15') }}"><img class="smImg" src="{{ asset('img/cards-med/start.png') }}" alt="unhushed at home: ages 12-15"></a>
                        <div class="smText">unhushed at home<br>ages 12-15</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Card -->
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    ACTIVITES & GAMES
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                        @foreach($activities as $activity)
                            <div class="smCard">
                                <a href="{{ url($path.'/dashboard/curricula/'.$activity->slug) }}"><img class="smImg" src="{{ asset('uploads/products/'.$activity->primary_image_path) }}" alt="{{ $activity->name }}"></a>
                                <div class="smText">{{ $activity->name }}</div>
                            </div>
                        @endforeach
                        <div class="smCard">
                            <div class="" style="margin-top:20px;"><b>Want More?</b></div>
                            <div class="smText" style="padding:10px;"><a href="{{ url($path.'/curricula?s[]=4') }}">Add stand alone activities and games to your account today!</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
