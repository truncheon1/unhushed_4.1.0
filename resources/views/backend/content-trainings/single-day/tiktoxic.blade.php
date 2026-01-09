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
                <span style="font-weight: bold;color:#9acd57">Tik Toxic Masculinity in the Classroom</span>
            </div>
        </div>

        <!-- Training Card -->
        <div class="row">
            <div class="col-8 pb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center align-self-center">
                                <a href="#video1" width="200" height="113" data-bs-toggle="modal"><img src="/img/trainings/single/thumb-tiktoxic.png" alt="Play Video"></a>
                            </div>
                            <div class="col-12 pt-3">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session:</span> Tik Toxic Masculinity in the Classroom</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">Recorded on Wednesday, July 5th, 2023 11:00am to 2:00pm CST.</span>
                                <br>This asynchronous training is designed to provide educators and school administrators with information, activities, and tools 
                                necessary to identify and address these influencers and support boys and young men in moving away from their harmful perspectives.
                                </p>
                                <p>Start by watching the video either on your own, or as a group. Feel free to pause to take breaks and/or ponder information shared. 
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
                            <!--
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11 pl-1">
                                <a href="https://us06web.zoom.us/j/83702782648?pwd=OHdJTVl0cE9VbmMvdU9yMFI3d3lLUT09" target="_blank">
                                <i class="fas fa-video fa-fw fa-lg align-middle"></i> Zoom: </a>Same for every live session!
                            </div>
                            <div class="col-12">&nbsp;</div>
                            -->
                            <div class="col-1 text-right">1.</div>
                            <div class="col-11 pl-1">
                                <a href="https://docs.google.com/presentation/d/1hWssKw_lKwtamsXVUxQAobM7F3si0xcyZIvSVaHXwKQ/edit?usp=sharing" target="_blank">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i> Google Slides: </a>Tik Toxic Masculinity
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <!--
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11 pl-1">
                                <a href="https://forms.gle/u7AydYicmXMi68tj7" class="px-1" target="blank">
                                <i class="fas fa-poll fa-fw fa-lg align-middle"></i></i> Survey:</a> Final Feedback  
                            </div>
                            -->
                            <div class="col-1 text-right">2.</div>
                            <div class="col-11 pl-1">
                                <a href="https://docs.google.com/presentation/d/16uAQu_NF5g-OU2I7l4eom8QoaMxrIuCYiWQJ8hQ1jNg/edit?usp=sharing" class="px-1" target="blank">
                                <i class="fas fa-presentation fa-fw fa-lg align-middle"></i> Google Slides: </a>Scenarios & Discussion Questions  
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">3.</div>
                            <div class="col-11 pl-1">
                                <a href="https://blog.unhushed.org/you-can-do-better-than-andrew-tate/" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i> Blog Post:</a> You Can Do Better Than Andrew Tate
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1 text-right">4.</div>
                            <div class="col-11 pl-1">
                                <a href="https://podcasters.spotify.com/pod/show/securingsexuality/episodes/40--Teaching-Kids-How-To-Unhush-e25sudg/a-a8707ir" target="_blank">
                                <i class="fas fa-link fa-fw fa-lg align-middle"></i> Podcast interview:</a> Karen Rayne and Jessica Smarr talk about the UN|HUSHED curricula and this training.
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-10 pt-2 mx-5 rounded" style="background-color:#f0f3fa">
                                <iframe src="https://podcasters.spotify.com/pod/show/securingsexuality/embed/episodes/40--Teaching-Kids-How-To-Unhush-e25sudg/a-a8707ir" height="102px" width="400px" frameborder="0" scrolling="no"></iframe>
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
                    <iframe src="https://player.vimeo.com/video/854098438" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
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
