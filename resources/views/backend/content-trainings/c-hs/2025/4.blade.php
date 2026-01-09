@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-hs.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <a href="{{ url($path.'/dashboard/trainings/high-school-curriculum-training-2025') }}"> The High School Curriculum</a> |
                <span style="font-weight: bold;color:#9acd57">Day 4</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The High School Curriculum</h3>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/1') }}" class="px-1">    <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/2') }}" class="px-1">    <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/3') }}" class="px-1">    <i class="fa-solid fa-circle-3 fa-2x fa-fw" alt="3"></i></a>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-4 fa-2x fa-fw" alt="4"></i></span></a>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/5') }}" class="px-1">    <i class="fa-solid fa-circle-5 fa-2x fa-fw" alt="5"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row mx-5">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <img src="{{ asset('img/cards-med/policy.png') }}" alt="Light bulb with gears inside" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 4:</span> Management and Policies</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Thursday Aug 14th 11et/10ct/8pt to 3et/2ct/12pt</span>
                                    <ul class="green pl-4">
                                        <li>Learning Processes</li>
                                        <li>Unit 4: Media</li>
                                        <li>Unit 5: Global Perspectives</li>
                                        <li>Talking to stakeholders</li>
                                        <li>Get ready for demos!</li>
                                    </ul>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 4 Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://us06web.zoom.us/j/89380144314?pwd=9OUWanIAIELQloGuZK5nhZb4O8EdLd.1" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                Zoom: Same for every live session.</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/high-school-curriculum') }}" target="_blank" class="pr-1">
                                <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                Quick Link to the high school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1mLSGewH6Xi-Tiz_XmZn4bYpQynoxEYDFPobNjgSx9TU/edit?usp=sharing" class="pr-1" target="_blank">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                The Teenage Brain</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        @include('backend.content-trainings.pdf-instruct')
                    </div>
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
