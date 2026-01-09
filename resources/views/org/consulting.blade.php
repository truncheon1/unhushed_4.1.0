@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/services') }}"> Services Offered</a> |
            Consulting |
            <a href="{{ url($path.'/subscription-info') }}">Curricula</a> |
            <a href="{{ url($path.'/trainings') }}">Training</a>
        </div>
    </div>

    <!-- ORG VALUES HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto mx-auto">
                    <img src="{{ asset('img/octos/ollie-phone.png') }}" alt="Ollie the octopus talking on a landline phone playing with the cord.">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Consulting</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">Need help setting up a comprehensive human sexuality program? We've got you covered, from coaching administration to custom curricula review.</p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center pt-5">
            <p class="justify">Please email <a href="mailto:info@unhushed.org">info@unhushed.org</a> for more information and/or to schedule a consultation today.</p>
        </div>
    </div>

</section>
@endsection
