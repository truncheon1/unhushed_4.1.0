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
                <span style="font-weight: bold;color:#9acd57">Human Sexuality for Child Welfare Providers</span>
            </div>
        </div>

        <!-- Training Card -->
        <div class="row">
            <div class="col-8 pb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!--
                            <div class="col-12 text-center align-self-center">
                                <a href="https://drive.google.com/file/d/13UcAwaR3Z0xotPCkW6PdT5M7YCkpuR5r/view?usp=sharing" target="_blank"><img src="/img/trainings/single/thumb-hcwp.png" alt="Play Video"></a>
                            </div>
                            -->
                            <div class="col-12 pt-3">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session:</span> Human Sexuality for Child Welfare Providers</b>
                                <!--
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday May 17th, 2021.</span>
                                <br><b>This four hour session is a solid foundation for talking with youth about human sexuality.</b>
                                Follow along while Karen Rayne, PhD, CSE and Monica Faulkner, PhD, LMSW walk you through technics and skills that will benefit the youth you interact with in life changing ways.</p>
                            -->
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
                                <a href="{{ asset('pdfs/t-hcwp/UNHUSHED_Intro-To-Sexuality-Education-A-Handbook-for-Child-Welfare-Providers.pdf') }}"
                                download="UNHUSHED_Intro-To-Sexuality-Education-A-Handbook-for-Child-Welfare-Providers.pdf" class="px-1">
                                <i class="fal fa-book fa-fw fa-lg align-middle"></i></a>
                                An Introduction to Human Sexuality: Child Welfare Providers
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/t-hcwp/UNHUSHED_H-MedicalVisitChecklist_v02.pdf') }}"
                                download="UNHUSHED_H-MedicalVisitChecklist_v02.pdf" class="px-1">
                                <i class="fas fa-file-alt fa-fw fa-lg align-middle"></i></i></a>
                                Handout: Medical Checklist
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <!--
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="https://utexas.qualtrics.com/jfe/form/SV_d6ivDr5QURAPS0C" class="px-1" target="blank">
                                <i class="fas fa-poll fa-fw fa-lg align-middle"></i></i></a>
                                Exit Feedback Survey (required for CEs)
                            </div>
                            -->
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






