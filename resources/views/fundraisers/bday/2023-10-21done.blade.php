@extends('layouts.app')
@section('content')
<section>

    <div class="container mb-5">
        <!-- THERMOMETER -->
        @include('fundraisers.bday.thermometer2023')
        
        <!-- EVENT HEADER-->
        <span id="2022" class="anchor">
            <div class="box1">
                <div class="row">
                    <div class="col-auto center">
                        <img class="img-s" src="{{ asset('img/octos/ollie-bday.png') }}" alt="Ollie holding four balloons.">
                    </div>
                    <div class="col-auto p-3 center">
                        <p class="diazo">We're turning 6!</p>
                        <hr>
                        <p style="max-width: 400px; text-align: justify;">UN|HUSHED is no longer a toddler of a nonprofit! We are a full fledged "big kid". 
                        Join us for our 4th annual sex ed trivia night the Saturday before we turn 6, October 21st, 2023.
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
                        <br/>We strive to achieve this mission by creating, reviewing, facilitating, and training on curricula for people of all ages (from infancy through older adulthood), academic levels,
                        and professional backgrounds. We are a tiny 501(C)(3) nonprofit based in Austin, TX, with team members across the globe.
                        <a href="{{ url($path.'/educators/about') }}">You can check out more information about the org here.</a>
                    </p>
                    <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/874079808?badge=0&amp;autopause=0&amp;quality_selector=1&amp;progress_bar=1&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="UN|HUSHED's 6th birthday!"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
                </div>
            </div>
        </span>

        <!-- EVENT 2 -->
        <span id="Cheer-Up-Charlies" class="anchor">
            <div class="box3">
                <img class="bday-pic" src="{{ asset('img/banners/bday2.png') }}" alt="Ollie holding a microphone on a stage in front of a neon sign that reads 'Sex Ed Trivia'.">
                <div class="bday-txt">
                    <p><span class="sketchnote-square">Sex Ed Trivia Night</span>
                    <br>Saturday, October 21st, 2023 9e/8c/7m/6p, 2hrs.
                    <br><a target="_blank" href="https://calendar.google.com/calendar/event?action=TEMPLATE&amp;tmeid=MXNiMHY1bzU4bW44MnB2bXJuZ2t0OWlkYzIgY182aWxqNW5ibGVudmg5YWk4N3NicWlwNHFib0Bn&amp;tmsrc=c_6ilj5nblenvh9ai87sbqip4qbo%40group.calendar.google.com">
                        <i class="fas fa-calendar-alt"></i> Add this event to your calendar!</a>
                    
                    <br>18+ only, we'll see you on zoom!</p>
                    <p>
                        Join us for our big birthday celebration! This adults only event is our 4th annual sex ed trivia night! Get your team together or come solo 
                        and we'll hook you up. We've got fun prizes, silly hosts, and questions that'll blow your mind!
                    </p>
                    <p><span class="sketchnote-square"><i class="fas fa-user-plus"></i> Register now, for free.</span>
                    <br><!-- added session 'bday' in Welcomer -->
                    <!-- added session 'bday' in Welcomer -->
                    Thanks for signing up!
                </div>
            </div>
        </span>
    </div>

    <style>
        /*iPhone*/
        @media screen and (max-width: 780px){
            .box1 {
                margin: auto 0;
                padding: 5px;
                width: 90%;
            }
            .box3 {
                margin: auto 0;
                padding: 5px;
                width: 90%;
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
                width: 70%;
            }
            .box3 {
                margin: 80px auto 0;
                padding: 5px 20px;
                width: 70%;
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
