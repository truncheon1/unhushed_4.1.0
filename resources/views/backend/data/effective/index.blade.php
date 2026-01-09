@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="row">
                <div class="backendcrumbs">
                    <a href="{{ url($section.'backend') }}"> Admin Backend</a> |
                    Effectiveness Data Parents
                </div>
            </div>

            <!-- TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-auto">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">EFFECTIVENESS DATA</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-md-center flex-wrap">
                                <div class="smCard">
                                    <a href="{{ url($section.'effective/parents') }}"><img class="smImg" src="{{ asset('img/cards-med/parent.png') }}" alt="Parents"></a>
                                    <div class="smText">Parents</div>
                                </div>
                                <div class="smCard">
                                    <a href="{{ url($section.'effective/participants') }}"><img class="smImg" src="{{ asset('img/cards-med/scroll.png') }}" alt="Past Participants"></a>
                                    <div class="smText">Past Participants</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-1">&nbsp;</div>
            </div>
        </div>
    </section>
@endsection
