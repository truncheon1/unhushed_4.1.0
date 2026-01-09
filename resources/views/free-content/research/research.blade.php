@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
        <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <span style="font-weight: bold;color:#9acd57">Research</span>
        </div>
    </div>

    <!-- RESEARCH HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-science.png') }}" alt="Ollie the octopus holding a test tube and beaker"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Research Citations</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:800px">At UN|HUSHED we believe in Evidence Informed Curricula.
                        This is different from Evidence Based Curricula because we are not bound by a five to ten year lag time—and we still adhere to medically and statistically accurate information informed by proven pedagogy theory.</p>
                </div>
            </div>
        </div>

        <!-- RESEARCH CONTENT-->
        <div class="row pt-2 pb-5">
            <div class="d-inline-flex justify-content-center flex-wrap mx-auto">
                <div class="xlrgCard1">
                    Pedagogy
                    <a href="{{ url($path.'/pedagogy') }}"><img src="{{ asset('img/cards-xlg/r-ped.png') }}" alt="Pedagogy"></a>
                </div>
                <div class="xlrgCard2">
                    Statistics
                    <a href="{{ url($path.'/statistics') }}"><img src="{{ asset('img/cards-xlg/r-stats.png') }}" alt="Statistics"></a>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
