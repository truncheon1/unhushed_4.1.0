@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-ms.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">
            <div class="row mx-auto">
                <div class="col-12 mx-5">
                    <div class="nav-box">
                        <h3>The Middle School Curriculum</h3>
                        <a href="{{ url($path.'/middle-school-curriculum-training/1') }}" class="px-1"><i class="fas fa-play-circle fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/2') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/3') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/4') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/5') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-12 mx-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col text-center">
                                    <img src="{{ asset('img/trainings/curricula/ms-Ollie.png') }}" class="responsive" style="max-width:600px;" alt="Ollie the octopus sitting on a green line from the curriculum cover.">
                                    <p style="font-size: 18px">
                                        <br>
                                        Let's get started!
                                        <a href="{{ url($path.'/middle-school-curriculum-training/1') }}" class="px-1"><i class="fas fa-play-circle fa-2x fa-fw align-middle"></i></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection





