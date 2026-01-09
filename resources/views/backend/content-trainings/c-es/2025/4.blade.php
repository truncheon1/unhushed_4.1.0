@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-es.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <a href="{{ url($path.'/dashboard/trainings/elementary-school-curriculum-training-2025') }}"> The Elementary School Curriculum</a> |
                <span style="font-weight: bold;color:#9acd57">Day 4</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The Elementary School Curriculum</h3>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/1') }}" class="px-1">    <i class="fa-solid fa-circle-1 fa-2x fa-fw" alt="1"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/2') }}" class="px-1">    <i class="fa-solid fa-circle-2 fa-2x fa-fw" alt="2"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/es-curriculum-training/3') }}" class="px-1">    <i class="fa-solid fa-circle-3 fa-2x fa-fw" alt="3"></i></a>
                    <a class="px-1"><span style="font-weight: bold;color:#9acd57">          <i class="fa-solid fa-circle-4 fa-2x fa-fw" alt="4"></i></span></a>
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
                                <img src="{{ asset('img/cards-med/policy.png') }}" alt="Light bulb with gears inside" class="responsive" style="max-width:200px;">
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 4:</span> Classroom Management</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Thursday July 31st 11et/10ct/8pt to 3et/2ct/12pt</span>
                                    <ul class="green pl-4">
                                        <li>Classroom management</li>
                                        <li>Review of School Policies around boundaries</li>
                                        <li>Reviewed 4th & 5th grade materials</li>
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
                                <a href="https://us06web.zoom.us/j/82027473412?pwd=ya2kJhQaa7t3matcBEIzME3p1GXmRf.1" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                Zoom: Same for every live session!</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1St6Fq_zDlXIe6N_QxeZCGJVWDYcpipG9rPB6pbKJz10/edit?tab=t.0" target="_blank" class="pr-1">
                                <i class="fas fa-file fa-fw fa-lg align-middle"></i>
                                School Policies</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1hkDOTslxC5pRSwbDXe2jogwfyR-njFOXGQPd3Oqqxf8/edit?slide=id.g36389183854_0_16#slide=id.g36389183854_0_16" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                Writing Love Notes to Policies</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/dashboard/curricula/elementary-school-curriculum') }}" target="_blank" class="pr-1">
                                <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                Quick Link to the elementary school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework for Friday</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                Prep your peer facilitation activity. Don't forget to let us know <a href="https://docs.google.com/spreadsheets/d/1l4FRmNDdMWU4LR6LWi6boC5bE72KfimoZf-8_9bWdcA/edit?gid=207959903#gid=207959903" target="_blank">in this spreadsheet</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
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
