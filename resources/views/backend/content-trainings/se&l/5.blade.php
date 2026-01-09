@extends('layouts.app')
@section('content')
@include('backend.content-trainings.se&l.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Sex Ed & the Law</span>
            </div>
        </div>

        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>Sex Ed & the Law</h3>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/1') }}"  class="px-1"><i class="fa-solid fa-clock-one fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/2') }}"  class="px-1"><i class="fa-solid fa-clock-two fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/3') }}"  class="px-1"><i class="fa-solid fa-clock-three fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/4') }}"  class="px-1"><i class="fa-solid fa-clock-four fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-solid fa-clock-five fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/6') }}"  class="px-1"><i class="fa-solid fa-clock-six fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/7') }}"  class="px-1"><i class="fa-solid fa-clock-seven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/8') }}"  class="px-1"><i class="fa-solid fa-clock-eight fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/9') }}"  class="px-1"><i class="fa-solid fa-clock-nine fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/10') }}" class="px-1"><i class="fa-solid fa-clock-ten fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/11') }}" class="px-1"><i class="fa-solid fa-clock-eleven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/12') }}" class="px-1"><i class="fa-solid fa-clock-twelve fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row px-4">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/se&l/thumb.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 5:</span> Sexual Violence I</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on November 4th, 2021.</span>
                                <br>Day 5 of the training covers the first half of sexual violence.
                                This includes definitions of sexual assault and rape and a consideration of how consent is definited in law versus in sex education.
                                Remember you can leave the live session or pause the recording at any time.
                                There is a difference between "safe" and "brave" spaces. Please consider the possible triggers you may have during this content. </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1WSywFuDnNW8hMTCIB7EDOVZikkSSwt7QQukJaZWngLg/edit?usp=sharing" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i>
                                PP: Sexual Violence I</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/document/d/1dFivAmA2uEoBO_m3CwfexmH2dAbhhvZDFNKWZLQaoCw/edit" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Scenarios: Session 05 (view only for asynchronous participation)</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ asset('UNHUSHED_SE&L_FR-Consent-in-Law-vs-Sex-Ed_v01.pdf') }}" download="UNHUSHED_SE&L_FR-Consent-in-Law-vs-Sex-Ed_v01.pdf" class="px-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                FR: Consent in Law vs. Sex Ed</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Links to laws relating to sexual violence</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.guttmacher.org/state-policy/explore/sex-and-hiv-education" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Guttmacher: Content Requirements for Sex Ed</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://apps.rainn.org/policy/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                RAINN: Laws in Your State</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Links to statistics realted to sexual violence</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.rainn.org/statistics/criminal-justice-system" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Statistics of the Criminal Justice System</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.who.int/publications/i/item/9789240022256" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Intimate Partner Violence/Non-Partner Sexual Assault</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://pubmed.ncbi.nlm.nih.gov/26679304/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Meta Analysis of False Report Rates</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://pubmed.ncbi.nlm.nih.gov/30811278/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Attitudes: "Most false reports involve teens"</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://pubmed.ncbi.nlm.nih.gov/29195520/" target="_blank"><i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Police officer’s rape myth acceptance.</a>
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
                    <iframe src="https://player.vimeo.com/video/656701718?h=13833ee4e7" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
