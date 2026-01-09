@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url('/parents') }}"> Home</a> |
            <span style="font-weight: bold;color:#9acd57">K thru 5th</span>
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
                    <p class="diazo">K thru 5th</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">
                        This curriculum is currenlty being taught by schools across the North America. Contact your school's admin to request it today.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col-lg-6 col-sm-12 m-auto p-1">
                <p style="text-align:justify">As kids grow up they become aware of the world around them. This curriculum helps you teach them how to navigate those murky waters.
                This curriculum is in the final stages of development (and was delayed due to covid) with a release date and training being offered in June 2021.
                The cost of the training includes a 1 yr/1 user subscription.
                </p>
                <div class="row justify-content-center pb-5">
                    <iframe src="{{ asset('pdfs/scope/UNHUSHED_E_U-00_FR-ActivitiesList_v01.pdf#toolbar=0') }}" width="80%" height="600">
                        This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('pdfs/scope/UNHUSHED_E_U-00_FR-ActivitiesList_v01.pdf') }}">Download PDF</a>
                    </iframe>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ asset('/uploads/subscriptions/unhushed_es_u-00_s-00_fr-activities-list_v01.pdf') }}" target="_blank"><i class="fas fa-file-download"></i> Download this activities list.</a>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ url($path.'/store/elemetary-school-curriculum') }}"><i class="fas fa-shopping-cart"></i> Ready to purchase?</a>
            </div>
        </div>
    </div>
</section>
@endsection
