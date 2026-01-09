@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url('/parents') }}"> Home</a> |
            <span style="font-weight: bold;color:#9acd57">6th thru 8th</span>
        </div>
    </div>

    <!-- PARENT HEADER 6-8 -->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">6th thru 8th</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">
                        This curriculum is currenlty being taught by schools across the globe. Contact your school's admin to request it today.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col-lg-6 col-sm-12 m-auto p-1">
                <p style="text-align:justify">
                    If you live in Austin, TX, you can <a href="{{ url($path.'/middle-school-classes') }}">get more information</a> about our next class taught by 
                    UN|HUSHED facilitators.
                </p>
                <p style="text-align:justify">This curriculum is designed to support teenagers ages 12 - 14 as they are become increasingly aware of the world 
                    of sexuality. Young adolescents are grappling with understanding who they are, what sexuality feels like, and why they and their peers may make 
                    different decisions about sexual activities. This curriculum is designed to meet them in this place and offer information, context, and structure.
                </p>
            </div>
            <div class="col-lg-7 col-sm-12 m-auto text-center">
                <iframe src="{{ asset('/uploads/subscriptions/unhushed_middle-school-activities-list_v03.pdf#toolbar=0') }}" width="80%" height="300">
                    This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('/uploads/subscriptions/unhushed_middle-school-activities-list_v03.pdf') }}">Download PDF</a>
                </iframe>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ asset('/uploads/subscriptions/unhushed_middle-school-activities-list_v03.pdf') }}" target="_blank"><i class="fas fa-file-download"></i> Download this activities list.</a>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ url($path.'/store/middle-school-curriculum') }}"><i class="fas fa-shopping-cart"></i> Ready to purchase?</a>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ url($path.'/middle-school-classes') }}"><i class="fas fa-users-class"></i> Get more information</a> about this in person class if you live in the Austin, TX area.
            </div>
        </div>
    </div>
</section>
@endsection
