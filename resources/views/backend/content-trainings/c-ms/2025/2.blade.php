@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-ms.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <a href="{{ url($path.'/dashboard/trainings/middle-school-curriculum-training-2025') }}"> The Middle School Curriculum</a> |
                <span style="font-weight: bold;color:#9acd57">Day 2</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The Middle School Curriculum</h3>
                    <a href="{{ url($path.'/dashboard/trainings/ms-curriculum-training/1') }}" class="px-1">    <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></a>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></span></a>
                    <a href="{{ url($path.'/dashboard/trainings/ms-curriculum-training/3') }}" class="px-1">    <i class="fa-solid fa-circle-3 fa-2x fa-fw" alt="3"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ms-curriculum-training/4') }}" class="px-1">    <i class="fa-solid fa-circle-4 fa-2x fa-fw" alt="4"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/ms-curriculum-training/5') }}" class="px-1">    <i class="fa-solid fa-circle-5 fa-2x fa-fw" alt="5"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row mx-5">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <img src="{{ asset('img/cards-med/idea.png') }}" alt="Colorful light bulb" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 2:</span> Facilitation</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Tuesday Aug 5th 11et/10ct/8pt to 3et/2ct/12pt</span>
                                    <ul class="green pl-4">
                                        <li>Facilitator Guide Scavenger Hunt</li>
                                        <li>Intro/overview of curriculum Facilitator Guide and Parent orientation</li>
                                        <li>Anonymous Questions</li>
                                        <li>Trauma informed sex ed</li>
                                        <li>Intro/overview of curriculum Units 1-2</li>
                                    </ul>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 2 Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://us06web.zoom.us/j/82641663476?pwd=zoTvZBqtf7bRZBYHgaaTlrLRaQsnEW.1" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                Zoom: Same for every session.</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/dashboard/curricula/middle-school-curriculum') }}" target="_blank" class="pr-1">
                                <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                Quick Link to the middle school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1Tg7ywRPNV2d2Nl0UKz-HURoEGf4V02qBqVZ-zd8QL8c/edit?slide=id.ge00b09c1de_3_75#slide=id.ge00b09c1de_3_75" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Trauma Informed Sex Ed</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1S-GRQyEMz0JpJsWiT-iRGKZb4rIcOK3U/edit?slide=id.ge065f113b6_1_75#slide=id.ge065f113b6_1_75" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Anonymous Questions for Middle School</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                Review <a href="{{ url($path.'/dashboard/curricula/middle-school-curriculum#l4') }}" target="_blank">units 3-4 curriculum</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                Bring your completed <a href="https://unhushed.org/resource/unhushed_e_u-00_s-00_fr-constructing-an-unhushed-program_v01.docx" target="_blank">FR: Constructing-an-UNHUSHED-program worksheet</a>.
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
