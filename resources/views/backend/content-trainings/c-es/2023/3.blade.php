@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-es.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <a href="{{ url($path.'/dashboard/trainings/elementary-school-curriculum-training-2023') }}"> The Elementary School Curriculum</a> |
                <span style="font-weight: bold;color:#9acd57">Day 3</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The Elementary School Curriculum</h3>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/1') }}" class="px-1">    <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/3') }}" class="px-1">    <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></a>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-3 fa-2x fa-fw" alt="3"></i></span></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/4') }}" class="px-1">    <i class="fa-solid fa-circle-4 fa-2x fa-fw" alt="4"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/5') }}" class="px-1">    <i class="fa-solid fa-circle-5 fa-2x fa-fw" alt="5"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row mx-5">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <img src="{{ asset('img/cards-med/design.png') }}" alt="White light bulb and bar graph on black background" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 3:</span> How to design an UN|HUSHED program + 2-3</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Wednesday June 14th, 2023.</span>
                                <br>Today we talked about how to design your UN|HUSHED program. We also went over the 2-3 grade curriculum in depth</p>
                                <br>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 3 Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://us06web.zoom.us/j/89756656119?pwd=VEhwMXhGZnJueWx4L0NjMG9mNnl4Zz09" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                Zoom: Same for every live session!</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/elementary-school-curriculum') }}" target="_blank" class="pr-1">
                                    <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                Quick Link to the elementary school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework for Thursday</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                Review <a href="{{ url($path.'/elementary-school-curriculum') }}" target="_blank">Grades 4-5</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                Pick an activity to demo on Friday and let us know <a href="https://docs.google.com/spreadsheets/d/1u1-py63kSzoR-mDa5dIWxK063oVpxtkT3fzZ85ROgdM/edit#gid=0" target="_blank">in this spreadsheet</a>. 
                                It can be from anywhere in the curriculum. Try to push yourself! Pick content or a process that you're the least comfortable with. People from the same orgs partner up.
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
