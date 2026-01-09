@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url('/parents') }}"> Home</a> |
            <span style="font-weight: bold;color:#9acd57">Birth to 4</span>
        </div>
    </div>

    <!-- PARENT HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo" style="max-width:500px">Birth to 4</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">Our pre-K curriculum is coming in the fall of 2025.</p>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
