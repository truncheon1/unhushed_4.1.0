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
                    <a class="px-1"><i class="fas fa-play-circle fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/middle-school-curriculum-training/2') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/middle-school-curriculum-training/3') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/middle-school-curriculum-training/4') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/middle-school-curriculum-training/5') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="https://drive.google.com/file/d/1O04Bk3xBXQbq6aVExEKIlwX6LnFEzC6q/view?usp=sharing" target="_blank"><img src="/img/trainings/curricula/ms.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 1:</span> Introduction (to each other and UN|HUSHED)</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday June 28th, 2021.</span>
                                <br>Day 1 of the training includes introductions to Karen Rayne, PhD, CSE, Jessica Smarr, MPH, and Nyk Rayne, as well as the UN|HUSHED middle school curriculum and the backend of the website you will use to facilitate the materials. We discussed boundaries and values, and how those directly and indirectly affect the way you facilitate.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 1 Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://us06web.zoom.us/j/84183494396?pwd=TXFlTFNnUmJIY3hvVytVL3NDV2xZQT09" target="_blank" class="pr-1">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i></a>
                                <a href="https://us06web.zoom.us/j/84183494396?pwd=TXFlTFNnUmJIY3hvVytVL3NDV2xZQT09" target="_blank">
                                Zoom: Same for every live session!</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ url($path.'/middle-school') }}" target="_blank" class="pr-1">
                                    <i class="fas fa-chalkboard fa-fw fa-lg align-middle"></i></a>
                                <a href="{{ url($path.'/middle-school') }}" target="_blank">
                                Quick Link to the middle school curriculum page</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.menti.com/" target="_blank" class="pr-1">
                                    <i class="fas fa-poll fa-fw fa-lg align-middle"></i></a>
                                <a href="https://www.menti.com/" target="_blank">
                                Quick Link to Mentimeter</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1B_2qBToPKN4sonuqcb8RylxF76c93r1hu51qivaHobA/edit" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                <a href="https://docs.google.com/document/d/1B_2qBToPKN4sonuqcb8RylxF76c93r1hu51qivaHobA/edit" target="_blank">
                                Group Agreements</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1qb9Djh_h8g_QLDxEV8DdcO49V2nXgpoKc43vbj3XnHo/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i></a>
                                <a href="https://docs.google.com/presentation/d/1qb9Djh_h8g_QLDxEV8DdcO49V2nXgpoKc43vbj3XnHo/edit?usp=sharing" target="_blank">
                                Values, Boundaries & Sexuality Education</a>
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
                                Review the <a href="{{ url($path.'/middle-school') }}" target="_blank">Facilitator Guide and Unit 1: The Beginning</a>. You'll have elements to review every night—makeing sure you do this thoroughly will allow you to get so much more out of the training!
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                <a href="https://www.psychologytoday.com/us/blog/modern-day-slavery/201707/trauma-informed-care-and-why-it-matters" class="pr-1" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Please read this article from Pyschology Today.</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/c-hs/Guide-toTrauma-Informed-Sex-Education.pdf') }}" download="Guide-toTrauma-Informed-Sex-Education.pdf" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                Please read this pdf "A Guide to Trauma Informed Sex Education" created and published by Cardea.</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Other Helpful Stuff</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.amazon.com/Reproductive-Justice-Introduction-Vision-Century/dp/0520288203/ref=asc_df_0520288203/?tag=hyprod-20&linkCode=df0&hvadid=312403172530&hvpos=&hvnetw=g&hvrand=13318298503562131418&hvpone=&hvptwo=&hvqmt=&hvdev=c&hvdvcmdl=&hvlocint=&hvlocphy=9028303&hvtargid=pla-302875651389&psc=1&tag=&ref=&adgrpid=60300244057&hvpone=&hvptwo=&hvadid=312403172530&hvpos=&hvnetw=g&hvrand=13318298503562131418&hvqmt=&hvdev=c&hvdvcmdl=&hvlocint=&hvlocphy=9028303&hvtargid=pla-302875651389" target="_blank" class="pr-1">
                                <i class="fas fa-shopping-cart fa-fw fa-lg align-middle"></i></a>
                                <a href="https://www.amazon.com/Reproductive-Justice-Introduction-Vision-Century/dp/0520288203/ref=asc_df_0520288203/?tag=hyprod-20&linkCode=df0&hvadid=312403172530&hvpos=&hvnetw=g&hvrand=13318298503562131418&hvpone=&hvptwo=&hvqmt=&hvdev=c&hvdvcmdl=&hvlocint=&hvlocphy=9028303&hvtargid=pla-302875651389&psc=1&tag=&ref=&adgrpid=60300244057&hvpone=&hvptwo=&hvadid=312403172530&hvpos=&hvnetw=g&hvrand=13318298503562131418&hvqmt=&hvdev=c&hvdvcmdl=&hvlocint=&hvlocphy=9028303&hvtargid=pla-302875651389" target="_blank">
                                Reproductive Justice by Loretta Ross & Rickie Solinger</a>
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
                    Video will be up tomorrow.
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
