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
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/1') }}"  class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/2') }}"  class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/3') }}"  class="px-1"><i class="fa-duotone fa-circle-3 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/4') }}"  class="px-1"><i class="fa-duotone fa-circle-4 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/5') }}"  class="px-1"><i class="fa-duotone fa-circle-5 fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-duotone fa-circle-6 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/7') }}"  class="px-1"><i class="fa-solid fa-circle-7 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/8') }}"  class="px-1"><i class="fa-solid fa-circle-8 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/9') }}"  class="px-1"><i class="fa-solid fa-circle-9 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/10') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/bonus-tech-session') }}"        class="px-1"><i class="fas fa-plus-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-10 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video2" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb1v2.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 6v2: Synchronous or asynchronous, that is the question</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday January 14th, 2021.</span>
                                <br><b>Class environments that are synchronous (at the same time, by video or voice) and asynchronous (at different times, by text, video, and/or voice) have both upsides and downsides.</b>
                                Ideally you can use both methods in integrative ways that are most effective for specific content, but many schools and organization are providing direction on exactly how teachers must interact with their students. Based on what your administration is telling you,  you can still get the most out of any online environment with creativity! In the same ways that you work to create engaging in person activities by thinking outside the box, you can do the same online. For synchronous environments, look into ways that your platform allows real-time interaction. This may mean providing students with the opportunity to  annotate as a way to brainstorm, integrating polls for anonymous feedback, watching videos together, and having participants engage in whole-group movement activities that everyone is able to participate in. For asynchronous environments, stay deeply engaged and respond to at least half of the participants’ responses, write assignments that connect personally and currently, like having participants look up information like the closest STI testing center to their home and report back on how they’re handling testing during quarantine, and include discussions that are just for fun rather than being required to allow for some sanctioned chit-chat.</p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Specific Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb6v1.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 6v1: Synchronous or asynchronous, that is the question</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday April 9th, 2020.</span>
                                <br><b>Class environments that are synchronous (at the same time, by video or voice) and asynchronous (at different times, by text, video, and/or voice) have both upsides and downsides.</b>
                                Ideally you can use both methods in integrative ways that are most effective for specific content, but many schools are dictating exactly how teachers must interact with their students. Based on what your administration is telling you,  you can still get the most out of any online environment with creativity!
                                In the same ways that you work to create engaging in person activities by thinking outside the box, you can do the same online. For synchronous environments, look into ways that your platform allows real-time interaction.
                                This may mean providing students with the opportunity to  annotate as a way to brainstorm, integrating polls for anonymous feedback, watching videos together, and having participants engage in whole-group movement activities that everyone is able to participate in. For asynchronous environments,
                                stay deeply engaged and respond to at least half of the participants’ responses, write assignments that connect personally and currently, like having participants look up information like the closest STI testing center to their home and report back on how they’re handling testing during quarantine,
                                and include discussions that are just for fun rather than being required to allow for some sanctioned chit-chat.</p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Specific Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-06_FR-TipsForModifyingF2FCurriculumForOnlineLearning_v02.pdf') }}" download="UNHUSHED_TSEO_S-06_FR-TipsForModifyingF2FCurriculumForOnlineLearning_v02.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Facilitator Resource: Tips For Modifying F2F Curriculum For Online Learning
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-06_H-OnceUponATime.pdf') }}" download="UNHUSHED_TSEO_S-06_H-OnceUponATime.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Handout: Once Upon A Time
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-06_W-OnceUponATime_worksheet.pdf') }}" download="UNHUSHED_TSEO_S-06_W-OnceUponATime_worksheet.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Pdf Form: Once Upon A Time
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="https://mailchi.mp/unhushed/session05" class="px-1">
                                <i class="far fa-envelope fa-fw fa-lg align-middle"></i></a>Email sent out the morning of the original session.
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video3" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb6vbonus.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 6v1 bonus: How to screen share in zoom</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Wednesday April 8th, 2020.</span>
                                <br><b>Knowing how to screen share, and walk your participants through it is vital.</b>
                                A quick crash course in how to screen share on zoom.</p>
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
                    <iframe src="https://player.vimeo.com/video/409930756" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
                    <iframe src="" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <p style="text-center">Our video editing team is working hard to get this content out ASAP, but don't worry!
                    <br>Version 1 is up and ready to watch now. Just click the next video down.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal video 2 -->
    <div id="video3" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    <iframe src="https://player.vimeo.com/video/408157596" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
        $("#video3").on('hidden.bs.modal', function (e) {
            $("#video3 iframe").attr("src", $("#video3 iframe").attr("src"));
        });
    </script>
</section>
@endsection





