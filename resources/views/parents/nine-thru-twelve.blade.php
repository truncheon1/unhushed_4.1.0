@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url('/parents') }}"> Home</a> |
            <span style="font-weight: bold;color:#9acd57">9th thru 12th</span>
        </div>
    </div>

    <!-- PARENT HEADER 9-12 -->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">9th thru 12th</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">
                        This curriculum is currenlty being taught by schools across the USA. Contact your school's admin to request it today.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col-lg-6 col-sm-12 m-auto p-1">
                <p style="text-align:justify">
                    If you live in Austin, TX, you can <a href="{{ url($path.'/high-school-classes') }}">register your youth</a> for our next class taught by 
                    UN|HUSHED facilitators.
                </p>
                <p style="text-align:justify">This curriculum is designed to support teenagers ages 15 - 18 as they consider sex and sexuality as it influences and is influenced by themselves,
                    their peers, and the larger world.
                    To this end, every unit refers back to the <a href="https://www.itspronouncedmetrosexual.com/2019/01/introducing-the-social-justice-compass/" target="_blank">Social Justice Compass</a> as the underpinning for discussing sex and sexuality at every turn.
                </p>
                <div class="row justify-content-center pb-5">
                    <iframe src="{{ asset('/uploads/subscriptions/unhushed_hs_u-00_s-00_fr-activities-list-v01.pdf#toolbar=0') }}" width="80%" height="600">
                        This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('/uploads/subscriptions/unhushed_hs_u-00_s-00_fr-activities-list_v01.pdf') }}">Download PDF</a>
                    </iframe>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ asset('/uploads/subscriptions/unhushed_hs_u-00_s-00_fr-activities-list_v01.pdf') }}" target="_blank"><i class="fas fa-file-download"></i> Download this activities list.</a>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ url($path.'/store/high-school-curriculum') }}"><i class="fas fa-shopping-cart"></i> Ready to purchase?</a>
            </div>
            <div class="col-lg-8 col-sm-11 m-auto text-center p-1">
                <a href="{{ url($path.'/high-school-classes') }}"><i class="fas fa-users-class"></i>Register your youth</a>  for this in person class if you live in the Austin, TX area.
            </div>
        </div>
    </div>
</section>
@endsection
