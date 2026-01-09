@extends('layouts.app')
@section('content')
@include('backend.content-trainings.se&l.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Sex Ed & the Law</span>
            </div>
        </div>

        <div class="row mx-auto">
            <div class="col-12 mx-5">
                <div class="nav-box">
                    <h3>Sex Ed & the Law</h3>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/1') }}"  class="px-1"><i class="fa-solid fa-clock-one fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/2') }}"  class="px-1"><i class="fa-solid fa-clock-two fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/3') }}"  class="px-1"><i class="fa-solid fa-clock-three fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/4') }}"  class="px-1"><i class="fa-solid fa-clock-four fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/5') }}"  class="px-1"><i class="fa-solid fa-clock-five fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/6') }}"  class="px-1"><i class="fa-solid fa-clock-six fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/7') }}"  class="px-1"><i class="fa-solid fa-clock-seven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/8') }}"  class="px-1"><i class="fa-solid fa-clock-eight fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/9') }}"  class="px-1"><i class="fa-solid fa-clock-nine fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/10') }}" class="px-1"><i class="fa-solid fa-clock-ten fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/11') }}" class="px-1"><i class="fa-solid fa-clock-eleven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/12') }}" class="px-1"><i class="fa-solid fa-clock-twelve fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>

        <!-- Training Card -->  
        <div class="row mx-auto">
            <div class="col-12 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-center">
                                <img src="{{ asset('img/trainings/se&l/OllieCover-SE&L.png') }}" class="responsive" style="max-width:600px;" alt="Two octopuses doing online training.">
                                <p style="font-size: 18px">
                                    <br>
                                    Let's get started!
                                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/1') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw align-middle"></i></a>
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





