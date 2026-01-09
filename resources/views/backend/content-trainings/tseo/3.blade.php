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
                        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/2') }}"  class="px-1"><i class="fa-duotone fa-circle-2 fa-2x fa-fw"></i></a>
                        <a class="px-1"><i class="fa-duotone fa-circle-3 fa-2x fa-fw"></i></a>
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
                                <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                    <a href="#video2" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb1v2.png"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 3v2: Participant reported abuse</span>
                                    <br><span style="font-weight:light;font-size:12px">recorded on Thursday October 1st, 2020.</span>
                                    <br><b>There are specific and important ways to respond if your participants disclose in a digital environment that they are experiencing violence in their real lives.</b>
                                    Your reactions must be based in a trauma-informed space, take legal requirements into consideration, and be aware of the increased risk of violence when families are quarantined together.
                                    Being responsive, available, and attentive to participants and the ways they need you to show up is important.
                                    Thinking through potential disclosures and your responses to them will help you prepare.</p>
                                </div>
                                <div class="col-lg-4 col-sm-12"></div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <b><span style="color:#9acd57">Guest Speaker's Resources</span></b>
                                </div>
                                <div class="col-lg-4 col-sm-12"></div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-03_H-WhatCanIDo_v01.pdf') }}" download="UNHUSHED_TSEO_S-03_H-WhatCanIDo_v01.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: What Can I Do? (Created by Scott J Patterson, MA, LPCC, LADAC, CADS with UNHUSHED)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-lg-4 col-sm-12"></div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-03_H-WhatIsAbuse_v01.pdf') }}" download="UNHUSHED_TSEO_S-03_H-WhatIsAbuse_v01.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: What Is Abuse? (Created by Scott J Patterson, MA, LPCC, LADAC, CADS with UNHUSHED)
                                </div>
                                <div class="col-12">&nbsp;</div>
                            </div>
                            <hr>
                            <div class="row px-4">
                                <div class="col-lg-4 col-sm-12 text-center align-self-center">
                                    <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/tseo/thumb3v1.png"></a>
                                </div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <p><span style="color:#9acd57;font-weight:bold;font-size:16px">Session 3v1: Participant reported abuse</span>
                                    <br><span style="font-weight:light;font-size:12px">recorded on Tuesday April 7th, 2020.</span>
                                    <br><b>If your participants share with you that they’re in an abusive home situation, the standard response teams are considered essential services and are still available.</b>
                                    Households that are prone to violence are in a freefall right now, with increased stress and proximity and dramatically decreased resources.
                                    Abuse and neglect numbers are expected to rise and some agencies and hospitals have already seen that rise start.
                                    Given the personal nature of sexuaity education content, you may be one of the first people a young person reaches out to if they are experiencing abuse or neglect at home.
                                    You should reach out to the same set of resources that you would have had this outcry happened in person.
                                    While they may be overtaxed at this time, they are still working and still available to you and your participants.</p>
                                </div>
                                <div class="col-lg-4 col-sm-12"></div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <b><span style="color:#9acd57">Guest Speaker's Resources</span></b>
                                </div>
                                <div class="col-lg-4 col-sm-12"></div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-03_PP-SAFE Guest BarriRosenbluth.PPTX') }}" download="UNHUSHED_TSEO_S-03_PP-SAFE Guest BarriRosenbluth.PPTX" class="px-1">
                                    <i class="fas fa-presentation fa-fw fa-lg align-middle"></i></a>
                                    PowerPoint: Barri Rosenbluth from SAFE
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-lg-4 col-sm-12"></div>
                                <div class="col-lg-8 col-sm-12 text-justify">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_TSEO_S-03_H-SAFE Relationship First Aid.pdf') }}" download="UNHUSHED_TSEO_S-03_H-SAFE Relationship First Aid.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: SAFE Relationship First Aid
                                </div>
                                <div class="col-12">&nbsp;</div>
                            </div>
                            <hr>
                            <div class="row px-4">
                                <div class="col-12 text-center">
                                    <b><span style="color:#9acd57">Session Resources</span></b>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-03_S-01_H-Columns&Shadows_v01.pdf') }}" download="UNHUSHED_MS_U-03_S-01_H-Columns&Shadows_v01.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: Columns & Shadows (Taken from Middle School Unit-03 Session-01 and created for our
                                    <br><span class="pl-5">curricula by UN|HUSHED's Karen Rayne, PhD, CSE and
                                    <a href="{{ url('https://www.itspronouncedmetrosexual.com/edugraphics/') }}" target="_blank">it's pronounced METROsexual</a>'s Sam Killerman.)</span>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-05_S-01_H-SteppingIn_v02.pdf') }}" download="UNHUSHED_MS_U-05_S-01_H-SteppingIn_v02.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: Stepping In (Taken from Middle School Unit-05 Session-01)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-05_S-01_H-TeenDatingViolenceFacts_v02.pdf') }}" download="UNHUSHED_MS_U-05_S-01_H-TeenDatingViolenceFacts_v02.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: Teen Dating Violence Facts (Taken from Middle School Unit-05 Session-01)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-05_S-01_H-TeenDatingViolenceTypes_v02.pdf') }}" download="UNHUSHED_MS_U-05_S-01_H-TeenDatingViolenceTypes_v02.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: Teen Dating Violence Types (Taken from Middle School Unit-05 Session-01)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-05_S-01_H-TeenDatingViolenceWarning_v02.pdf') }}" download="UNHUSHED_MS_U-05_S-01_H-TeenDatingViolenceWarning_v02.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: Teen Dating Violence Warning (Taken from Middle School Unit-05 Session-01)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="{{ asset('pdfs/t-tseo/UNHUSHED_MS_U-05_S-03_H-GettingHelp_v02.pdf') }}" download="UNHUSHED_MS_U-05_S-03_H-GettingHelp_v02.pdf" class="px-1">
                                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i></a>
                                    Handout: Getting Help (Taken from Middle School Unit-05 Session-03)
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://mailchi.mp/unhushed/were-excited-to-see-you-tomorrow-4385911" class="px-1">
                                    <i class="far fa-envelope fa-fw fa-lg align-middle"></i></a>Email sent out the morning of the original session.
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://journals.sagepub.com/doi/abs/10.1177/2167702619827018?journalCode=cpxa&" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    Article in SAGE journals about trigger warning effectiveness</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://www.inclusivetherapists.com/" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    Inclusive Therapists</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://www.loveisrespect.org/for-yourself/safety-planning/" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    Love Is Respect: safety plan tool</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://www.childwelfare.gov/topics/systemwide/laws-policies/state/" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    Mandatory Reporters State Statutes Search</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://www.npr.org/sections/coronavirus-live-updates/2020/04/06/827908402/global-lockdowns-resulting-in-horrifying-surge-in-domestic-violence-u-n-warns?utm_campaign=storyshare&utm_source=facebook.com&utm_medium=social" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    NPR Article on domestic violence surge due to Covid-19 stay at home orders</a>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <a href="https://www1.nyc.gov/assets/doh/downloads/pdf/imm/covid-sex-guidance.pdf" target="_blank" class="px-1">
                                    <i class="fas fa-link fa-fw fa-lg align-middle"></i></a>
                                    Sex During COVID (from NYC Health)</a>
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
                        <iframe src="https://player.vimeo.com/video/407826705" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
                        <iframe src="" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        <p style="text-center">Our video editing team is working hard to get this content out ASAP, but don't worry!
                        <br>Version 1 is up and ready to watch now. Just click the next video down.</p>
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





