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
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/6') }}"  class="px-1"><i class="fa-duotone fa-circle-6 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/7') }}"  class="px-1"><i class="fa-duotone fa-circle-7 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/8') }}"  class="px-1"><i class="fa-duotone fa-circle-8 fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/9') }}"  class="px-1"><i class="fa-duotone fa-circle-9 fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-duotone fa-stop-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/bonus-tech-session') }}" class="px-1"><i class="fas fa-plus-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-10 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#" width="200" height="113" data-bs-toggle="modal"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 10v2: Practice self compassion.</span>
                                <br><span style="font-weight:light;font-size:12px">live on Thursday May 6th, 2021</span>
                                <br><b>Let's practice self compassion.</b>
                                While these are top priorities at all times, for participants and facilitators alike, it is even more so in times of deep trauma and stress.
                                And everyone is experiencing trauma and stress right now. Preparing to attend to your own self care and self compassion needs,
                                as well as supporting your participants in attending to their own needs, should be a critical element of your work plan.</p>
                                <p>Join us for the final live session.</p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="https://zoom.us/j/94394331130?pwd=M3Jmdy8waUxpZTVEbXdEeTlvb0Nmdz09" target="_blank" class="px-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i></a> Zoom: Same for every live session!
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb10v1.png"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 10v1: This is awkward for everyone</span>
                                <br><span style="font-weight:light;font-size:12px">recorded on Thursday April 9th, 2020.</span>
                                <br><b>Let's practice self compassion.</b>
                                </p>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57">Session Resources</span></b>
                            </div>
                            <div class="col-lg-4 col-sm-12 text-center align-self-center"></div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-09_FR-MaslowsHeirchyOfNeeds_v02.pdf') }}" download="UNHUSHED_TSEO_S-09_FR-MaslowsHeirchyOfNeeds_v02.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
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
                    <iframe src="https://player.vimeo.com/video/417281964" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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





