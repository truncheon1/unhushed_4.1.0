@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url('/youth') }}"> Home</a> |
            High School Sex Ed Drop In
        </div>
    </div>

    <div class="container">
        <!-- APPLY HEADER-->
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto mx-auto">
                    <img src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Sex Ed Drop In</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">
                        • Zoom sessions that are fun and informative.
                        <br>• No homework.
                        <br>• Valuable info you'll use for the rest of your life.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container2">
        <div class="row mx-auto">
            <div class="col-12">&nbsp;</div>
            <div class="col-4 text-right">
                <b>STEP 1:</b>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfNInjp9qbg3dHgL7WZuFuy9EQRD4clhJVW3VwNfKg8RgpKBA/viewform" target="_blank">
                    <button type="submit" class="btn btn-secondary btn-sm ml-2">
                        <i class="fas fa-file-alt"></i>
                    </button>
                </a>
            </div>
            <div class="col">
                14-17 years old? Fill out our intake form.
            </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-4 text-right">
                <b>STEP 2:</b>
                <!-- Modal trigger JD-->
                <button type="submit" class="btn btn-secondary btn-sm ml-2" data-bs-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#cal">
                    <i class="far fa-calendar-alt"></i>
                </button>
            </div>
            <div class="col">
                Check dates and topics and set your calendar reminders.
            </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-4 text-right">
                <b>STEP 3:</b>
                <button type="submit" class="btn btn-secondary btn-sm ml-2">
                    <i class="fas fa-video"></i>
                </button>
            </div>
            <div class="col">
                We'll email you a zoom link to join us.
            </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-4 text-right">
                <b>STEP 4:</b>
                <a href="{{ url($path.'/donate') }}">
                    <button type="submit" class="btn btn-secondary btn-sm ml-2">
                        <i class="fas fa-donate"></i>
                    </button>
                </a>
            </div>
            <div class="col">
                Send this <a href="{{ url($path.'/donate') }}">donation link</a> to someone who can to help us continue to offer this free program!
            </div>
            <div class="col-12">&nbsp;</div>
        </div>
    </div>

    <div class="alert alert-primary alert-dismissible">
        <a href="#" class="close" data-bs-dismiss="alert" aria-label="close">&times;</a>
        <a href="{{ url($path.'/donate') }}"><i class="fa-light fa-hand-holding-heart"></i></a>
        This program is on hold while we look for funding.
        Send this <a href="{{ url($path.'/donate') }}">donation link</a> to someone who can help us continue to offer this free program! 
        <a href="{{ url($path.'/donate') }}"><i class="fa-regular fa-hand-holding-seedling"></i></a>
    </div>
    
    <!-- Modal popup-->
    <div class="modal fade" id="cal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('youth/modal-dropin')
            </div>
        </div>
    </div>

</section>
@endsection
