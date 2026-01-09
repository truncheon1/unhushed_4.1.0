@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Give & get involved</span>
        </div>
    </div>

    <!-- GIVE-->
    <div class="row" style="padding:35px 0;">
        <div class="col-6 px-1 mx-auto">
            <div class="row">
                <div class="col-1 m-auto">
                    <a href="https://donate.stripe.com/6oE8z7bv52XJ9r2aEE">
                        <img class="img-s" src="{{ asset('img/octos/ollie-donate.png') }}" alt="Ollie the octopus holding up a laptop">
                    </a>
                </div>
                <div class="col-8 m-auto">
                    <p class="diazo" style="padding-top:5px;">Give & get involved</p>
                </div>
            </div>
            <hr>
            <p class="sketchnote-square" style="text-align:center;">Here at UN|HUSHED<span style="font-size:16px; vertical-align:top;">®</span> our mission is to 
            <br><span style="font-weight:bold; color:#9acd57;"><i>break the silence surrounding human sexuality.</i></span></p>
            <p style="text-align:center;">We achieve this mission in large part with donations from individuals just like you!
                <br/>Pick your favorite way to donate below:</p>
        </div>
        <div class="row justify-content-center">
            <!-- way -->
            <div class="xsmCard">
                <a href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q" target="_blank">
                    <img src="{{ asset('img/give/paypal.png') }}" alt="Donate Today">
                </a>
            </div>
            <!-- way -->
            <div class="xsmCard">
                <a href="https://donate.stripe.com/6oE8z7bv52XJ9r2aEE" target="_blank">
                    <img src="{{ asset('img/give/stripe.png') }}" alt="Donate Today">
                </a>
            </div>
            <!-- way -->
            <div class="xsmCard">
                <a href="{{ url('https://www.facebook.com/fund/unhushed/') }}" target="_blank">
                    <img src="{{ asset('img/give/fb.png') }}" alt="Facebook">
                </a>
            </div>
            <!-- way -->
            <div class="xsmCard">
                <a href="{{ url('https://www.charity.ebay.com/charity/UN%7CHUSHED/3494496') }}" target="_blank">
                    <img src="{{ asset('img/give/eBay.png') }}" alt="eBay">
                </a>
            </div>
        </div>
    </div>

    <!-- GET INVOLVED -->
    <div class="row" style="padding:50px 0; background:#f0f3fa;">
        <div class="col-12 mx-auto my-auto pb-2">
            <h3 style="text-align:center;"><i class="fa-solid fa-hand-holding-hand fa-fw"></i> GET INVOLVED</h3>
        </div>
        <div class="col-6 mx-auto my-auto" style="text-align:center;">
            <p>We couldn't do it without people like you!</p>
        </div>
        <div class="row justify-content-center">
            <!-- way -->
            <div class="xsmCard">
                <a href="{{ url($path.'/volunteer') }}">
                    <img src="{{ asset('img/give/volunteer.png') }}" alt="Volunteer">
                </a>
            </div>
            <!-- way -->
            <div class="xsmCard">
                <a href="{{ url('https://www.guidestar.org/profile/82-3184837') }}" target="_blank">
                    <img src="{{ asset('img/give/guide.png') }}" alt="Guide Star">
                </a>
            </div>
            <!-- way -->
            <div class="xsmCard">
                <a href="{{ url('https://www.yelp.com/biz/unhushed-austin-2') }}" target="_blank">
                    <img src="{{ asset('img/give/yelp.png') }}" alt="Yelp">
                </a>
            </div>
        </div>
    </div>

    

    <!-- SOCIAL HEADER-->
    <span id="social" class="anchor">
        <div class="row" style="padding:50px 0;">
            <div class="col-12 mx-auto my-auto pb-2">
                <h3 style="text-align:center;"><i class="fas fa-comments fa-fw"></i> LET'S GET SOCIAL</h3>
            </div>
            <div class="col-6 mx-auto my-auto" style="text-align:center;">
                <p>We love keeping in touch, learning new things, spreading medically accurate info. Come join us!</p>
            </div>
            <div class="row justify-content-center">
                <!-- media newsletter -->
                <div class="xsmCard">
                    <a href="{{ url($path.'/news') }}"><img src="{{ asset('img/social/news.png') }}" width="150" height="150"
                    onMouseOver="this.src='{{ asset("img/social/newsH.png") }}';" onMouseOut="this.src='{{ asset("img/social/news.png") }}';" alt="Newsletter"></a>
                </div>
                <!-- media facebook -->
                <div class="xsmCard">
                    <a href="{{ url('https://www.facebook.com/unhushed') }}" target="_blank"><img src="{{ asset('img/social/fb.png') }}" width="150" height="150"
                    onMouseOver="this.src='{{ asset("img/social/fbH.png") }}';" onMouseOut="this.src='{{ asset("img/social/fb.png") }}';" alt="Facebook"></a>
                </div>
                <!-- media instagram -->
                <div class="xsmCard">
                    <a href="https://www.instagram.com/accounts/login/?next=%2F_unhushed_%2F&source=follow"><img src="{{ asset('img/social/insta.png') }}" width="150" height="150"
                    onMouseOver="this.src='{{ asset("img/social/instaH.png") }}';" onMouseOut="this.src='{{ asset("img/social/insta.png") }}';" alt="Instagram"></a>
                </div>
            </div>
        </div>
    </span>

    <style>
        .anchor {
            scroll-margin-top: 125px;
        }
        p {
            font-size:18px;
        }
        .socialCard {
            width: 200px;
            height: 200px;
            border-radius: 10px;
            margin: 10px;
            overflow: hidden;
            -moz-box-shadow:    4px 5px 6px 4px #ccc;
            -webkit-box-shadow: 4px 5px 6px 4px #ccc;
            box-shadow:         4px 5px 6px 4px #ccc;
            padding: 0;
        }
        /* mobile */
        @media (max-width: 767px) {
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
            .wImg{
                width: 100%;
                padding: 20px 50px;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                background: url({{url('img/bgs/welcome.jpg')}}) no-repeat center center fixed;
            }
            .cImg{
                padding-top: 30px;
            }
        }
        .cImg{
            background: rgb(224,230,245);
            background: linear-gradient(0deg, rgba(224,230,245,1) 19%, rgba(181,195,230,1) 30%, rgba(188,196,217,1) 35%, rgba(203,213,238,1) 66%, rgba(240,243,250,1) 100%);
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
