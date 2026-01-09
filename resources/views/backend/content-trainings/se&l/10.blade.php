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
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/5') }}"  class="px-1"><i class="fa-solid fa-clock-five fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/6') }}"  class="px-1"><i class="fa-solid fa-clock-six fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/7') }}"  class="px-1"><i class="fa-solid fa-clock-seven fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/8') }}"  class="px-1"><i class="fa-solid fa-clock-eight fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/9') }}"  class="px-1"><i class="fa-solid fa-clock-nine fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fa-solid fa-clock-ten fa-2x fa-fw"></i></a>
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
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 10:</span> Sex work/Commercial Sexual Exploitation of Minors</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on January 20th, 2022.</span>
                                <br>Day 10 of the training covers what used to be called Sex Trafficking and is now more accurately called Sex work/Commercial Sexual Exploitation of Minors.
                                Once again we have far more than we could possibly cover in one session, but we're going to try!</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mx-5">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://ojjdp.ojp.gov/mpg/literature-review/csec-sex-trafficking.pdf" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Article: Commercial Sexual Exploitation of Children and Sex Trafficking</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.theatlantic.com/magazine/archive/2022/01/children-sex-trafficking-conspiracy-epidemic/620845/" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Article: The Great (Fake) Child-Sex-Trafficking-Epidemic</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://www.nswp.org/sites/nswp.org/files/pb_impact_of_end_demand_on_women_sws_nswp_-_2018.pdf" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Article: The Impact of ‘End Demand’ Legislation on Women Sex Workers</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://pubmed.ncbi.nlm.nih.gov/32744171/" target="_blank" class="pr-1">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i>
                                Article: Harms of third party criminalisation under end-demand legislation: undermining sex workers' safety and rights</a>
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
                    <iframe src="https://player.vimeo.com/video/764597762?h=171432e851" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
