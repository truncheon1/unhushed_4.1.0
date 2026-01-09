@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-hs.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <div class="row mx-auto">
            <div class="col-9 mx-5">
                <div class="nav-box">
                    <h3>The High School Curriculum</h3>
                    <a href="{{ url($path.'/high-school-curriculum-training/1') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/high-school-curriculum-training/2') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/high-school-curriculum-training/3') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/high-school-curriculum-training/4') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-9 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="https://drive.google.com/file/d/1q4AIbuzVX-qlRp1CUghTYB49_v7ItMQY/view?usp=sharing" target="_blank"><img src="/img/trainings/curricula/hs.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 5:</span> Facilitation Demo</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday June 18th, 2021.</span>
                                <br>Demo day! Today participants demo an activity and ask final questions.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 5 Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://zoom.us/j/99655939891?pwd=Q1BlMHRjRmZLd0hoZUx2cjBUWGNKZz09" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i></a>
                                <a href="https://zoom.us/j/99655939891?pwd=Q1BlMHRjRmZLd0hoZUx2cjBUWGNKZz09" target="_blank">
                                Zoom: Same for every live session!</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/high-school') }}" target="_blank" class="pr-1">
                                    <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i></a>
                                <a href="{{ url($path.'/high-school') }}" target="_blank">
                                Quick Link to the high school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.facebook.com/groups/thehighschoolcurriculum" target="_blank" class="pr-1">
                                    <i class="fab fa-facebook-square fa-fw fa-lg align-middle"></i></a>
                                <a href="https://www.facebook.com/groups/thehighschoolcurriculum" target="_blank">
                                Quick Link to The Facebook Group - UNHUSHED: The High School Curriculum</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                <a href="https://forms.gle/oYF66rsicXyyABdEA" target="_blank" class="pr-1">
                                <i class="fas fa-comment-dots fa-fw fa-lg align-middle"></i></a>
                                <a href="https://forms.gle/oYF66rsicXyyABdEA" target="_blank">
                                Final Feedback Form</a>
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
                    <!-- <iframe src="#" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe> -->
                    Video Coming Soon.
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
