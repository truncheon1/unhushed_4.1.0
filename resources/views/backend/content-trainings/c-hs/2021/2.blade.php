@extends('layouts.app')
@section('content')
@include('backend.content-trainings.c-hs.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="nav-box">
                    <h3>The High School Curriculum</h3>
                    <a href="{{ url($path.'/high-school-curriculum-training/1') }}" class="px-1"><i class="fas fa-circle fa-2x fa-fw"></i></a>
                    <a class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/high-school-curriculum-training/3') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/high-school-curriculum-training/4') }}" class="px-1"><i class="fas fa-arrow-circle-right fa-2x fa-fw"></i></a>
                    <a href="{{ url($path.'/high-school-curriculum-training/5') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="col-8 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                <a href="https://drive.google.com/file/d/1-zDX0O2Oh9izafF4GScEv-LzIuDmdLQg/view?usp=sharing" target="_blank"><img src="/img/trainings/curricula/hs.png" alt="Play Video"></a>
                            </div>
                            <div class="col-lg-8 col-sm-12 text-justify">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Day 2:</span> Facilitation Intro</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on Monday June 15th, 2021.</span>
                                <br>We discussed facilitation vs teaching with a google doc activity and slideshow. Anonymous Questions! How to answer them and why. After the break we talked through trauma informed sex ed. Lastly we covered Identity, Values, & Sexuality, Gender Identity & Roles, and the 3 Elements of Orientation.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Day 2 Resources</span></b>
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
                                <a href="https://docs.google.com/document/d/1BC1iMC5f6GjdwsmzPPH7X4JtJz6aMsAq7D7tSQpzCYs/edit" target="_blank" class="px-1">
                                <i class="fa fa-link fa-fw fa-lg align-middle"></i>
                                Google Doc: Teacher vs Facilitator</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1beQF2svqXf3aNJ6ZVKUmj8WnfzG71iTF/edit#slide=id.gdfe1d0954e_3_112" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i></a>
                                <a href="https://docs.google.com/presentation/d/1beQF2svqXf3aNJ6ZVKUmj8WnfzG71iTF/edit#slide=id.gdfe1d0954e_3_112" target="_blank">
                                Do's & Don'ts as a Facilitator</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="{{ asset('pdfs/c-hs/UNHUSHED_Things-we-do_v02.pdf') }}" download="UNHUSHED_Things-we-do_v02.pdf" class="px-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                FR: Things We Do</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/1uvJjsvfMfrZ6AHTDvUj1cIl47WLCAcBH/edit#slide=id.ge065f113b6_1_84" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i></a>
                                <a href="https://docs.google.com/presentation/d/1uvJjsvfMfrZ6AHTDvUj1cIl47WLCAcBH/edit#slide=id.ge065f113b6_1_84" target="_blank">
                                PP: Anonymous questions</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="https://docs.google.com/presentation/d/16Vut_DO3aRv7wltPIucvubG_Ca1pAFe2/edit#slide=id.gdfe1d0954e_3_112" target="_blank" class="pr-1">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i></a>
                                <a href="https://docs.google.com/presentation/d/16Vut_DO3aRv7wltPIucvubG_Ca1pAFe2/edit#slide=id.gdfe1d0954e_3_112" target="_blank">
                                PP: Trauma-Informed Sex Ed</a>
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
                                Review the <a href="{{ url($path.'/high-school') }}" target="_blank">Unit 2: Relationships</a>.
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/c-hs/UNHUSHED_MS_U-00_S-00_FR-Constructing-an-UNHUSHED-program_v01.docx') }}" download="UNHUSHED_MS_U-00_S-00_FR-Constructing-an-UNHUSHED-program_v01.docx" class="pr-1">
                                <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                                FR: Constructing an UNHUSHED program</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Other Helpful Stuff</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col">
                                <a href="http://www.facilitationmagic.com/" target="_blank" class="pr-1">
                                <i class="fas fa-shopping-cart fa-fw fa-lg align-middle"></i></a>
                                <a href="http://www.facilitationmagic.com/" target="_blank">
                                Facilitation Magic by Sam Killermann & Meg Bolger</a>
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
