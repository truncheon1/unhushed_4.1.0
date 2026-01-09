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

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-10 mx-5">
                <div class="nav-box">
                    <h3>Enseñando Educación Sexual en Línea</h3>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/1') }}"  class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/2') }}"  class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/3') }}"  class="px-1"><i class="fa-duotone fa-circle-3 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/4') }}"  class="px-1"><i class="fa-duotone fa-circle-4 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/5') }}"  class="px-1"><i class="fa-duotone fa-circle-5 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/6') }}"  class="px-1"><i class="fa-duotone fa-circle-6 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/7') }}"  class="px-1"><i class="fa-duotone fa-circle-7 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/8') }}"  class="px-1"><i class="fa-duotone fa-circle-8 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/9') }}"  class="px-1"><i class="fa-duotone fa-circle-9 fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-duotone fa-stop-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
            <div class="col-10 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb1v2.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Sesión 10: Hay que recordar que este cambio es incómodo para todos.</span>
                                <br><span style="font-weight:light;font-size:12px">Grabado en jueves 20 a las 1e/12c/10p.</span>
                                <br><b>Hay que recordar que este cambio es incómodo para todos.</b> La confusión de las últimas semanas de trabajar desde casa y dar clases en línea
                                nos ha enseñado algo, que es común vivir momentos incómodos en el espacio de la educación digital. Puede ocurrir que uno de tus alumnos participe en
                                las videoconferencias desde su cama y le podemos pedir que se cambie a un lugar diferente, pero no todos tendrán un lugar diferente donde estar, y como
                                facilitadores, tenemos que estar dispuestos a aceptar eso y continuar la clase.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-10">&nbsp;</div>
            <div class="col-10 text-center">
                <p>Recuerde que puede ver los archivos PDF en su navegador o descargarlos.
                <br><span style="color:#9acd57; font-weight:bold">PC:</span> Haga clic derecho en elles para ver en su navegador.</span>
                <br/><span style="color:#9acd57; font-weight:bold">Mac:</span> Controle haga clic en elles para verlos en su navegador.</span></p>
            </div>
            <div class="col-12">&nbsp;</div>
        </div>
    </div>

    <!-- Modal video 1 -->
    <div id="video1" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    <iframe src="https://player.vimeo.com/video/461882266" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#video1").on('hidden.bs.modal', function (e) {
            $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
        });
        $("#video2").on('hidden.bs.modal', function (e) {
            $("#video2 iframe").attr("src", $("#video2 iframe").attr("src"));
        });
    </script>
</section>
@endsection
