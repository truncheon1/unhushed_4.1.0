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
                <span style="font-weight: bold;color:#9acd57">Day 1</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The Elementary School Curriculum</h3>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></span></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/2') }}" class="px-1">    <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/3') }}" class="px-1">    <i class="fa-solid fa-circle-3 fa-2x fa-fw" alt="3"></i></a>
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
                                <img src="{{ asset('img/cards-med/hello.png') }}" alt="Lights bulbs waiving hello" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 1:</span> Introduction (to each other and UN|HUSHED)</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday June 12th, 2023.</span>
                                <br>Today we went over introductions to your trainers, Karen Rayne, PhD, CSE, and Nyk, as well as the UN|HUSHED elementary school curriculum and the backend of the website you will use to facilitate the materials. We also introduced participants to one another and discussed unit one.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 1 Resources</span></b>
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
                            <div class="col">
                                <a href="https://www.menti.com/" target="_blank" class="pr-1">
                                    <i class="fas fa-poll fa-fw fa-lg align-middle"></i>
                                Quick Link to Mentimeter</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/18xTsm0MY9FOHILkDaNoRBdyqgBr8ewL9h88OFvobr58/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Values, Boundaries & Sexuality Education</a>
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
                                Review <a href="{{ url($path.'/elementary-school-curriculum') }}" target="_blank">K-1 curriculum</a>. You'll have elements to review every night—makeing 
                                sure you do this thoroughly will allow you to get so much more out of the training!
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                <a href="https://www.psychologytoday.com/us/blog/modern-day-slavery/201707/trauma-informed-care-and-why-it-matters" class="pr-1" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Please read this article from Pyschology Today.</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/c-hs/Guide-toTrauma-Informed-Sex-Education.pdf') }}" download="Guide-toTrauma-Informed-Sex-Education.pdf" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                Please read this pdf "A Guide to Trauma Informed Sex Education" created and published by Cardea.</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">4.</div>
                            <div class="col-11">
                                If you are staying for K-1 on Tuesday, bring a soft paint or makeup brush and an ice cube.</a>
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
