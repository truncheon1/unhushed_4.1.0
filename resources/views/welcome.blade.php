@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb" style="font-size:16px">
        <!-- Welcome -->
        <div class="row wImg">
            <!-- laptop -->
            <div class="col-md-4 col-sm-12 mr-auto desBox">
                <h1>Hard topics the easy way.</h1>
                <p>Our K-12 curriculum brings a wide range of topics including communication, consent, identity, relationships, and more...</p>
                <a href="{{ url($path.'/subscription-info') }}"><button class="btn btn-secondary px-5">LEARN MORE</button></a>
            </div>
            <!-- phone -->
            <div class="col-sm-12 phoneBox">
                <p><span style="font-weight:bold; text-transform: uppercase;">OUR MISSION IS TO:</span>
                    <br><span class="rollerscript-rough" style="font-size:25px; color:#9acd57;">Break the silence 
                    <br>surrounding 
                    <br>human sexuality.</span></p>
                <p>We believe in the power and necessity of school based sexuality education. 
                    <br>Our K-12 curriculum brings a wide range of topics including: 
                    <br>communication, consent, identity, relationships, and more.
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

        <!-- Offer -->
        <div class="row laptop" style="padding: 30px 0 10px 0;">
            <div class="col-auto mx-auto my-auto">
                <h3>WHAT WE OFFER</h3>
            </div>
        </div>
        <div class="row laptop justify-content-center" style="padding-bottom:30px;">
            <div class="iImgW col-md-2 col-sm-12">
                <a href="{{ url($path.'/subscription-info#medically') }}" class="text-white">
                    Medical Accuracy<br>
                    <img src="{{url('img/cards-med/medical.png')}}" style="max-width:150px" alt="<i class='fa-regular fa-clipboard-medical fa-2xl'></i>">
                </a>
            </div>
            <div class="iImgW col-md-2 col-sm-12">
                <a href="{{ url($path.'/subscription-info#upToDate') }}" class="text-white">
                    Up to Date Materials<br>
                    <img src="{{url('img/cards-med/time.png')}}" style="max-width:150px" alt="<i class='fa-regular fa-calendar-clock fa-2xl'></i>">
                </a>
            </div>
            <div class="iImgW col-md-2 col-sm-12">
                <a href="{{ url($path.'/subscription-info#inclusive') }}" class="text-white">
                    Inclusivity<br>
                    <img src="{{url('img/cards-med/inclusive.png')}}" style="max-width:150px" alt="<i class='fa-regular fa-hands-holding-child fa-2xl'>">
                </a>
            </div>
        </div>

        <!-- Curriculum -->
        <div class="row cImg">
            <!-- phone -->
            <div class="col-12 mx-auto phone">
                <img src="{{url('img/bgs/curricula.png')}}">
            </div>
            <!-- laptop -->
            <div class="col-lg-2 col-md-12 laptop">
            </div>
            <div class="col-lg-auto col-md-12 laptop">
                <img src="{{url('img/bgs/curricula.png')}}">
            </div>
            <div class="col-lg-5 col-md-12" style="text-align:justify;">
                <h3>THE UN|HUSHED K-12 CURRICULUM</h3>
                <p><b><i>"A modular, medically accurate, up-to-date curriculum that gives youth the information they need and want to succeed."</i></b></p>
                <p>Our activities are interactive, modular, and fun. We design them with educational best practices and developmentally appropriate stages in mind.</p> 
                <p>Ready to start providing your students with the information to 
                    make healthy and safe choices around relationships, consent, and more?</p>
                <p><b><a href="{{ url($path.'/curricula') }}">Start your subscription</a> or <a href="{{ url($path.'/c-review') }}">request a curriculum review</a> today!</b></p>
            </div>
        </div>

        <!-- Praise -->
        <div class="row laptop" style="padding-top:50px; background:#f0f3fa;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-message-quote fa-fw"></i> FROM EDUCATORS LIKE YOU </h3>
            </div>
        </div>
        <div class="row laptop" style="background:#f0f3fa;">
            <div class="col-8 mx-auto">
                <div class="slideshow-container">
                    <div class="mySlides">
                        <b>K-12 curriculum</b>
                        <p>
                            <i class="fa-solid fa-quote-left"></i> I have been using the UN|HUSHED Middle School curriculum 
                            with both middle school and high school students for five years. We love how current and progressive 
                            it is and packed full of lessons and activities that the students really enjoy! The students truly 
                            appreciate the content--they learn that we really are trying to help them make safe decisions and 
                            build respect for themselves and others. We recently purchased the Elementary curriculum as well, 
                            and so far, the students have really enjoyed the lessons! There are lots of hands-on activities and 
                            opportunities to move, so they are fully engaged!
                            <i class="fa-solid fa-quote-right"></i>
                        </p>
                        <p style="text-align:right;">- Anonymous<br/>2023</p>
                    </div>
                    <div class="mySlides">
                        <b>K-8 curriculum</b>
                        <p>
                            <i class="fa-solid fa-quote-left"></i> The UN|HUSHED curriculum is revolutionary! From Dr. Karen Rayne's background 
                            in educational psychology to the many diverse fields of expertise of those that comprise the whole UN|HUSHED team, this 
                            emergent curriculum can be shared with confidence knowing that immense care continuously goes into each lesson. I feel 
                            extremely grateful for the opportunity to work with the UN|HUSHED organization, as it is a real gem with unparalleled 
                            ongoing support.
                            <i class="fa-solid fa-quote-right"></i>
                        </p>
                        <p style="text-align:right;">- Sarah Elledge
                            <br/>Speciality Mentor
                            <br/>Whole Life Learning Center
                            <br/>2022
                        </p>
                    </div>
                    <div class="mySlides">
                        <b>K-12 curriculum</b>
                        <p>
                            <i class="fa-solid fa-quote-left"></i> I was just trained on your curriculum—it is incredible!! I am so, so glad there is a 
                            curriculum out there like this. Thank you so much for creating it.”
                            <i class="fa-solid fa-quote-right"></i>
                        </p>
                        <p style="text-align:right;">- Betty Caulkins, MS, TVI, O&M <br/>2018</p>
                    </div>
                    <div class="mySlides">
                        <b>Middle School Curriculum</b>
                        <p>
                            <i class="fa-solid fa-quote-left"></i> I am going to put into place practices developed by Karen and the UNHUSHED program 
                            immediately in my Health class. I hope to fully integrate this program over the next few years. 
                            <i class="fa-solid fa-quote-right"></i>
                        </p>
                        <p style="text-align:right;">– Anonymous curriculum trainee feedback</p>
                    </div>
                    <div class="mySlides">
                        <b>Elementary School Curriculum</b>
                        <p>
                            <i class="fa-solid fa-quote-left"></i> The training and curriculum are very well organized and full of useful tools to accompany 
                            the lessons/activities. <i class="fa-solid fa-quote-right"></i>
                        </p>
                        <p style="text-align:right;">– Anonymous curriculum trainee feedback <br/>2021</p>
                    </div>
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                </div>
            </div>
        </div>

        <!-- About training -->
        <div class="row laptop" style="padding-top:50px;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-message-quote fa-fw"></i> CURRICULUM TRAINING</h3>
            </div>
        </div>
        <div class="row laptop">
            <div class="col-7 mx-auto" style="text-align:justify; font-size:16px;">
                <p>While our curriculum is ready for out-of-the-box implementation, we also offer training and consultations to make sure that you and your team are ready to 
                    offer the best possible program to your community. From broad overview guidance to detailed classroom management skills, we can help you strengthen the 
                    skills needed to facilitate these life savings and affirming conversations with your staff, parents, and student body.
                    <a href="{{ url($path.'/contact') }}">Get in touch for a quote today.</a></p>
            </div>
        </div>
        <div class="row justify-content-md-center laptop" style="padding-bottom:25px;">
            <div class="col-3 rounded" style="text-align: center; margin:30px; padding:30px; background:#f0f3fa;">
                <p><b>Other Resources Available</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/breaking-the-hush-factor-2') }}">Breaking the Hush Factor v2.0</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/how-i-got-into-sex-ed') }}">How I Got Into Sex... Ed</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-mental-health-practitioners') }}">A Handbook for Mental Health Practitioners</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-nursing-professionals') }}">A Handbook for Nursing Professionals</a>
                    
                </p>
            </div>
            <div class="col-3 rounded laptop" style="text-align: center; margin:30px; padding:30px; background:#f0f3fa;">
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
        /* start mobile */
        @media (max-width: 767px) {
            .desBox{
                display: none;
            }
            .phoneBox{
                padding: 30px 30px 5px 30px;
                color: #fff;
                font-size: 18px;
                text-align: center;
            }
            .wImg{
                background-color: white;
                position: relative;
                height: 400px;
                overflow: hidden;
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
            .cImg{
                padding: 10px;
            }
        }
        /* end mobile */
        /* start laptop */
        @media (min-width: 768px) {
            .desBox{
                background-color: rgba(38, 90, 142, 0.8);
                margin: 50px;
                padding: 40px;
                border-radius: 10px;
                color: #fff;
                font-size: 16px;
                text-align: justify;
            }
            .phoneBox{
                display: none;
            }
            .wImg{
                width: 100%;
                padding: 20px 0;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                background: url({{ url('img/bgs/welcome.jpg') }}) no-repeat center center fixed;
            }
            .cImg{
                padding-top: 30px;
            }
        }
        /* end laptop */
        .cImg{
            background: rgb(224,230,245);
            background: linear-gradient(0deg, rgba(224,230,245,1) 19%, rgba(181,195,230,1) 30%, rgba(188,196,217,1) 35%, rgba(203,213,238,1) 66%, rgba(240,243,250,1) 100%);
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
            text-decoration: none; /* remove underline from arrow links */
            color: inherit;
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
