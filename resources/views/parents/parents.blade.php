@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url('/parents') }}"> Home</a> |
            <span style="font-weight: bold;color:#9acd57">Parent Coaching</span>
        </div>
    </div>

    <!-- PARENT COUACHING HEADER -->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Parent Coaching Sessions</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:680px">
                        Need a sounding board? Do you have a conversation you need support having with your youth? Set up a coaching session today.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- PARENT TABS-->
    <div class="row justify-content-center">
        <div class="col-auto">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-bs-toggle="tab" href="#karen">
                        <div class="lrgCard-T">
                        <img class="lrgImg" src="{{ asset('img/cards-sm/karen.png') }}" alt="Karen Rayne">
                        <p class="lrgText">Karen Rayne, PhD, CSE</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab panes -->
    <div class="d-flex justify-content-center">
        <div class="iBox1 col-9">
            <div class="tab-content">
                <div class="tab-pane fadein active" id="karen">
                    <div class="tBox1">
                        <p>Karen Rayne, PhD, CSE</p>
                        <p class="justify">Acclaimed author of <a href="/b-BTHF" target="_blank"><i>Breaking the Hush Factor</i></a> and educator Karen Rayne
                        is available to help you navigate those sometimes merky waters. Sessions can be scheduled for individuals, couples, or larger groups. These sessions can be done via phone, zoom, or in person in the Austin area.</p>
                        <p>Karen offers a per hour rate with sliding scale available.</span></p>
                        <p class="justify">Please email <a href="mailto:info@unhushed.org">info@unhushed.org</a> for more information and/or to schedule a session today.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</section>
@endsection
