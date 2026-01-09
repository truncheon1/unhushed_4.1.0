@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <!-- Welcome -->
        <div class="row wImg">
            <div class="col-md-8 col-sm-12 ms-auto desBox">
                <p style="font-size:20px; font-weight:bold; text-transform: uppercase;">CAREGIVERS/PARENTS/GUARDIANS:</p>
                <p>Here at UN|HUSHED we believe in the importance of your parental role in your young person’s sexualty education, whether you are a parent, guardian, 
                    or different kind of caregiver.</p>
                <p>In fact, family based adults (maybe you!) were your child’s first sexuality educator. It begins with the ways we teach children to think and speak about bodies, 
                    continuing through talking about friendships in childhood, and maybe even sexual or romantic connections during adolescence. The importance of your role in your 
                    child’s sexuality education is why we have <a href="{{ url($path.'/store/books') }}" style="color:#9acd57;"><b><u>books</u></b></a>, 
                    <a href="{{ url($path.'/store/curricula?s[]=4&s[]=35') }}" style="color:#9acd57;"><b><u>curricula</u></b></a>, and 
                    <a href="{{ url($path.'/parent-guardian') }}"style="color:#9acd57;"><b><u>coaching</u></b></a> just for you!</p>
                <p>We believe that young adults should know everything they need to make healthy, independent choices about their own sexuality. Sexuality education is 
                    about preparing teenagers to become those healthy young adults.
                </p>
            </div>
            <div class="col-sm-12 phoneBox">
                <p><span style="font-weight:bold; text-transform: uppercase;">OUR MISSION IS TO:</span>
                    </br><span class="rollerscript-rough" style="font-size:25px; color:#9acd57;">Break the silence 
                    </br>surrounding human sexuality.</span></p>
                <p>we believe in the importance of your parental role in your children’s and teenagers’ sexualty education, whether you are a parent, guardian, 
                    or different kind of caregiver.
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

        <!-- UNHUSHED at Home -->
        <div class="row bg-primary" style="padding:25px 0 10px 0;">
            <div class="col-auto mx-auto my-auto" style="text-align:center;">
                <h3 style="color:white;"><i class="fa-solid fa-house-chimney fa-fw"></i> UN|HUSHED at Home</h3>
                <h4 style="color:#9acd57;" class="sketchnote-square"><i><b>Family led sex ed.</i></b></h4>
            </div>
        </div>
        <div class="row justify-content-center bg-primary" style="border-bottom: 20px solid #26598e;">
            <div class="col-auto align-self-end rounded-left bg-white" style="margin:-5px 0 0 0; padding:5px 0 0 0;">
                <img src="{{url('img/bgs/home1.png')}}">
            </div>
            <div class="col-lg-4 col-md-10 align-self-end" style="padding:0;">
                <div class="row" style="height: 252px;">
                    <div class="col-12 bg-white rounded-right" style="text-align:justify; margin-top:-5px; padding:20px 30px 0; font-size:18px; height:180px;">
                        <p>Are you ready to bring (or deepen) conversations about consent, changing bodies, relationships, identity, and the media into your home? 
                            That’s amazing! We are in full support of you taking this step! We wrote this series of courses specifically to help you do just that.
                        </p>
                    </div>
                    <div class="col-12 align-self-end" style="padding:23px; font-size:20px;">
                        <a href="{{ url($path.'/store/unhushed-at-home-ages-12-15') }}" style="color: #9acd57;"><u><b>Get the first course free today!</b></u></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Praise -->
        <div class="row laptop" style="padding-top:50px; background:#f0f3fa;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-message-quote fa-fw"></i> FROM PARENTS LIKE YOU </h3>
            </div>
        </div>
        <div class="row laptop" style="background:#f0f3fa;">
            <div class="col-8 mx-auto">
                <div class="slideshow-container">
                    <div class="mySlides">
                        <b>Community classes</b>
                        <p><i class="fa-solid fa-quote-left"></i> My children both took the classes in middle school. I had friends say it was too risky, and I was shocked. 
                        Driving is risky and yet we all do it. Do you really want your child behind the wheel without driver's ed? Now my children are young adults, 22 and 19, and 
                        both sexually active. They both practice safer sex. They both delayed sexual activity past most of their peers. I know these things about my children 
                        because they learned to speak openly and honestly about sexually in the UN|HUSHED classes and to know themselves better. Don't let your children out on 
                        the highway of life without <strike>driver's</strike> sex ed. 
                        <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– S. Owens</p>
                    </div>
                    <div class="mySlides">
                        <b>Community classes</b>
                        <p><i class="fa-solid fa-quote-left"></i> My teenage daughter recently completed a sex ed course with UN|HUSHED and we couldn't be happier. 
                        Prior to this class she was very uncomfortable bringing issues of a sexual nature to me for discussion. She is now able to speak freely with 
                        me and our relationship is much closer. The class not only offered her the information she needed, but more importantly, it gave her the tools 
                        and confidence to make appropriate choices in her life. <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– Mom whose child completed the ATX community class</p>
                    </div>
                    <div class="mySlides">
                        <b>Parent coaching</b>
                        <p><i class="fa-solid fa-quote-left"></i> Karen is extremely insightful and knowledgeable in the field of adolescents. She has been so helpful
                        to me in helping me communicate more deeply with my 15 year old son, especially in the area of sexuality. <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– Marianne</p>
                    </div>
                    <div class="mySlides">
                        <b>Community classes</b>
                        <p><i class="fa-solid fa-quote-left"></i> When asked if I wanted my teen to join this sex ed class, I said “hell, no!” as most courses I knew of were 
                        far too narrow-minded.  After researching the UN|HUSHED curriculum, I enthusiastically enrolled my daughter and found it to be a fantastic experience 
                        for her and her classmates.  I HIGHLY recommend these classes!
                        <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– Pat D.</p>
                    </div>
                    <div class="mySlides">
                        <p style="font-size:16px">
                            <b><i>"Our middle schooler was initially resistant to go, so we just asked them to try the first class, and they were hooked by the brave and 
                            inclusive (and fun!) space of the UN|HUSHED classes. We really saw a lot of growth and confidence talking about sexuality during the year long experience, and we are 
                            so grateful!"</i></b> -Erika (parent)
                        </p>
                    </div>
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breaking the Hush Factor -->
        <div class="row bg-white" style="padding: 50px 0 10px 0;">
            <div class="col-auto mx-auto my-auto ">
                <h3><i class="fa-solid fa-book fa-fw"></i>BREAKING THE HUSH FACTOR</h3>
            </div>
        </div>
        <div class="row bg-white justify-content-md-center" style="padding-bottom:25px;">
            <div class="col-auto mx-auto my-auto phone" style="text-align=cetner;">
                <a href="{{ url($path.'/store/breaking-the-hush-factor-2') }}"><img src="{{url('uploads/products/603377cd0c0755b5d58e.png')}}"></a>
            </div>
            <div class="col-lg-2 hide">
                <a href="{{ url($path.'/store/breaking-the-hush-factor-2') }}"><img src="{{url('uploads/products/603377cd0c0755b5d58e.png')}}"></a>
            </div>
            <div class="col-lg-4 col-sm-12" style="text-align: justify; margin:10px; padding:0 50px 20px 50px;">
                <p style="color: #9acd57;"><b><i>"A go-to guide for parents who are looking to prepare their pre-teens or teens with information and support to create sexually 
                    healthy lives."</i></b></p>
                <p>Ready to start (or better yet, continue) the conversation with your teen, but you don't know how to get there? Read these ten simple rules to get the ball rolling.
                <a href="{{ url($path.'/store/breaking-the-hush-factor-2') }}">Buy your copy today.</a></p>
            </div>
        </div>

        <!-- About UN|HUSHED -->
        <div class="row laptop" style="padding-top:50px; background:#f0f3fa;">
            <div class="col-auto mx-auto my-auto">
            <h3><i class="fa-solid fa-address-card fa-fw"></i> ABOUT UN|HUSHED</h3>
            </div>
        </div>
        <div class="row laptop" style="background:#f0f3fa;">
            <div class="col-6 mx-auto" style="text-align:justify;">
                <p>Here at UN|HUSHED we write curriculum on hard topics the easy way, sex ed done right! And our mission is to break the silence surrounding human sexuality.
                    The thing is, sex ed is more than just “sex”.
                    It’s about relationships, communication, and so much more.
                    <a href="{{ url($path.'/about') }}">Learn More Here</a></p>
            </div>
        </div>
        <div class="row justify-content-md-center" style="padding-bottom:25px; background:#f0f3fa;">
            <div class="col-3 rounded" style="text-align: center; margin:30px; padding:30px; background:#ffffff;">
                <p><b>Books Available</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/where-do-babies-come-from') }}">Where Do Babies Come From?</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-child-welfare-providers') }}">A Handbook for Child Welfare Providers</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-nursing-professionals') }}">A Handbook for Nursing Professionals</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/how-i-got-into-sex-ed') }}">How I Got Into Sex... Ed</a>
                </p>
            </div>
            <div class="col-3 rounded" style="text-align: center; margin:30px; padding:30px; background:#ffffff;">
                <p><b>Services Offered</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-phone fa-fw"></i> <a href="{{ url($path.'/parent-guardian') }}">Parent Coaching</a>
                    <br/><i class="fa-solid fa-bus-school fa-fw" ></i> <a href="{{ url($path.'/curricula') }}">K-12 Comprehensive Curricula</a>
                    <br/><i class="fa-solid fa-headset fa-fw"></i> <a href="{{ url($path.'/trainings') }}">Virtual & In-person Trainings</a>
                </p>
            </div>
        </div>
    </div>

    <style>
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
                background: url({{url('img/bgs/parents.jpg')}}) no-repeat center center fixed;
            }
            .cImg{
                padding-top: 30px;
            }
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
