@extends('layouts.app')
@section('content')
@include('backend.content-trainings.eesl.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Enseñando Educación Sexual en Línea</span>
            </div>
        </div>

        <div class="row mx-auto">
            <div class="col-12 mx-5">
                <div class="nav-box">
                    <h3>Enseñando Educación Sexual en Línea</h3>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/1') }}"  class="px-1"><i class="fa-solid fa-circle-1 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/2') }}"  class="px-1"><i class="fa-solid fa-circle-2 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/3') }}"  class="px-1"><i class="fa-solid fa-circle-3 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/4') }}"  class="px-1"><i class="fa-solid fa-circle-4 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/5') }}"  class="px-1"><i class="fa-solid fa-circle-5 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/6') }}"  class="px-1"><i class="fa-solid fa-circle-6 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/7') }}"  class="px-1"><i class="fa-solid fa-circle-7 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/8') }}"  class="px-1"><i class="fa-solid fa-circle-8 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/9') }}"  class="px-1"><i class="fa-solid fa-circle-9 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/10') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>

        <!-- Training Card -->  
        <div class="row mx-auto">
            <div class="col-12 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p style="font-size: 18px">Bienvenido a tu curso</p>
                                <img src="{{ asset('img/trainings/tseo/Ollies.png') }}" class="responsive" style="max-width:600px;" alt="Dos pulpos entrenando en línea.">
                                <p style="font-size: 18px">
                                    <br>
                                    ¡Empecemos!
                                    <a href="{{ url($path.'/ensenando-educacion-sexual-en-linea/1') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw align-middle"></i></a>
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





