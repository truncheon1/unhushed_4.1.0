@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <!-- Welcome -->
        <div class="row wImg p-5">
            <!-- laptop -->
            <div class="col-md-6 col-sm-12 mr-auto desBox">
                <h1 style="text-align:center;">Austin Community Classes
                    <br/>for 7th & 8th Graders</h1>
                <p>Our semester long sessions are designed to engage 13-15 year olds in 7th and 8th grades. Skilled facilitators cover the full 40 hour
                    <a href="{{ url($path.'/middle-school-classes') }}">UNHUSHED: Middle School Curriculum</a>. Provide the youth in your life with 
                    the info to make healthy and safe choices around relationships, consent, and more.</p>
                <!--<a href="{{ url($path.'/subscription-info') }}"><button class="btn btn-secondary px-5">MORE</button></a>-->
            </div>
            <!-- phone -->
            <div class="col-sm-12 phoneBox">
                <p>Thanks for checking out our classes. Please view this page on a laptop for more information.</p>
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

        <!-- Steps to Enroll -->
        <div class="row laptop" style="padding: 30px 0 10px 0; background:#f0f3fa;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-regular fa-shoe-prints fa-fw"></i> STEPS TO ENROLL</h3>
            </div>
        </div>
        <div class="row laptop justify-content-center" style="padding-bottom:30px; background:#f0f3fa;">
            <div class="iImgW col-md-2 col-sm-12">
                <a href="#step1" class="text-white">
                    1. Orientation<br>
                    <img src="{{url('img/cards-med/compass.png')}}" style="max-width:150px" alt="<i class='fa-regular fa-clipboard-medical fa-2xl'></i>">
                </a>
            </div>
            <div class="iImgW col-md-2 col-sm-12">
                <a href="#step2" class="text-white">
                    2. Registration<br>
                    <img src="{{url('img/cards-med/register.png')}}" style="max-width:150px" alt="<i class='fa-regular fa-calendar-clock fa-2xl'></i>">
                </a>
            </div>
            <div class="iImgW col-md-2 col-sm-12">
                <a href="#step3" class="text-white">
                    3. Payment<br>
                    <img src="{{url('img/cards-med/payment.png')}}" style="max-width:150px" alt="<i class='fa-regular fa-hands-holding-child fa-2xl'>">
                </a>
            </div>
        </div>

        <!-- Step 1 -->
        <div id="step1" class="row laptop anchor" style="padding-top:30px;">
            <div class="col-auto mx-auto my-auto pb-2">
                <h3><i class="fa-sharp fa-regular fa-compass fa-fw"></i> STEP 1: PARENT/GUARDIAN ORIENTATION</h3>
            </div>
        </div>
        <div class="row laptop pb-5">
            <div class="col-md-5 col-sm-12 mx-auto pb-3">
                <ul class="green pb-3">
                    <span style="font-size:18px;"><b>Parents/Guardians</b> you must attend one of the orientations in order to sign your youth up for class.</span>
                    <li class="pl-3">Thursday, September 12th, 7:00pm - 9:00pm <a href="https://unhush.us/parent-zoom" target="_blank">ZOOM REGISTRATION</a></li>
                    <li class="pl-3">Already attended a parent orientation? <a href="mailto: info@unhushed.org">Email us at info@unhushed.org</a> to enroll your youth now.</li> 
                </ul>
                <ul class="green">
                    <span style="font-size:18px;"><b>What's covered</b> during the orientation?</span>
                    <li class="pl-3">Meet the facilitators and ask your questions.</li>
                    <li class="pl-3">Get the class schedule.</li>
                    <li class="pl-3">Participate in an activity and look over the curriculum.</li> 
                </ul>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step2" class="row laptop anchor" style="background:#f0f3fa; padding-top:50px;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-light fa-clipboard fa-fw"></i> STEP 2: REGISTRATION</h3>
            </div>
        </div>
        <div class="row laptop pb-5" style="background:#f0f3fa; text-align:justify;">
            <div class="col-md-5 col-sm-12 mx-auto">
                <ul class="green">
                    <span style="font-size:18px;"><b>I'm Ready</b> to register my youth.</span>
                    <li class="pl-3">I have attended the required parent/guardian orientation and know what I'm signing my youth up for.</li>
                    <li class="pl-3"><a href="{{ url($path.'/middle-school-registration') }}"><b>REGISTER MY YOUTH</b></a>  (This opens August 25th, 2024)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Step 3 -->
    <div id="step3" class="row laptop anchor" style="padding-top:30px;">
            <div class="col-auto mx-auto my-auto pb-1">
                <h3><i class="fa-sharp fa-solid fa-cart-shopping fa-fw"></i> STEP 3: PAYMENT</h3>
            </div>
        </div>
        <div class="row laptop pb-5">
            <div class="col-md-5 col-sm-12 mx-auto">
                <ul class="green pb-3">
                    <span style="font-size:18px;"><b>Payment</b> is completed during registration.</span>
                    <li class="pl-3">Tuition can be paid in one payment or borken up into a 6 month payment plan.</li>
                    <li class="pl-3">As part of our mission we offer sliding scale rates for families in need</li>
                    <li class="pl-3">You can <a href="mailto: info@unhushed.org">email info@unhushed.org</a> for assistance.</li> 
                    <li class="pl-3">Want to help another family? <a href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q" target="_blank">Donate today!</a></li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .anchor {
            scroll-margin-top: 120px;
        }
        /* mobile */
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
        /* laptop */
        @media (min-width: 768px) {
            .desBox{
                background-color: rgba(38, 90, 142, 0.8);
                margin: 50px;
                padding: 40px;
                border-radius: 10px;
                color: #fff;
                font-size: 18px;
                text-align: justify;
            }
            .phoneBox{
                display: none;
            }
            .wImg{
                width: 100%;
                padding: 20px 50px;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                background: url({{url('img/bgs/mscc.jpg')}}) no-repeat center center fixed;
            }
            .cImg{
                padding-top: 30px;
            }
        }
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
