@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">About</span>
        </div>
    </div>

    <!-- ABOUT HEADER-->
    <div class="row" style="padding:35px 0;">
        <div class="col-6 px-1 mx-auto">
            <div class="row">
                <div class="col-1 m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop">
                </div>
                <div class="col-8 m-auto">
                    <p class="diazo" style="padding-top:5px;">About UN|HUSHED</p>
                </div>
            </div>
            <hr>
            <p class="sketchnote-square">Here at UN|HUSHED<span style="font-size:16px; vertical-align:top;">®</span> our mission is to <span style="font-weight:bold; color:#9acd57;"><i>break the silence surrounding human sexuality.</i></span></p>
            <p style="text-align:justify;">We achieve this mission by creating, facilitating, 
                and training on curricula for people of all ages (from infancy through older adulthood), academic levels, and professional backgrounds. Our 501(c)(3) home office, 
                founded October 24, 2017, is located in Austin, TX, and we are proud to have Board Members from across North America as well as facilitators and translators from around the globe.
            </p>
        </div>
    </div>

    <!-- Misson & Values -->
    <div class="row" style="padding:50px 0; background:#f0f3fa;">
        <div class="col-12 mx-auto my-auto pb-2">
            <h3 style="text-align:center;"><i class="fa-solid fa-scale-balanced fa-fw"></i> WE ARE VALUES BASED</h3>
        </div>
        <div class="col-6 mx-auto my-auto" style="text-align:justify;">
            <p>
            UN|HUSHED is a values-based organization, with organizational, sexual, and facilitator values. Combined, these elements provide guiding forces for how we consider and 
            execute comprehensive sexuality education. We focus on comprehensiveness, digital accessibility, health, inclusion, and medical accuracy. We draw on and are inspired by 
            research. We reconsider and reflect on our curricula in an ongoing way so that we are up to date and reflect the current world. 
            <a href="{{ url($path.'/values') }}">Read more about our organizational values.</a></p>
        </div>
    </div>

    <!-- About team -->
    <div class="row" style="padding-top:50px;">
        <div class="col-12 mx-auto my-auto pb-2">
            <h3 style="text-align:center;"><i class="fas fa-users fa-fw"></i> OUR TEAM</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mx-auto" style="text-align:justify;">
            <p>UN|HUSHED is made up of a team of professionals who are passionate about creating 
                and providing comprehensive sexuality education as a basic human right. With a wide array of skills, languages, and professional and educational experiences, the team is 
                able to consider and address sexuality education from a range of critical and unique perspectives. 
                <!-- <a href="{{ url($path.'/team') }}">Meet the team.</a></p> -->
        </div>
    </div>
    <div class="row justify-content-md-center" style="padding-bottom:25px;">
        <div class="col-3 rounded" style="text-align: center; margin:20px; padding:30px; background:#f0f3fa;">
            <p><b>MEET OUR FOUNDERS</b></p>
            <p style="text-align: center;">
                <img src="{{ asset('img/cards-lrg/founders.jpg') }}" alt="Karen & Nyk" style="max-width:260px;">
                <br>Karen and Nyk Rayne
            </p>
        </div>
        <div class="col-3 rounded" style="text-align: center; margin:30px; padding:30px; background:#f0f3fa;">
            <p><b>WHO IS OLLIE THE OCTOPUS?</b></p>
            <a href="{{ url('https://blog.unhushed.org/ollie-the-octopus/')}}">
            <p style="text-align: center;">
                <img src="{{ asset('img/cards-lrg/ollie.jpg') }}" alt="Karen & Nyk" style="max-width:260px;">
                <br>Ollie on vacation last summer
            </p></a>
        </div>
    </div>

    <!-- Standards -->
    <div class="row" style="padding:50px 0; background:#f0f3fa;">
        <div class="col-12 m-auto pb-1">
            <span id="standards" class="anchor">
            <h3 style="text-align:center;"><i class="fa-sharp fa-solid fa-chart-bar fa-fw"></i> WE ADHERE TO STANDARDS</h3>
        </div>
        <div class="col-6 m-auto" style="text-align:justify;">
            <p>UN|HUSHED adheres to international guidelines and technical guidance on what constitutes the most effective comprehensive sexuality education.</p>
            <!-- Standards table -->
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="tcard">
                        <table class="table table-borderless" id="standardsTable" style="width: 100%">
                            <thead class="border-bottom">
                                <tr>
                                    <th id="author"    colspan="2" style="text-align:center;">Authors</th>
                                    <th id="standard"  colspan="1" style="text-align:center;">Standards</th>
                                    <!-- <th id="aligns"    colspan="1" style="text-align:center;">Aligns</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Standards::orderBy('standard', 'DESC')->get() as $entry)
                                <tr id="row_{{$entry->id}}">
                                    <td colspan="1" >
                                        @empty($entry->image)
                                        @else
                                            <img class="tinyFlag border" src="{{ url('uploads/standards/'.$entry->image) }}" alt="{{$entry->image}}}"/></a>
                                        @endempty
                                    </td>
                                    <td colspan="1" style="text-align:left" class="align-middle">{{$entry->author}}</td>
                                    <td colspan="1" style="text-align:left" class="align-middle"> 
                                        <a href="{{ $entry->link }}" target="_blank">
                                            {{$entry->standard}}
                                        </a> 
                                    </td>
                                    <!-- <td colspan="1" style="text-align:center">{{ $entry->aligns }}</td> -->
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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
                <div class="socialCard">
                    <a href="{{ url($path.'/news') }}"><img src="{{ asset('img/social/news.png') }}" width="200" height="200"
                    onMouseOver="this.src='{{ asset("img/social/newsH.png") }}';" onMouseOut="this.src='{{ asset("img/social/news.png") }}';" alt="Newsletter"></a>
                </div>
                <!-- media facebook -->
                <div class="socialCard">
                    <a href="{{ url('https://www.facebook.com/unhushed') }}" target="_blank"><img src="{{ asset('img/social/fb.png') }}" width="200" height="200"
                    onMouseOver="this.src='{{ asset("img/social/fbH.png") }}';" onMouseOut="this.src='{{ asset("img/social/fb.png") }}';" alt="Facebook"></a>
                </div>
                <!-- media instagram -->
                <div class="socialCard">
                    <a href="https://www.instagram.com/accounts/login/?next=%2F_unhushed_%2F&source=follow"><img src="{{ asset('img/social/insta.png') }}" width="200" height="200"
                    onMouseOver="this.src='{{ asset("img/social/instaH.png") }}';" onMouseOut="this.src='{{ asset("img/social/insta.png") }}';" alt="Instagram"></a>
                </div>
                <!-- media twitter -->
                <div class="socialCard">
                    <a href="https://twitter.com/_unhushed?ref_src=twsrc%5Etfw"><img src="{{ asset('img/social/twitter2.png') }}" width="200" height="200"
                    onMouseOver="this.src='{{ asset("img/social/twitter2H.png") }}';" onMouseOut="this.src='{{ asset("img/social/twitter2.png") }}';" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </span>

    <!-- Awards -->
    <div class="row" style="padding:50px 0; background:#f0f3fa;">
        <div class="col-12 mx-auto my-auto pb-2">
            <h3 style="text-align:center;"><i class="fas fa-award fa-fw"></i> AWARDS</h3>
        </div>
        <div class="col-6 mx-auto my-auto" style="text-align:justify;">
            <p>
                UN|HUSHED is proud to have received 
                <a href="{{ url('https://www.worldsexualhealth.net/congress-of-the-world-association-for-sexual-health-sexuality-excellence-in-education-awards-wi') }}">
                    the World Association for Sexual Health 2021 Award for Excellence & Innovation in Sexuality Education.
                </a>
            </p>
        </div>
    </div>

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
        .tcard {
            border-radius: 10px;
            margin: 10px;
            padding: 0px 30px;
            background: #ffffff;
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
