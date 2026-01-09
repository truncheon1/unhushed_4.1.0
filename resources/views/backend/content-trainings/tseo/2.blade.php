@extends('layouts.app')
@section('content')
@include('backend.content-trainings.tseo.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}"> Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">Teaching Sex Ed Online</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row mx-auto">
            <div class="col-10 mx-5">
                <div class="nav-box">
                    <h3>Teaching Sex Ed Online</h3>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/1') }}"  class="px-1"><i class="fa-duotone fa-circle-1 fa-2x fa-fw"></i></a>
                        <a class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/3') }}"  class="px-1"><i class="fa-solid fa-circle-3 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/4') }}"  class="px-1"><i class="fa-solid fa-circle-4 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/5') }}"  class="px-1"><i class="fa-solid fa-circle-5 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/6') }}"  class="px-1"><i class="fa-solid fa-circle-6 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/7') }}"  class="px-1"><i class="fa-solid fa-circle-7 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/8') }}"  class="px-1"><i class="fa-solid fa-circle-8 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/9') }}"  class="px-1"><i class="fa-solid fa-circle-9 fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/10') }}" class="px-1"><i class="fas fa-stop-circle fa-2x fa-fw"></i></a>
                        <a href="{{ url($path.'/dashboard/trainings/bonus-tech-session') }}"        class="px-1"><i class="fas fa-plus-circle fa-2x fa-fw"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mx-auto">
                <div class="col-10 mx-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row px-4">
                                <div class="col-lg-3 col-sm-12 text-right align-self-center">
                                    <a href="#video2" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb2v1.png"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 2v2: Maintaining a trauma-informed lens</span>
                                    <br><span style="font-weight:light;font-size:12px">recorded on Thursday September 3rd, 2020.</span>
                                    <br><b>We take a second look at maintaining a trauma-informed lens in the online space.</b>
                                    We must make online sexuality education trauma informed. Some problems include: participants not having a safe space to receive or participate in this education given their family dynamics, participants being triggered without facilitator awareness, other people participating or engaging with the class in ways that the facilitator cannot see or control, participants using the class as a springboard for going down a YouTube or Google thread because they cannot ask the facilitator a question, and more.
                                    Some ways to address these issues include: robust group agreements that you revisit every single class, having two facilitators available at all times, and letting participants know what content you will be discussing beforehand. Use all of the tools you used in F2F environments: be aware, be alert, and directly address anything that you are worried about. Taking the time to have a trauma-informed approach is not taking away from the curriculum, it’s adding to it.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row px-4">
                                <div class="col-lg-3 col-sm-12 text-right align-self-center">
                                    <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb2v1.png"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify pr-5">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 2v1: Maintaining a trauma-informed lens</span>
                                    <br><span style="font-weight:light;font-size:12px">recorded on Thursday April 2nd, 2020.</span>
                                    <br><b>Maintaining a trauma-informed lens is even more critical in the online space, and much more difficult to do.</b>
                                    Once we’ve settled down to the business of teaching sex ed online, we must have a conversation about how we will make this endeavor trauma informed.
                                    Some problems include: participants not having a safe space to receive or participate in this education given their family dynamics, participants being triggered without facilitator awareness, other people participating or engaging with the class in ways that the facilitator cannot see or control, participants using the class as a springboard for going down a YouTube or Google thread because they cannot ask the facilitator a question, and more.
                                    Specific tips and tricks on how to address these issues coming soon – for now, be aware, be alert, and directly address anything that you are worried about.
                                    Working towards a trauma-informed approach is not taking away from the curriculum it’s adding to it.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row px-4">
                                <div class="col-12 text-center">
                                    <b><span style="color:#9acd57">Session Resources</span></b>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-02_FR-TeachingOnlineGroupAgreements_v01.pdf') }}" download="UNHUSHED_TSEO_S-02_FR-TeachingOnlineGroupAgreements_v01.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Facilitator Resource: Online Group Agreements (created with participants during session 2v1)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-02_FR-TraumaInTheOnlineClassroom_v01.pdf') }}" download="UNHUSHED_TSEO_S-02_FR-TraumaInTheOnlineClassroom_v01.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Facilitator Resource: Trauma In The Online Classroom (version 1 created by the unhushed team)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://mailchi.mp/unhushed/were-excited-to-see-you-tomorrow-4378831" class="px-1">
                                    <i class="far fa-envelope fa-fw fa-lg align-middle"></i></a>Email sent out the morning of the original session.
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://starr.org/product/covid-19-response-reintegration-and-post-disruption-recovery-a-trauma-informed-resilience-focused-approach/" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    COVID-19 Response, Reintegration, and Post-Disruption Recovery: A Trauma-Informed, Resilience-Focused Approach</a>
                                    <ul class="ml-3">
                                        <li>This is a $49 ebook. We've read the first 12 pages and it looks pretty solid.</li>
                                        <li>We may update this once we've had time to purchase and review it fully.</li>
                                        <li>The price has dropped from $99 when we first looked it over.</li>
                                    </ul>
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
                        <iframe src="https://player.vimeo.com/video/404746304" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal video 2 -->
        <div id="video2" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                        <iframe src="https://player.vimeo.com/video/522388157" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $("#video1").on('hidden.bs.modal', function (e) {
                $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
            });
            $("#video2").on('hidden.bs.modal', function (e) {
                $("#video2 iframe").attr("src", $("#video2 iframe").attr("src"));
            });
        </script>
    </section>
@endsection





