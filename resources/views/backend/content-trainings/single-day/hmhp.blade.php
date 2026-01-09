@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}">Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Human Sexuality for Mental Health Practitioners</span>
            </div>
        </div>

        <!-- Training Card -->
        <div class="row">
            <div class="col-8 pb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/single/thumb-hmhp.png" alt="Play Video"></a>
                            </div>
                            <div class="col-12 pt-3">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session:</span> Human Sexuality for Mental Health Practitioners</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Friday April 23rd, 2021.</span>
                                <br><b>This four hour session is a solid foundation for talking with clients about human sexuality.</b>
                                Follow along while Ryan Dillon, LPC walks you through technics and skills that will benefit your practice and clientel.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Session Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="https://zoom.us/j/93826594166?pwd=eU10MktDQzUyeTFMVnJXc0hweTJMQT09" target="_blank" class="px-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i></a> Zoom: link for live session!
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/t-hmhp/UNHUSHED_Intro-To-Sexuality-Education-A-Handbook-for-Mental-Health-Practitioners.pdf') }}"
                                download="UNHUSHED_Intro-To-Sexuality-Education-A-Handbook-for-Mental-Health-Practitioners.pdf" class="px-1">
                                <i class="fal fa-book fa-fw fa-lg align-middle"></i></a>
                                An Introduction to Human Sexuality: A Handbook for Mental Health Practitioners
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        @include('backend.content-trainings.pdf-instruct')
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row">
                    <div class="col-12 pb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="font-weight-bold" style="color:#9acd57;"><i class="fa-solid fa-circle-dollar-to-slot"></i> Thanks for watching!</p>
                                <p class="text-justify">Did you learn something new? Consider making a donation to help fund content like this.</p>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="paypal">
                                    <input type="hidden" name="cmd" value="_s-xclick" >
                                    <input type="hidden" name="hosted_button_id" value="VFH5ZPLVX5352" >
                                    <input name="submit" id="resend" class="btn btn-secondary text-center" type="submit" value="DONATE" data-toggle="button" aria-pressed="false" autocomplete="off">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class=" font-weight-bold" style="color:#9acd57;">Have a suggestion?</p>
                                <p class="text-justify">Email us at <a href="mailto:info@unhushed.org">info@unhushed.org</a> to suggest additional features or ask questions about your purchased products.</p>
                                <a href="mailto:info@unhushed.org"><i class="fa-solid fa-envelope fa-fw fa-2xl"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class=" font-weight-bold" style="color:#9acd57;">Want more?</p>
                                <p class="text-justify">Browse our ever growing selection of amazing trainings.</p>
                                <a href="{{ url($path.'/trainings') }}"><i class="fa-solid fa-cart-shopping fa-fw fa-2xl"></i></a>
                            </div>
                        </div>
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






