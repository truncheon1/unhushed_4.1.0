@extends('layouts.app')

@section('content')
<section>

    <div class="container mb-5">
        <!-- EVENT HEADER-->
        <div class="box1">
            <div class="row">
                <div class="col-auto center">
                    <img src="{{ asset('img/octos/ollie-donate.png') }}" alt="Ollie holding four balloons.">
                </div>
                <div class="col-auto p-3 center">
                    <p class="diazo">We'd love your help!</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:420px">We're trying to bring our Board of Directors together in person for the first time ever!
                    </p>
                </div>
            </div>
        </div>
        <!-- THERMOMETER -->
        @include('fundraisers.retreat.thermometer')

        <!-- EVENT 1 -->
        <div class="box3">
            <div class="bday-txt">
                <p><span class="sketchnote-square">A little bit about us...</span>
                    <br/>Here at UN|HUSHED our mission is <span class="rollerscript-rough" style="color: #9acd57;">Breaking the silence surrounding human sexuality</span>.
                    <br/>We strive to achieve this mission by creating, facilitating, and training on curricula for people of all ages (from infancy through older adulthood), academic levels,
                    and professional backgrounds. We are a tiny 501(C)(3) nonprofit based in Austin, TX, and we have only existed for four years (five this October!).
                    <br/><a href="{{ url($path.'/educators/about') }}">You can check out more information about the org here.</a>
                </p>
            </div>
        </div>

        <!-- EVENT 1 -->
        <div class="box3">
            <img class="bday-pic" src="{{ asset('img/banners/bod.png') }}" alt="Ollie holding four balloons under a banner that reads 'It's our birthday!' in front of falling confetti'.">
            <div class="bday-txt">
                <p>
                    <span class="sketchnote-square">About our Board of Director's Retreat</span>
                    <br><i class="fas fa-calendar-alt"></i> April 22-24, 2022
                </p>
                <p>
                    As you know, two of our four years existence have been during a global pandemic.
                    This means our Board of Directors have never been together in the same room in person.
                    If you've ever been on a team (especially of volunteers) you know that the connection built in person makes working remotely a more grounded and productive experience.
                    So our goal is to bring our board together for a two day retreat at a small farm home in Dripping Springs, TX.
                    There we will focus on team building and strategic planning for the organization.
                    We're trying to raise enough funds to pay for food, team building activities, travel, and lodging. If you're able to give anything at all we'd be extremely grateful.
                    If you have a donation in kind please email us at <a href="mailto:info@unhushed.org">info@unhushed.org</a>.
                </p>
                <div class="row">
                    <div class="mx-auto p-1">
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input name="donate" id="donate" class="btn btn-secondary" type="submit" value="DONATE" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                            <input type="hidden" name="cmd" value="_s-xclick" >
                            <input type="hidden" name="hosted_button_id" value="VFH5ZPLVX5352" >
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <style>
        /*iPhone*/
        @media screen and (max-width: 780px){
            .box1 {
                margin: 50px auto 0;
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
            width: 70%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #e3e7f1;
        }
        .box3{
            margin: 10px auto;
            background-color: #fff;
            width: 70%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #e3e7f1;
        }
    </style>
    <script>
        //Team field
        function sexTrivia(nameSelect)
        {
            if(nameSelect){
                admOptionValue = document.getElementById("teamOption").value;
                if(admOptionValue == nameSelect.value){
                    document.getElementById("sexTrivia").style.display = "block";
                }
                else{
                    document.getElementById("sexTrivia").style.display = "none";
                }
            }
            else{
                document.getElementById("sexTrivia").style.display = "none";
            }
        }
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
