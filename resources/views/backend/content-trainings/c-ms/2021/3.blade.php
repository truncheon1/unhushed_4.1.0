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
                    <a class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/middle-school-curriculum-training/4') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
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
                                <a href="https://drive.google.com/file/d/19UPZBlb7wNz6lsM0SetFRy__lndX_rKo/view?usp=sharing" target="_blank"><img src="/img/trainings/curricula/ms.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 3:</span> How to design an UN|HUSHED program</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday June 30th, 2021.</span>
                                <br>Today was spent going over learning processes, Unit 2: Relationships, the Facilitator Guide, and prep for day 4.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 3 Resources</span></b>
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
                                <a href="https://drive.google.com/file/d/1B04dHBSQms1BK0iEzzb28Z6AmaRrRDyF/view?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                PP: Middle School Brains: How to Teach Them</a>
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
                                Pick an activity to demo on Friday. It can be from anywhere in the curriculum. Try to push yourself! Pick content or process that you're the least comfortable with.
                                <p style="font-size: 10px">
                                <br>Unit 1, Session 2: Media, Media, Media!
                                <br>Activity 5: Social Media, 20 minutes
                                <br>(Medium)
                                <br>
                                <br>Unit 2, Session 1: Gendersocks
                                <br>Activity 3: Biology, 10 minutes
                                <br>(Hard)
                                <br>
                                <br>Unit 3, Session 1: Relationships 101
                                <br>Activity 2: What do you want and what do you need?, 20 minutes
                                <br>(Hard)
                                <br>
                                <br>Unit 4, Session 2: Contraception
                                <br>Activity 3: Ringerangeroo and Doodle-Dasher, Too, 20 minutes
                                <br>(Easy)
                                <br>
                                <br>Unit 4, Session 2: Contraception
                                <br>Activity 7: Behavioral Contraception, 10 minutes
                                <br>(Hard)
                                <br>
                                <span style="color:#9acd57">
                                <br>Kayla (taken)
                                <br>Unit 4, Session 3: Unplanned Pregnancy and Parenting
                                <br>Activity 3: Parenting
                                <br>(Medium)
                                <br>
                                <br>American School Paris (taken)
                                <br>Unit 5, Session 1: Dating Violence
                                <br>Activity 4: Healthy/Unhealthy/Abusive, 20 minutes
                                <br>(Medium)
                                <br>
                                <br>Nia/Educa Health (taken)
                                <br>Unit 5, Session 5: Heartbreaker
                                <br>Activity 4: Once Upon A Time…, 15 minutes
                                <br>(Easy)</span>
                                </p>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/c-hs/UNHUSHED_MS_U-00_S-00_FR-My-Relationship-with-Online-Learning_v01.docx') }}" download="UNHUSHED_MS_U-00_S-00_FR-My-Relationship-with-Online-Learning_v01.docx" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                FR: My Relationship with Online Learning</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-07_FR-AgeAppropriateGuidelinesForTeachingSexEdOnline_v02.pdf') }}" download="UNHUSHED_TSEO_S-07_FR-AgeAppropriateGuidelinesForTeachingSexEdOnline_v02.pdf" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                FR: Age Appropriate Guidelines for Teaching Sex Ed Online</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">4.</div>
                            <div class="col-11">
                                Review the <a href="{{ url($path.'/middle-school') }}" target="_blank">Unit 4: Safer Sex Decisions and Unit 6: Wrap Up</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Other Helpful Stuff</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.youtube.com/watch?v=cAUDKEI4QKI" target="_blank" class="pr-1">
                                <i class="fab fa-youtube fa-fw fa-lg align-middle"></i>Youtube Video: What It's Like To Be Intersex</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://youtube.com/playlist?list=PLxB_mPwX1ajsi0-wmYXRUC1ooxZFgd4p1" target="_blank" class="pr-1">
                                <i class="fab fa-youtube fa-fw fa-lg align-middle"></i>Youtube Playlist: Trans dude + Nonbinary people</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://imfromdriftwood.com/" target="_blank" class="pr-1">
                                <i class="fas fa-browser fa-fw fa-lg align-middle"></i>Website: I'm from Driftwood - LGBTQ Story Archive</a>
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
