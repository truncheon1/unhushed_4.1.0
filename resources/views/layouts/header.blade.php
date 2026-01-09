<?php
    $path = $path ?? '';
?>

<div class="nav topbar">
    <!-- row 1 -->
    <ul class="nav m-auto" style="font-weight:bold">
        <li class="top-tab
            @if((
                (!empty($path) && $path != 'organizations')
                &&(!empty($path) && $path != 'professionals')
                &&(!empty($path) && $path != 'parents')
                &&(!empty($path) && $path != 'youth')
                )|(empty($path))) top-tab-a @endif">
            <a href="{{ url('') }}">
                EDUCATORS
            </a>
        </li>
        <!--
        <li class="top-tab @if(!empty($path) && $path == 'organizations') top-tab-a @endif">
            <a href="{{ url('organizations') }}">
                ORGANIZATIONS
            </a>
        </li>
            -->
        <li class="top-tab @if(!empty($path) && $path == 'professionals') top-tab-a @endif">
            <a href="{{ url('professionals') }}">
                PROFESSIONALS
            </a>
        </li>
        <li class="top-tab @if(!empty($path) && $path == 'parents') top-tab-a @endif">
            <a href="{{ url('parents') }}">
                PARENTS
            </a>
        </li>
        <!--
        <li class="top-tab @if(!empty($path) && $path == 'youth') top-tab-a @endif">
            <a href="{{ url('youth') }}">
                YOUTH
            </a>
        </li>
            -->
    </ul>
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <!-- LOGO -->
    <a href="/" class="navbar-brand logo pl-3"><img src="{{ asset('img/logo.png') }}" alt="UN|HUSHED"/></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse w-100 flex-md-column pr-3" id="navbarCollapse">
        <!-- row 2 -->
        <ul class="navbar-nav ml-auto pb-2 pb-md-0">
            @guest
                <li class="nav-item mr-2">
                    <a class="nav-link" href="{{ url($path.'/login') }}">{{ __('LOGIN') }}</a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item mr-2">
                    <a class="nav-link" href="{{ url($path.'/register') }}">{{ __('SIGN UP') }}</a>
                </li>
                @endif
            @else
                <li class="nav-item mr-2">
                    @php
                        $dashboardUrl = (auth()->user()->can('access-master') || auth()->user()->hasRole('admin', 'team')) 
                            ? url($path.'/backend') 
                            : url($path.'/dashboard');
                    @endphp
                    <a class="nav-link user-nav-link" style="text-transform:uppercase;" href="{{ $dashboardUrl }}"><i class="fa-solid fa-user fa-fw"></i> {{ Auth::user()->name }}</a>
                </li>
            @endguest
            <!-- CART -->
            @include('layouts.cart')
        </ul>

        <!-- row 3 educators-->
        @if(empty($path))
            @include('layouts.headers.educators')
        @elseif(!empty($path) && $path == 'organizations')
            @include('layouts.headers.organizations')
        @elseif(!empty($path) && $path == 'professionals')
            @include('layouts.headers.professionals')
        @elseif(!empty($path) && $path == 'parents')
            @include('layouts.headers.parents')
        @elseif(!empty($path) && $path == 'youth')
            @include('layouts.headers.youth')
        @elseif (!empty($path))
            @include('layouts.headers.educators')
        @endif
    </div>
</nav>

<style>
    .user-nav-link:hover,
    .user-nav-link:hover i {
        color: #9acd57 !important;
    }
</style>

<!-- DONATION -->
@include('layouts.donate')
