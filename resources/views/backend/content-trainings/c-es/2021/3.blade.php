@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-es.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The Elementary School Curriculum</h3>
                    <a href="{{ url($path.'/elementary-school-curriculum-training/1') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/elementary-school-curriculum-training/2') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/elementary-school-curriculum-training/4') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/elementary-school-curriculum-training/5') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="https://drive.google.com/file/d/1O9DNS5mrW2jD9L5xx703-Q-ulxxi-TUj/view?usp=sharing" target="_blank"><img src="/img/trainings/curricula/es.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 3:</span> How to design an UN|HUSHED program + 2-3</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Wednesday July 14th, 2021.</span>
                                <br>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 1 Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://us06web.zoom.us/j/83297721276?pwd=ZDhwZkhSbG12Q2RMVDdVb09hb1pQQT09" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                Zoom: Same for every live session!</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/elementary-school') }}" target="_blank" class="pr-1">
                                <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                Quick Link to the elementary school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Homework for Thursday</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11">
                                Review <a href="{{ url($path.'/elementary-school') }}" target="_blank">Grades 4-5</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                Pick an activity to demo on Friday and let us know <a href="https://docs.google.com/spreadsheets/d/1u1-py63kSzoR-mDa5dIWxK063oVpxtkT3fzZ85ROgdM/edit#gid=0" target="_blank">in this spreadsheet</a>. It can be from anywhere in the curriculum. Try to push yourself! Pick content or process that you're the least comfortable with. People from the same orgs partner up.
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11">
                                Optional:  <a href="https://docs.google.com/spreadsheets/d/1u1-py63kSzoR-mDa5dIWxK063oVpxtkT3fzZ85ROgdM/edit#gid=0" target="_blank">in this spreadsheet</a>. It can be from anywhere in the curriculum. Try to push yourself! Pick content or process that you're the least comfortable with. People from the same orgs partner up.
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
