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
                <span style="font-weight: bold;color:#9acd57">Moving from Tik Toxic to Optimistic Masculinity</span>
            </div>
        </div>

        <!-- Training Card -->
        <div class="row">
            <div class="col-8 pb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/single/thumb-tiktoxic2.png" alt="Play Video"></a>
                            </div>
                            <div class="col-12 pt-3">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session:</span> Moving from Tik Toxic to Optimistic Masculinity</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Recorded on Tuesday, Sept. 23rd, 2025.</span>
                                <br>This asynchronous training is designed to provide educators and school administrators with information, activities, and tools 
                                necessary to identify and address these influencers and support boys and young men in moving away from their harmful perspectives.
                                </p>
                                <p>Start by watching the video. Feel free to pause to take breaks and/or ponder information shared. 
                                    We have provided the google slides (linked below) for you to use as reference. To complete the training, take a look at the scenarios 
                                    and discussion questions provided in the google slides.
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Session Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11 pl-1">
                                <a href="https://docs.google.com/presentation/d/1h8_Y-sZLxNiSNcs1WrYTBRsoeOauHD_R79p7PbIo95o/edit?usp=sharing" target="_blank">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i> Google Slides: </a>Tik Toxic to OptimisticMasculinity
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11 pl-1">
                                <a href="https://forms.gle/xpJgsBfVUC4didR56" class="px-1" target="blank">
                                <i class="fas fa-poll fa-fw fa-lg align-middle"></i></i> Survey:</a> Asynchronous Feedback
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11 pl-1">
                                <a href="https://vimeo.com/1093593511?share=copy#t=0" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i> Video:</a> The Future of Masculinity A Conversation between Karen Rayne, PhD, CSE and Corbin Knight-Dixon, MS.
                                <div class="row text-center">
                                    <div class="col-10 pt-2 mx-5 rounded" style="background-color:#f0f3fa">
                                        <iframe src="https://player.vimeo.com/video/1093593511?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" width="300" height="169" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" title="The Future of Masculinity"></iframe>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">4.</div>
                            <div class="col-11 pl-1">
                                <a href="https://blog.unhushed.org/you-can-do-better-than-andrew-tate/" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i> Blog Post:</a> You Can Do Better Than Andrew Tate
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">5.</div>
                            <div class="col-11 pl-1">
                                <a href="https://podcasters.spotify.com/pod/show/securingsexuality/episodes/40--Teaching-Kids-How-To-Unhush-e25sudg/a-a8707ir" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i> Podcast interview:</a> Karen Rayne and Jessica Smarr talk about the UN|HUSHED curricula and this training.
                                <div class="row text-center">
                                    <div class="col-10 pt-2 mx-5 rounded" style="background-color:#f0f3fa">
                                        <iframe src="https://podcasters.spotify.com/pod/show/securingsexuality/embed/episodes/40--Teaching-Kids-How-To-Unhush-e25sudg/a-a8707ir" height="102px" width="400px" frameborder="0" scrolling="no"></iframe>
                                    </div>
                                </div>
                                <div class="col-12">&nbsp;</div>
                            </div>
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
                    <iframe src="https://player.vimeo.com/video/1125321353?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        //Play video modal
        $("#video1").on('hidden.bs.modal', function (e) {
            $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
        });
        //Moveable modal
        $("#video1").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection
