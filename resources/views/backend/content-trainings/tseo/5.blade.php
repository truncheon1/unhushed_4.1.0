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
                    <a class="px-1"><i class="fa-duotone fa-circle-5 fa-2x fa-fw"></i></a>
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
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 5v2: Find and/or make a curriculum that works online!</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday December 3rd, 2020.</span>
                                <br><b>Find and/or make a curriculum that works online!</b>
                                There are not many sex ed curricula that were written for the online space. Some curricula developers are allowing their F2F curricula to be modified for online use and some are not. Make sure you know which kind you’re using.
                                Replicating an in-person curriculum online doesn’t provide the most effective online learning experience.
                                Taking synchronous in-person lesson plans and moving them into a digital modality just doesn’t have the same learning impact.
                                Rather than trying to replicate a curriculum as-is, it is critical to modify it, with an eye towards activities and engagement that work well (or even better!) in the online space.
                                This will provide substantial increases in participant engagement and learning.</p>
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
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb5v1.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 5v1: In-person curriculum will only get you so far.</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday April 9th, 2020.</span>
                                <br><b>Replicating your in-person curriculum will only get you so far.</b>
                                By which I mean, they will probably get you through next week, but they definitely aren’t a long-term solution unless you’re doing a lot of modifications.
                                Attempting to take your synchronous in-person lesson plans and move them into a synchronous on-line class just doesn’t have the same learning impact.
                                Rather than trying to replicate your curriculum as-is, modifying it, with an eye towards activities and engagement that work well (or even better!) in the online space will provide substantial increases in participant engagement and learning.
                                However, it will take you some time to figure out how to modify and adapt your curriculum well, and that’s okay.</p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Specific Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-05_FR-PedagogicalCreativityTeachingOnline_v01.pdf') }}" download="UNHUSHED_TSEO_S-05_FR-PedagogicalCreativityTeachingOnline_v01.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Facilitator Resource: Pedagogical Creativity for Teaching Online
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-05_S-04_!-Heartbreaker_vwebseries.pdf') }}" download="UNHUSHED_MS_U-05_S-04_!-Heartbreaker_vwebseries.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Session: Heartbreaker (Taken from Middle School Unit-05 Session-04)
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-06_S-01_!-SexualValues_vwebseries.pdf') }}" download="UNHUSHED_MS_U-06_S-01_!-SexualValues_vwebseries.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                Session: Sexual Values (Taken from Middle School Unit-06 Session-01)
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
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="https://mailchi.mp/unhushed/session05" class="px-1">
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
                    <iframe src="https://player.vimeo.com/video/412301436" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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





