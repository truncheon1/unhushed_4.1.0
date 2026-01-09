@extends('layouts.app')
@section('content')
@include('backend.content-trainings.tseo.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Teaching Sex Ed Online</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-10 mx-5">
                <div class="nav-box">
                    <h3>Teaching Sex Ed Online</h3>
                        @if(auth()->user()->is_assigned_training(1))
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/1') }}"  class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/2') }}"  class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/3') }}"  class="px-1"><i class="fa-duotone fa-circle-3 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/4') }}"  class="px-1"><i class="fa-duotone fa-circle-4 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/5') }}"  class="px-1"><i class="fa-duotone fa-circle-5 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/6') }}"  class="px-1"><i class="fa-duotone fa-circle-6 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/7') }}"  class="px-1"><i class="fa-duotone fa-circle-7 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/8') }}"  class="px-1"><i class="fa-duotone fa-circle-8 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/9') }}"  class="px-1"><i class="fa-duotone fa-circle-9 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/10') }}" class="px-1"><i class="fa-duotone fa-stop-circle fa-2x fa-fw"></i></a>
                        <a class="px-1"><i class="fa-duotone fa-plus-circle fa-2x fa-fw"></i></a>
                        @else
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-3 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-4 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-5 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-6 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-7 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-8 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-circle-9 fa-2x fa-fw"></i></a>
                        <a href="{{ url('#') }}" class="px-1"><i class="fa-duotone fa-stop-circle fa-2x fa-fw"></i></a>
                        <a class="px-1"><i class="fa-duotone fa-plus-circle fa-2x fa-fw"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-10 mx-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 text-center align-self-center">
                                    <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb-tech.png" alt="Play Video"></a>
                                </div>
                                <div class="col-9 text-justify">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 11:</span> Bonus Tech Chat</b>
                                    <br><span style="font-weight:light;font-size:12px">recorded on Thursday August 6th, 2020.</span>
                                    <br><b>This is different from teaching almost every other content area online, and it can be done well.</b>
                                    The engaging, participatory, facilitated environment that most sex ed classrooms encompass can be brought into the online environment – you just need to know how! Turning your creativity, enthusiasm, and buy-in up to 11 is the first step to providing an exciting, dynamic, informative online program, and you can absolutely make that happen! Specific, detailed planning is the key.</p>
                                </div>
                            </div>
                            @if(!auth()->user()->is_assigned_training(1))
                            <hr>
                            <div class="row justify-content-md-center">
                                <div class="col-12 text-center">
                                    <b><span style="color:#9acd57">Want more?</span></b>
                                </div>
                                <div class="col-8 text-center">
                                    <a href="{{ url($path.'/store/teaching-sex-ed-online') }}"><i class="fa-solid fa-cart-shopping"></i></a>
                                    <p>Start the full ten hour <a href="{{ url($path.'/store/teaching-sex-ed-online') }}"><i><b>Teaching Sex Ed Online</b></i></a> series today!</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal video 1 -->
        <div id="video1" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                        <iframe src="https://player.vimeo.com/video/412945759" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $("#video1").on('hidden.bs.modal', function (e) {
                $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
            });
        </script>
    </section>
@endsection





