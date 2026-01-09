@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-ms.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">
            <div class="row mx-auto">
                <div class="col-8 mx-5">
                    <div class="nav-box">
                        <h3>The Middle School Curriculum</h3>
                        <a href="{{ url($path.'/middle-school-curriculum-training/1') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/2') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/3') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                        <a class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/middle-school-curriculum-training/5') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-8 mx-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                    <a href="https://drive.google.com/file/d/1Tw8TXrOkG5QxqzjmmrR5kY1_xX7ZY3dw/view?usp=sharing" target="_blank"><img src="/img/trainings/curricula/ms.png" alt="Play Video"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 4:</span> Sex Ed Classroom Managment</b>
                                    <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday July 1st, 2021.</span>
                                    <br>Today we discussed Unit 3: Sexual Health (the largest unit in the curriculum) and talked about classroom managment in the sex ed classroom. Everyone prepares for their demo activity on Friday.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mx-5">
                                <div class="col-12 text-center">
                                    <b><span style="color:#9acd57">Day 4 Resources</span></b>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col">
                                    <a href="https://us06web.zoom.us/j/84183494396?pwd=TXFlTFNnUmJIY3hvVytVL3NDV2xZQT09" target="_blank" class="pr-1">
                                    <i class="fas fa-video fa-fw fa-lg align-middle"></i>
                                    Zoom: Same for every live session!</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col">
                                    <a href="{{ url($path.'/middle-school') }}" target="_blank" class="pr-1">
                                        <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i>
                                    Quick Link to the middle school curriculum page</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col">
                                    <a href="https://docs.google.com/document/d/1ryrSCSYI1jLXcpG3Mwq2B6wLNpsUQXEh9JpxIBiyTac/edit" target="_blank" class="pr-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                    Classroom Managment Gone Wrong</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row px-4">
                                <div class="col-12 text-center">
                                    <b><span style="color:#9acd57">Homework</span></b>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1 text-right">1.</div>
                                <div class="col-11">
                                    Review the <a href="{{ url($path.'/high-school') }}" target="_blank">Unit 5: Violence and Consent.</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1 text-right">2.</div>
                                <div class="col-11">
                                    Prepare your activity to demo tomorrow.
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
