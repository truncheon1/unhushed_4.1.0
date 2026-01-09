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
                <span style="font-weight: bold;color:#9acd57">Day 2</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The High School Curriculum</h3>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/1') }}" class="px-1">    <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></a>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></span></a>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/3') }}" class="px-1">    <i class="fa-solid fa-circle-3 fa-2x fa-fw" alt="3"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/4') }}" class="px-1">    <i class="fa-solid fa-circle-4 fa-2x fa-fw" alt="4"></i></a>
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
                                <img src="{{ asset('img/cards-med/idea.png') }}" alt="Colorful light bulb" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 2:</span> Facilitation intro</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Tuesday Aug 12th 11et/10ct/8pt to 3et/2ct/12pt</span>
                                    <ul class="green pl-4">
                                        <li>Facilitation vs teaching</li>
                                        <li>Anonymous questions</li>
                                        <li>Trauma informed sex ed</li>
                                        <li>Unit 1 overview</li>
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
                                <a href="https://us06web.zoom.us/j/89380144314?pwd=9OUWanIAIELQloGuZK5nhZb4O8EdLd.1" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                Zoom: Same for every live session.</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/dashboard/curricula/high-school-curriculum') }}" target="_blank" class="pr-1">
                                <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                Quick Link to the high school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://drive.google.com/file/d/1s66lQ5FU-YioUOIvYTJop8ck9hGK0jef/view?usp=drive_link" class="pr-1" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Things we do: Teacher vs. Facilitator</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1dPSrj2OOCtfoyrcXPaeZVtJZcGqRt0kw8ZLO_UrYhlQ/edit?slide=id.gdfe1d0954e_3_80#slide=id.gdfe1d0954e_3_80" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Facilitator Values & Sexuality Education</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1J9SievRyBGAPdUU2xgg-r_X2qPs7n48EtRiKWQ1Tncg/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Trauma informed sex ed</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                Bring your completed <a href="https://docs.google.com/document/d/1Hy46sruj9SXM5Jko-uzViY3dXXZkGR4rp_uk7IUSssw/copy" target="_blank">FR: Constructing-an-UNHUSHED-program worksheet</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Other stuff we talked about</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">*</div>
                            <div class="col-11">
                                <a href="https://drive.google.com/file/d/1lNdzTZjG0pA2MxZB6oZLSp3yBqa9NtR_/view?usp=sharing" class="pr-1" target="_blank">
                                <i class="fa-solid fa-file-zip fa-fw fa-lg align-middle"></i>
                                Downloadable packet of articles about forms of address and gender bias</a>
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
