@extends('layouts.app')
@section('content')
<section>

    <div class="container mb-5">
        <!-- THERMOMETER -->
        @include('fundraisers.bday.thermometer2022')
        
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
                </div>
            </div>
        </span>

        <!-- EVENT 1 
        <span id="BookPeople" class="anchor">
            <div class="box3">
                <img class="bday-pic" src="{{ asset('img/banners/bday2022-bookpeople.png') }}" alt="Ollie holding four balloons under a banner that reads 'It's our birthday!' in front of falling confetti'.">
                <div class="bday-txt">
                    <p>
                        <span class="sketchnote-square" >So Much to Celebrate!</span>
                        <br>Friday, 6pm-8pm CST in Austin, TX
                        <br><a target="_blank" href="https://calendar.google.com/event?action=TEMPLATE&tmeid=MW5vc3Zoa3M5ZXJoZ2ltbzI1dXAzbDBldm4gY182aWxqNW5ibGVudmg5YWk4N3NicWlwNHFib0Bn&tmsrc=c_6ilj5nblenvh9ai87sbqip4qbo%40group.calendar.google.com">
                        <i class="fas fa-calendar-alt"></i> Add this event to your calendar!</a> 
                        <br>All ages welcome.
                        <br><a href="#Register"><i class="fas fa-user-plus"></i> Register</a> now, for free.
                    </p>
                    <p>
                        Join us in celebrating our 5th birthday at BookPeople in Austin, TX. This event is a kid-oriented and family friendly party with interactive stations 
                        including story time with Jessica Smarr, coloring pages from our curriculum, a book signing with Karen Rayne, a silent auction and donation table, and a 
                        birthday cake from <a href="https://www.sugarmamasbakeshop.com/" target="_blank">Sugar Mama's Bakeshop</a>! Books authored by our team will be available for sale, including GIRL, Breaking the Hush Factor, Trans+, and 
                        more.
                    </p>
                </div>
            </div>
        </span>-->

        <!-- EVENT 2 -->
        <span id="Cheer-Up-Charlies" class="anchor">
            <div class="box3">
                <img class="bday-pic" src="{{ asset('img/banners/bday2.png') }}" alt="Ollie holding a microphone on a stage in front of a neon sign that reads 'Sex Ed Trivia'.">
                <div class="bday-txt">
                    <p><span class="sketchnote-square" >Sex Ed Trivia Night</span>
                    <br>Saturday, 6pm-10pm CST in Austin, TX
                    <br><a target="_blank" href="https://calendar.google.com/event?action=TEMPLATE&tmeid=NHRzNGg3YmdmZzhyZnJ0ZDUzYzF2NDZpcW0gY182aWxqNW5ibGVudmg5YWk4N3NicWlwNHFib0Bn&tmsrc=c_6ilj5nblenvh9ai87sbqip4qbo%40group.calendar.google.com">
                        <i class="fas fa-calendar-alt"></i> Add this event to your calendar!</a> 
                    <br>18+ only please.
                    <br><a href="#Register"><i class="fas fa-user-plus"></i> Register</a> now, for free.</p>
                    <p>
                        Join us for night two of our big birthday celebration! This adults only event is our 3rd annual sex ed trivia night, and the first in person! We'll have a 
                        silent auction and donation table, a birthday cake from <a href="https://www.sugarmamasbakeshop.com/" target="_blank">Sugar Mama's Bakeshop</a>, and some interactive games you'll really lol at! But really, because we'll
                        be irl.
                    </p>
                </div>
            </div>
        </span>

        <!-- STUFF -->
        <span id="Register" class="anchor">
            <div class="box3" >
                <div class="bday-txt pt-4">
                    <p class="sketchnote-square" >Free Event Registration</p>
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
            width: 60%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #e3e7f1;
        }
        .box3{
            margin: 10px auto;
            background-color: #fff;
            width: 60%;
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
