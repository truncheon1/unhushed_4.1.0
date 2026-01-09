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
                    <a class="px-1"><i class="fa-solid fa-clock-one fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/2') }}"  class="px-1"><i class="fa-solid fa-clock-two fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/3') }}"  class="px-1"><i class="fa-solid fa-clock-three fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/4') }}"  class="px-1"><i class="fa-solid fa-clock-four fa-2x fa-fw"></i></a>
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
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/se&l/thumb.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 1:</span> Introduction (to each other and UN|HUSHED)</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on September 2nd, 2021.</span>
                                <br>Day 1 of the training includes introductions to Karen Rayne, PhD, CSE, Brian, MLS, and Nyk Rayne. Then we dive straight into confidentiality, how to talk to kids about sex and the law, and how to stay up to date with legal changes.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1B_2qBToPKN4sonuqcb8RylxF76c93r1hu51qivaHobA/edit" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Group Agreements (editable during live session and view only for asynchronous participation)</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1YoA7LBmbkbD0YOtd1rbBFRFOVJ8yT4swWSqJSCNwJSM/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Group 1 (view only for asynchronous participation)</a>
                                <br>
                                <a href="https://docs.google.com/document/d/1YrMNvJjbuY3f4z0iiP4yu2CPO24S0LZm13QabdlmMiI/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Group 2 (view only for asynchronous participation)</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ asset('pdfs/t-se&l/UNHUSHED_SE&L_FR-LegalResources_v01.pdf') }}" download="UNHUSHED_TSEO_S-01_FR-Ways2modifyF2Fapproaches_v02.pdf" class="px-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                FR: Legal Resources</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Places to find laws and policies related to Sex and Sex Education</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.guttmacher.org/state-policy/laws-policies" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Guttmacher State Laws and Policies</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://siecus.org/state-profiles/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                SIECUS State Profiles</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://sexetc.org/action-center/sex-in-the-states/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Sex Etc Sex in the States</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Keeping Current</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://apnews.com/hub/sex-education" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i> AP Sex Education News Hub</a>

                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.usnews.com/topics/subjects/sex_education" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i> US News Sex Education Hub</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://rewirenewsgroup.com/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i> Rewire News Group</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
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
                    <iframe src="https://player.vimeo.com/video/613210475?h=68b3958a84" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        //Play video modal
        $("#video1").on('hidden.bs.modal', function (e) {
            $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
        });
        //Moveable modal
        $("#video1").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection
