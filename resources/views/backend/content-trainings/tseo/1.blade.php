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
                    <a class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/2') }}"  class="px-1"><i class="fa-solid fa-circle-2 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/3') }}"  class="px-1"><i class="fa-solid fa-circle-3 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/4') }}"  class="px-1"><i class="fa-solid fa-circle-4 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/5') }}"  class="px-1"><i class="fa-solid fa-circle-5 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/6') }}"  class="px-1"><i class="fa-solid fa-circle-6 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/7') }}"  class="px-1"><i class="fa-solid fa-circle-7 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/8') }}"  class="px-1"><i class="fa-solid fa-circle-8 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/9') }}"  class="px-1"><i class="fa-solid fa-circle-9 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/10') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/bonus-tech-session') }}"        class="px-1"><i class="fas fa-plus-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-10 mx-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row px-4">
                                <div class="col-lg-3 col-sm-12 text-right align-self-center">
                                    <a href="#video2" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb1v2.png"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 1v2: This is different</span>
                                    <br><span style="font-weight:light;font-size:12px">recorded on Thursday August 6th, 2020.</span>
                                    <br><b>This is different from teaching almost every other content area online, and it can be done well.</b>
                                    The engaging, participatory, facilitated environment that most sex ed classrooms encompass can be brought into the online environment – you just need to know how! Turning your creativity, enthusiasm, and buy-in up to 11 is the first step to providing an exciting, dynamic, informative online program, and you can absolutely make that happen! Specific, detailed planning is the key.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row px-4">
                                <div class="col-lg-3 col-sm-12 text-right align-self-center">
                                    <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb1v1.png"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 1v1: This is hard!</span>
                                    <br><span style="font-weight:light;font-size:12px">recorded on March 31st, 2020.</span>
                                    <br><b>This is hard and different from teaching almost every other content area online, and offering something rather than nothing matters.</b>
                                    It’s difficult for any teacher to pick up teaching online with short turn around and limited training.
                                    It is even more difficult for teachers of sexuality because our content is deeply personal, relational, and connected.
                                    UN|HUSHED has always said that because sex (usually) happens in person, sex ed should (usually) happen in person.
                                    But we live in unusual times! There are specific concerns with moving our content online, but we CAN do it, and we NEED to do it.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mx-5">
                                <div class="col-12 text-center">
                                    <b><span style="color:#9acd57">Session Resources</span></b>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-01_FR-OneNewToolBrainstorm_v01.pdf') }}" download="UNHUSHED_TSEO_S-01_FR-OneNewToolBrainstorm_v01.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Facilitator Resource: One New Tool Brainstorm (created with participants during session 1v1)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-01_FR-Ways2modifyF2Fapproaches_v02.pdf') }}" download="UNHUSHED_TSEO_S-01_FR-Ways2modifyF2Fapproaches_v02.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Facilitator Resource: Ways 2 Modify F2F Approaches (version 2 created by the unhushed team)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-01_Glossary_OnlineTeachingTerms_v03.pdf') }}" download="UNHUSHED_TSEO_S-01_Glossary_OnlineTeachingTerms_v03.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Glossary: Online Teaching Terms (version 3 of the original .pdf)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ url($path.'/onlineTerms') }}" class="px-1"><i class="fas fa-link fa-fw fa-lg align-middle"></i></a> As a result of this training we now maintain a free online teaching terms glossary in our free content section.
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://zoom.us/j/94394331130?pwd=M3Jmdy8waUxpZTVEbXdEeTlvb0Nmdz09" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a> Zoom: Same for every live session!
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://mailchi.mp/unhushed/were-excited-to-see-you-tomorrow" class="px-1">
                                        <i class="far fa-envelope fa-fw fa-lg align-middle"></i></a>
                                    Email sent out the day before the original session 03/31/2020
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
                        <iframe src="https://player.vimeo.com/video/404058692" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal video 2 -->
        <div id="video2" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                        <iframe src="https://player.vimeo.com/video/468693598" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $("#video1").on('hidden.bs.modal', function (e) {
                $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
            });
            $("#video2").on('hidden.bs.modal', function (e) {
                $("#video2 iframe").attr("src", $("#video2 iframe").attr("src"));
            });
        </script>
    </section>
@endsection
