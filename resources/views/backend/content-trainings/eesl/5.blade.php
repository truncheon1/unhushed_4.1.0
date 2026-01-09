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
                    <a class="px-1"><i class="fa-duotone fa-circle-5 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/6') }}"  class="px-1"><i class="fa-solid fa-circle-6 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/7') }}"  class="px-1"><i class="fa-solid fa-circle-7 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/8') }}"  class="px-1"><i class="fa-solid fa-circle-8 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/9') }}"  class="px-1"><i class="fa-solid fa-circle-9 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/10') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
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
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Sesión 5: Sincrónico y asincrónico, es la pregunta</span>
                                <br><span style="font-weight:light;font-size:12px">Grabado en martes 4 de agosto a las 1e/12c/10p.</span>
                                <br><b>Los dos ambientes de clase en línea, sincrónicos (en vivo, por vídeo o voz) y asincrónicos (grabaciones por texto, vídeo y/o voz), tienen
                                    sus ventajas y sus desventajas.</b> Lo ideal sería que pudiéramos usar ambos métodos de manera integrada, escogiendo el método más efectivo
                                    dependiendo de cada contenido específico, pero desafortunadamente muchas escuelas están dictando exactamente cómo deben interactuar los profesores
                                    con sus estudiantes. Basándonos en lo que te diga nuestra administración y supervisores, ¡aún podemos sacarle el máximo provecho a cualquier método
                                    y de manera creativa! De la misma manera que trabajas para crear actividades cuando das clases en persona, puedes hacerlo para tus clases en línea.
                                    Para el ambiente sincrónico, busca formas en que tu plataforma permita la interacción en tiempo real. Puedes darles a los estudiantes la oportunidad
                                    de hacer comentarios en el chat como un tipo de lluvia de ideas; puedes integrar encuestas para obtener opiniones anónimas; pueden ver videos juntos;
                                    y, en general, puedes crear actividades donde todos participen. Para los ambientes asincrónicos, mantente profundamente comprometido y responde por
                                    lo menos a la mitad de las respuestas de los participantes en las discusiones o chats, escribe tareas que conecten a nivel personal con los estudiantes
                                    y usando temas actuales, como por ejemplo podemos hacer que los participantes busquen información sobre el laboratorio de pruebas de ITS (Infecciones
                                    de Transmisión Sexual) más cercano a su casa y que reporten cómo están manejando las pruebas durante la cuarentena. Otra forma es incluir discusiones
                                    que sean sólo para la diversión de los participantes.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Recursos de la sesión</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="{{ asset('unhushed_hses_u-01_s-02_!-trivia-del-lenguaje_v01.pdf') }}" download="unhushed_hses_u-01_s-02_!-trivia-del-lenguaje_v01.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                UN|HUSHED, Unidad: 01, Sesión: 02 Trivia del lenguaje</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="{{ asset('unhushed_hses_u-01_s-02_rf-trivia-del-lenguaje_v01.pdf') }}" download="unhushed_hses_u-01_s-02_rf-trivia-del-lenguaje_v01.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                Recurso de facilitación: 02 Trivia del lenguaje</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
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
                    <iframe src="https://player.vimeo.com/video/448723288" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
