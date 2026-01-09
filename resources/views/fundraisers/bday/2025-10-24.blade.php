@extends('layouts.app')
@section('content')
<section>

    <div class="container">
        <!-- THERMOMETER -->
        @include('fundraisers.bday.thermometer2025')
        
        <!-- EVENT HEADER-->
        <span id="2022" class="anchor">
            <div class="box1">
                <div class="row">
                    <div class="col-auto center">
                        <img class="img-s" src="{{ asset('img/octos/ollie-bday.png') }}" alt="Ollie holding four balloons.">
                    </div>
                    <div class="col-auto p-3 center">
                        <p class="diazo">We're turning 8!</p>
                        <hr>
                        <p style="max-width: 400px; text-align: justify;">UN|HUSHED is turning 8 years old on, October 24th, 2025.
                        </p>
                    </div>
                </div>
            </div>
        </span>
        
        <!-- ABOUT -->
        <span id="about" class="anchor">
            <div class="box3">
                <div class="bday-txt">
                    <p><span class="sketchnote-square">A little bit about us...</span>
                        <br/>Here at UN|HUSHED our mission is <span class="rollerscript-rough" style="color: #9acd57;">Breaking the silence surrounding human sexuality</span>.
                        We strive to achieve this mission by creating, reviewing, facilitating, and training on curricula for people of all ages (from infancy through older adulthood), academic levels,
                        and professional backgrounds. We are a tiny 501(C)(3) nonprofit based in Austin, TX, with team members across the globe.
                        <a href="{{ url($path.'/about') }}">You can check out more information about the org here.</a>
                    </p>
                    <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/874079808?badge=0&amp;autopause=0&amp;quality_selector=1&amp;progress_bar=1&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="UN|HUSHED's 6th birthday!"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
                </div>
                <div class="bday-txt">
                    <p>Last year we threw a kick ass event in person and we loved seeing so many of you there! 
                        This year we’re hopping to hear from you! Email us a video, audio message, or just a good ol' fashioned 
                        sentance or two telling us why UN|HUSHED matters to you. At the end of the year we're going to put
                        those all toghether and play them at our next in person event, which rumor has it, might be in Feburary 
                        of 2026, so stay tuned!</p>
                </div>
            </div>
        </span>
    </div>

    <style>
        /*iPhone*/
        @media screen and (max-width: 780px){
            .box1 {
                margin: 0 auto;
                padding: 5px;
                width: 100%;
            }
            .box3 {
                margin: 0 auto;
                padding: 5px;
                width: 100%;
            }
            .center{
                text-align: center;
                margin: 0 auto;
            }
        }
        /*desktop*/
        @media all and (min-width: 780px) {
            .box1 {
                margin: 80px auto 0;
                padding: 5px 20px;
                width: 65%;
            }
            .box3 {
                margin: 80px auto 0;
                padding: 5px 20px;
                width: 65%;
            }
            .right {
                float: right;
            }
            .left {
                float: left;
            }
        }
        .bday-pic {
            max-width: 800px;
            width: 100%;
            height: auto;
        }
        .bday-txt {
            font-size: 16px;
            padding: 10px 30px 30px 30px;
            text-align: justify;
        }
        .box1 {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #e3e7f1;
        }
        .box3{
            margin: 10px auto;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #e3e7f1;
        }
        .anchor {
            scroll-margin-top: 125px;
        }
    </style>
    <script>
        //Registration form
        $(document).ready(function(){
            $("#submit").on('click', function(e){
                e.preventDefault();
                _url = $("#regform").attr('action');
                _data = $("#regform").serialize();
                $.ajax({
                    url: _url,
                    type: 'post',
                    data: _data,
                    success: function(response){

                        console.log(response);
                        if(response.error === true){
                            alert(response.message);
                        }else{
                            document.location = _url;
                        }
                    },
                    fail: function(){ alert("Error"); }
                });
            })
        })
    </script>

</section>
@endsection
