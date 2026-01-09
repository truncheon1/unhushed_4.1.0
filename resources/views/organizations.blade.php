@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <!-- Welcome -->
        <div class="row wImg">
            <div class="col-md-6 col-sm-12 ms-auto desBox">
                <!-- <p style="font-size:20px; font-weight:bold; text-transform: uppercase;">EDUCATORS/FACILITATORS/ADMINISTRATION:</p> -->
                <p>We believe in the power and necessity of school based sexuality education. Our K-12 curriculum brings a wide range of topics including 
                    communication, consent, identity, relationships, and so much more.
                </p>
                <p><a href="{{ url($path.'/curricula') }}"><i>The UN|HUSHED curriculum</i></a> is medically accurate, inclusive, up-to-date, ready for out-of-the-box implementation, and aligns with many state, national, 
                    and international comprehensive sexuality <a href="{{ url($path.'/about#standards') }}">education standards.</a></p>
            </div>
            <div class="col-sm-12 phoneBox">
                <p><span style="font-weight:bold; text-transform: uppercase;">OUR MISSION IS TO:</span>
                    </br><span class="rollerscript-rough" style="font-size:25px; color:#9acd57;">Break the silence 
                    </br>surrounding human sexuality.</span></p>
                <p>We believe in the power and necessity of school based sexuality education. Our K-12 curriculum brings a wide range of topics including 
                    communication, consent, identity, relationships, and so much more.
                </p>
            </div>
        </div>

        <!-- DONATE -->
        <div class="row phone bg-white pb-4">
            <div class="col-auto mx-auto mt-4" style="text-align:center;">
                <span style="font-weight:bold; text-transform: uppercase;">Help sex ed save lives</span>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input name="submit" id="resend" class="btn btn-secondary" type="submit" value="DONATE" data-toggle="button" aria-pressed="false" autocomplete="off">
                    <input type="hidden" name="cmd" value="_s-xclick" >
                    <input type="hidden" name="hosted_button_id" value="VFH5ZPLVX5352" >
                </form>
            </div>
        </div>

        <!-- Curriculum -->
        <div class="row" style="padding: 50px 0 10px 0;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-book fa-fw"></i>UN|HUSHED K-12 CURRICULUM</h3>
            </div>
        </div>
        <div class="row justify-content-center align-top" style="padding-bottom:25px;">
            <div class="col-auto mx-auto my-auto phone" style="text-align=cetner;">
                <a href="{{ url($path.'/curricula') }}"><img src="{{url('uploads/products/c-hs.png')}}"></a>
            </div>
            <div class="col-lg-2 hide">
                <a href="{{ url($path.'/curricula') }}"><img src="{{url('uploads/products/c-hs.png')}}"></a>
            </div>
            <div class="col-lg-4 col-sm-12" style="text-align: justify; margin:10px; padding:0 50px 20px 50px;">
                <p style="color: #9acd57;"><b><i>"A modular, medically accurate, up-to-date curriculum that gives youth the information they need and want to succeed."</i></b></p>
                <p>Our activities are interactive, modular, and fun. We design them with educational best practices and developmentally appropriate stages in mind. 
                    Ready to start providing your students with the information to 
                    make healthy and safe choices around relationships, consent, and more?
                <a href="{{ url($path.'/curricula') }}">Start your subscription</a> or <a href="{{ url($path.'/c-review') }}">request a curriculum review</a> today!</p>
            </div>
        </div>

        <!-- Praise -->
        <div class="row bg-white hide" style="padding-top:50px;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-message-quote fa-fw"></i> FROM EDUCATORS LIKE YOU </h3>
            </div>
        </div>
        <div class="row bg-white hide">
            <div class="col-6 mx-auto">
                <div class="slideshow-container">
                    <div class="mySlides">
                        <b>K-12 curriculum</b>
                        <p><i class="fa-solid fa-quote-left"></i> “I was just trained on your curriculum—it is incredible!! I am so, so glad there is a curriculum out there like this. Thank you so much for creating it.”
                        <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">- Betty Caulkins, MS, TVI, O&M</p>
                    </div>
                    <div class="mySlides">
                        <b>Middle School Curriculum</b>
                        <p><i class="fa-solid fa-quote-left"></i> I am going to put into place practices developed by Karen and the UNHUSHED program immediately in my Health class. I hope to fully integrate this program over the next few years. <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– Anonymous curriculum trainee feedback</p>
                    </div>
                    <div class="mySlides">
                        <b>Elementary School Curriculum</b>
                        <p><i class="fa-solid fa-quote-left"></i> The training and curriculum are very well organized and full of useful tools to accompany the lessons/activities. <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– Anonymous curriculum trainee feedback</p>
                    </div>
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- About training -->
        <div class="row hide" style="padding-top:50px;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-address-card fa-fw"></i> CURRICULUM TRAINING</h3>
            </div>
        </div>
        <div class="row hide">
            <div class="col-6 mx-auto" style="text-align:justify;">
                <p>While our curriculum is ready for out-of-the-box implementation, we also offer training and consultations to make sure that you and your team are ready to 
                    offer the best possible program to your community. From broad overview guidance to detailed classroom management skills, we can help you strengthen the 
                    skills needed to facilitate these life savings and affirming conversations with your staff, parents, and student body.
                    <a href="{{ url($path.'/contact') }}">Get in touch for a quote today.</a></p>
            </div>
        </div>
        <div class="row justify-content-md-center hide" style="padding-bottom:25px;">
            <div class="col-4 rounded bg-white" style="text-align: center; margin:30px; padding:20px 50px 20px 50px;">
                <p><b>Other Books Available</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/how-i-got-into-sex-ed') }}">How I Got Into Sex... Ed</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-mental-health-practitioners') }}">A Handbook for Mental Health Practitioners</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-nursing-professionals') }}">A Handbook for Nursing Professionals</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/breaking-the-hush-factor') }}">Breaking the Hush Factor</a>
                </p>
            </div>
            <div class="col-4 rounded bg-white hide" style="text-align: center; margin:30px; padding:20px 50px 20px 50px;">
                <p><b>Additional Services</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-phone fa-fw"></i> <a href="{{ url($path.'/parent-guardian') }}">Parent Coaching</a>
                    <br/><i class="fa-solid fa-bus-school fa-fw" ></i> <a href="{{ url($path.'/store/free') }}">Free Resources</a>
                    <br/><i class="fa-solid fa-headset fa-fw"></i> <a href="{{ url($path.'/trainings') }}">Virtual & In-person Trainings</a>
                </p>
            </div>
        </div>
    </div>

    <style>
        /* mobile */
        @media (max-width: 767px) {
            .desBox, .hide{
                display: none;
            }
            .phoneBox{
                padding: 30px;
                color: #fff;
                font-size: 18px;
                text-align: center;
            }
            .wImg{
                background-color: white;
                position: relative;
                overflow: hidden;
                height: 340px;
            }
            .wImg::before {
                border-radius: 100%;
                position: absolute;
                background-color: rgba(38, 90, 142, 1);
                right: -200px;
                left: -200px;
                top: -200px;
                content: '';
                bottom: 0;
            }
        }
        /* laptop */
        @media (min-width: 768px) {
            .desBox{
                background-color: rgba(38, 90, 142, 0.9);
                margin: 60px;
                padding: 60px;
                border-radius: 10px;
                color: #fff;
                font-size: 20px;
                text-align: justify;
            }
            .phoneBox, .phone{
                display: none;
            }
            .wImg{
                padding: 20px 50px;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                background: url({{url('img/bgs/welcome.jpg')}}) no-repeat center center fixed;
            }
        }
        .sections{
            padding: 30px 300px;
            text-align: justify;
        }

        /* Slideshow container */
        * {box-sizing: border-box}
        .slideshow-container {
            position: relative;
        }
        /* Slides */
        .mySlides {
            display: none;
            padding: 30px 80px;
            text-align: justify;
        }
        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -30px;
            padding: 16px;
            font-weight: bold;
            font-size: 20px;
            border-radius: 3px 0 0 3px;
            user-select: none;
        }
        /* Position the "next button" to the right */
        .next {
            position: absolute;
            right: 0;
            border-radius: 0 3px 3px 0;
        }
        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(38, 90, 142, 0.8);
            color: white;
        }
    </style>
    <script type="text/javascript">
        //slider
        var slideIndex = 1;
            showSlides(slideIndex);
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
        function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}    
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "block";
        }
    </script>

</section>    
@endsection
