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
                <span style="font-weight: bold;color:#9acd57">Day 1</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The Middle School Curriculum</h3>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></span></a>
                    <a href="{{ url($path.'/dashboard/trainings/ms-curriculum-training/2') }}" class="px-1">    <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></a>
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
                                <img src="{{ asset('img/cards-med/hello.png') }}" alt="Lights bulbs waiving hello" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 1:</span> Introduction (to each other and UN|HUSHED)</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Monday Aug 4th 11et/10ct/8pt to 3et/2ct/12pt</span>
                                    <ul class="green pl-4">
                                        <li>Introductions to your trainers, Karen Rayne, PhD, CSE, and Nyk, and fellow participants</li>
                                        <li>Group agreements</li>
                                        <li>Tour the backend of the website you will use to facilitate the materials</li>
                                        <li>Values & Boundaries</li>
                                        <li>Intro/overview of the UN|HUSHED middle school curriculum</li>
                                        <li>Facilitation vs teaching</li>
                                    </ul>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 1 Resources</span></b>
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
                                <a href="https://docs.google.com/document/d/1yjTrrMz8eY_2gNxTcXomofYcbQX6uwttGMhNVVQqlhY/edit?tab=t.0" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Group agreements</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/18qydQzDZz0-lffw5rshV65nzWCGw1bKdtgFpZViEg0s/edit?slide=id.gdfe1d0954e_3_75#slide=id.gdfe1d0954e_3_75" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Values, Boundaries & Sexuality Education</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1Iwv5iseVkmHgk1V7psb8tpPSSShSWYIg1XpRfNtQk28/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Facilitator Values</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://drive.google.com/file/d/1hFEJG_znrdmSXXidyNpYAZ2iQKkI033U/view?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Things a teacher does</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1bXiRRVi4ezn7sbkfgwTJK6Hw7yMFCHdpTYZNWHRcH5U/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                Things a facilitator does</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework</span></b>
                            </div>
                            <div class="col-12 pb-1">You'll have segments of the curricula and relevant articles/info to review every night—making 
                                sure you do this thoroughly will allow you to get so much more out of the training!</div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                Review <a href="{{ url($path.'/dashboard/curricula/middle-school-curriculum#l4') }}" target="_blank">the facilitator guide and units 1-2 curriculum</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                <a href="https://responsiblesexedinstitute.org/rsei-blog/trauma-informed-sex-education/" class="pr-1" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Read, "Trauma-Informed Sex Education."</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/t-c_es/Exploring how trauma is addressed in sexual education interventions for youth.pdf') }}" download="Exploring how trauma is addressed in sexual education interventions for youth.pdf" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                Read, "Exploring How Trauma Is Addressed in Sexual Education Interventions for Youth: A Scoping Review"</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">4.</div>
                            <div class="col-11">
                                <a href="https://resilienteducator.com/classroom-resources/accountable-talk-classroom/" download="Doing Accountable Talk in the Classroom" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Read, "Doing Accountable Talk in the Classroom"</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">5.</div>
                            <div class="col-11">
                                Bring a writing/drawing utensil, paper, and a small mirror (or you can use selfie mode on your phone) with you tomorrow.
                            </div>
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
