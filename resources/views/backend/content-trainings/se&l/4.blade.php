@extends('layouts.app')
@section('content')
@include('backend.content-trainings.se&l.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Sex Ed & the Law</span>
            </div>
        </div>

        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>Sex Ed & the Law</h3>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/1') }}"  class="px-1"><i class="fa-solid fa-clock-one fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/2') }}"  class="px-1"><i class="fa-solid fa-clock-two fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/3') }}"  class="px-1"><i class="fa-solid fa-clock-three fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-solid fa-clock-four fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/5') }}"  class="px-1"><i class="fa-solid fa-clock-five fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/6') }}"  class="px-1"><i class="fa-solid fa-clock-six fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/7') }}"  class="px-1"><i class="fa-solid fa-clock-seven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/8') }}"  class="px-1"><i class="fa-solid fa-clock-eight fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/9') }}"  class="px-1"><i class="fa-solid fa-clock-nine fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/10') }}" class="px-1"><i class="fa-solid fa-clock-ten fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/11') }}" class="px-1"><i class="fa-solid fa-clock-eleven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/12') }}" class="px-1"><i class="fa-solid fa-clock-twelve fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row mx-5">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/se&l/thumb.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 4:</span> Health Care Access</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on October 21st, 2021.</span>
                                <br>Day 4 of the training covers the vast issue of maintaining medical privacy and supporting youth in being their own medical home.
                                As part of this conversation, we cover health care access laws across states.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/179LawMYfzOFQtnRM-s95Y2i2GAgZ6AWgGc8h8Rt3Pao/edit#slide=id.p1" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                PP: Health Care Access</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/spreadsheets/d/1AFvvRNFuRwvZDQvtRWfEVESSM0n5dLtzX-XnEtft85o/edit#gid=0" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Spreadsheet: State Laws Chart (view only for asynchronous participation)</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1spS03BqiF-qlHKfO60jDpa85oOW2dfVKT_pP-Yn7-I4/edit" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Scenarios: Session 04 (view only for asynchronous participation)</a>
                            </div>
                        </div>
                        @include('backend.content-trainings.pdf-instruct')
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
                    <iframe src="https://player.vimeo.com/video/642315071?h=1fb3e0ad58" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
