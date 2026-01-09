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
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/1') }}" class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/2') }}" class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/3') }}" class="px-1"><i class="fa-duotone fa-circle-3 fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-duotone fa-circle-4 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/5') }}"  class="px-1"><i class="fa-solid fa-circle-5 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/6') }}"  class="px-1"><i class="fa-solid fa-circle-6 fa-2x fa-fw"></i></a>
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
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 4v2: COVID + Communication</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday November 5th, 2020.</span>
                                <br><b>Teaching about communication is even more important than before COVID.</b>
                                All communication education prior to COVID worked from an assumption that the mere act of being together in the same room would not, itself, be a potential infectious risk factor.
                                Increasing young people’s capacity to discuss risk factors, identify their own needs, and communicate those needs has never been more important.
                                These communication skills need to honed in both in person and digital modalities.</p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Specific Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-04_!-Covid-Communication_v01.pdf') }}" download="UNHUSHED_TSEO_S-04_!-Covid-Communication_v01.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Activity: Covid + Communication
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-04_FR-Negotiation Scenarios_v01.pdf') }}" download="UNHUSHED_TSEO_S-04_FR-Negotiation Scenarios_v01.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Facilitator Resource: Negotiation Scenarios
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-04_PP-Would I.pptx') }}" download="UNHUSHED_TSEO_S-04_PP-Would I.pptx" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                PowerPoint: Would I ________?
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb3v1.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 4v1: Short and sweet</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday April 9th, 2020.</span>
                                <br><b>Your curriculum, in the short term, should not be as robust as you had planned for it to be in person.</b>
                                Our participants are all dealing with trauma right now. Quarantine is difficult in the best of situations, but with added stressors like a family that fights a lot or is abusive, family or friends who are at increased risk, new responsibilities of caring for younger siblings, trying to access school without the necessary technology, parents who have lost their jobs, and more, young people’s capacity for learning is dramatically reduced.
                                Some of these stressors will be based in participants’ gender identities and sexual orientation, painful separation from a person they are dating, experiencing sexual abuse while under quarantine, or other elements related to the content of sexuality classes. Learning about these topics may be particularly meaningful and/or triggering during this time.
                                Regardless of what participants are dealing with in their personal lives, meeting them where they are by taking the time to listen and responding to their immediate fears and needs rather than focusing exclusively on the larger curricular program should be the most important element of your educational plans.</p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Specific Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-04_PP-Short&Sweet.pptx') }}" download="UNHUSHED_TSEO_S-04_PP-Short&Sweet.pptx" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                PowerPoint: Short & Sweet
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="https://classroom.google.com/u/0/h" class="px-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>Link: Google Classroom (use code: cvyxtku )
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="https://classroom.google.com/u/0/c/Njg4MTU5NDcwMjZa" class="px-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>Link: Google Classroom (if you have previously signed in)
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">More Session Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="https://docs.google.com/a/unhushed.org/forms/d/1avbS9ClTBwbrN_p-8k0j7Oc89UCTYXlJYh0o48EjUig/edit?usp=slack&ts=5e8e78b7" class="px-1">
                                <i class="fad fa-poll-people fa-fw fa-lg align-middle"></i></a>Survey: How are we doing so far?
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="https://mailchi.mp/unhushed/session4" class="px-1">
                                <i class="far fa-envelope fa-fw fa-lg align-middle"></i></a>Email sent out the morning of the original session.
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
                    <iframe src="https://player.vimeo.com/video/410017584" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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





