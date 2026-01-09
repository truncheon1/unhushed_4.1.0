@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}">About </a> |
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <span style="font-weight: bold;color:#9acd57">The Belly Project v2.0</span>
        </div>
    </div>

    <!-- CONTENT -->
    @include('fundraisers.thebellyproject.content')
    <!-- THERMOMETER -->
    @include('fundraisers.thebellyproject.thermometer')

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
                    <p class="sketchnote-square" style="max-width:420px">We're fundraising for
                        <br>The Belly Project: An UN|HUSHED Venture
                    </p>
                </div>
            </div>
        </div>

        <!-- ABOUT -->
        <span id="about" class="anchor">
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

        <!-- PROBLEM -->
        <span id="problem" class="anchor"></span>
        <div class="box3">
            <div class="bday-txt">
                <p><span class="sketchnote-square">The Problem</span>
                    <br/>While people expect human sexuality to cover topics like pregnancy and abortion, they often forget that we help people of all ages take a deeper
                    dive into all facets of relationships both between individuals and between a person and their own body.
                    Education is the most effective tool when our goal is to raise mentally and physically healthy, happy, and productive humans.
                    Bodily autonomy, medically factual information, and access to medical treatment should not be political.
                    They should be a basic human right. The emerging legal environment in the United States is proving that access to basic human rights are at great risk.
                    We are called on, here at UN|HUSHED, to change that.
                </p>
            </div>
        </div>

        <!-- PROJECT -->
        <span id="project" class="anchor"></span>
        <div class="box3">
            <div class="bday-txt">
                <p><span class="sketchnote-square">The Project</span>
                    <br/>The way that we are able to influence cultural elements like this is through education, art, and connection.
                    Thus, we are reviving a project that I started with the current President of our Board of Directors, Christy Tashjian, in 2008:
                    <a href="https://thebellyproject.wordpress.com" target="_blank">The Belly Project</a>.
                    The Belly Project.
                    This is a simple blog-style site where people can submit pictures of their bellies along with their ages and reproductive histories.
                    It turned into a powerful space for connecting about many elements of bodies and reproduction.
                    The Belly Project was developed through a conversation about the ways in which people don’t see normal abdomens and don’t really have a sense of the reproductive
                    histories of the abdomens they do see. The frequency of abortion and miscarriage, combined with the medical intersection of abortion and miscarriage,
                    is something that is left entirely off the conversation table. The Belly Project: An UN|HUSHED Venture will work to change that.
                    As an ongoing, living art piece, The Belly Project: An UN|HUSHED Venture can influence, engage, and provide movement towards a culture that is more accepting of
                    the very human, embodied experience of miscarriage, abortion, and pregnancy.
                </p>
                <!-- Donate -->
                <div class="row">
                    <div class="mx-auto p-1">
                        <a href="https://www.paypal.com/donate/?campaign_id=7KMPGCBPFRKLE" class="btn btn-secondary add-cart" style="color:#fff" target="_blank">DONATE</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- HISTORY -->
        <span id="history" class="anchor"></span>
        <div class="box3">
            <div class="bday-txt">
                <p><span class="sketchnote-square">The History</span>
                    <br/>The Belly Project started with just a few invitations to participate among my and Christy’s friends.
                    Within weeks it ballooned and took on a life of its own.
                    After the first dozen or so posts from our circle of friends, the remaining hundreds of images and hundreds of thousands of views came
                    in over the next few years without any additional marketing or outreach on our parts. In fact, even years after our final post (in January of 2013),
                    interest has remained high, with almost 500,000 views in 2014 and almost 40,000 views last year, about a decade after we stopped posting.
                    We have continued to receive (although we have not been able to post) pictures and stories from people who find the website through google
                    searches and new articles that reference the site.</p>
                    <p>Here are some of the comments that people sent in about the original incarnation of The Belly Project:<ul>
                        <li>“Its re-assuring to see how many women have had miscarriages. Nobody ever mentions them somehow, although they are relatively common.”</li>
                        <li>“Thank you for doing this project. I have always had body issues that center mostly around my belly, although dealing with an eating disorder
                            has forced me to work on this. Seeing all those pictures, I thought I was abnormal and fat for a 23-year-old with no pregnancies, but when
                            I took the picture, I realized I look like everyone else.”</li>
                        <li>“This is an interesting site! I find myself captivated; sometimes in a good way, sometimes in a bad way.  As a woman yet to have babies
                            (and currently trying to conceive), I get pretty horrified by some of the trauma women’s bellies have gone through.”</li>
                        <li>“I’ve always weighed more than I would care to. Even after finding my amazing partner (he loves my body precisely as is, stretch marks, wobbly thighs and all),
                            even after accepting the size I wear, even after acknowledging that the health of my body is more important than the number on the scale: I’ve always hated my stomach.
                            I felt compelled to add my photo to the growing archive to show others out there that bellies come in all shapes and sizes, all ages, all races, all life experiences.
                            More importantly, I took the picture with the intent of acknowledging my stomach for what it is, flaws and all, putting it out there to assert myself that my stomach
                            is as worthy of love as everyone else’s. Then, suddenly, I paused. I turned, I twisted, I looked at the picture in the camera and I thought to myself, “You know,
                            that’s really rather cute. Sexy, even.” Flabbergasted, I took a few more shots. I looked at the photo on the camera and looked at the body in the mirror. They’re
                            the same, and yet, 20 minutes ago I would have told you the stomach in the photo is one I’d like to obtain once the weight finally starts to come off. I posed, I
                            did a little dance, I took a few sassy shots between rounds of laughter and I am genuinely amazed at how happy I am with my stomach right now. I keep switching windows
                            between my photo program and the browser, convinced I’m seeing things and yet.... all I see is a happy belly. Thank you so much for what you do.”</li>
                        <li>“Just wanted to say, you guys are awesome for doing this project. I’ve always been self-conscious about my stomach (with an older sister that always teases me about
                            my appearance and brothers that only want perfect women to be associated with them, can you blame me?), but just the fact of looking at this blog and then the pictures
                            of my own stomach makes me realize, hey, I’m normal! In fact, I’m gorgeous! Why did I ever let anybody get me down?”</li>
                    </ul>
                    <br/>We’re ready to take this incredibly powerful project, re-tool it to be even more inclusive and powerful, and re-launch it on its own dedicated URL.
                </p>
                <div class="row">
                    <div class="mx-auto p-1">
                        <a href="https://www.paypal.com/donate/?campaign_id=7KMPGCBPFRKLE" class="btn btn-secondary add-cart" style="color:#fff" target="_blank">DONATE</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FUTURE -->
        <span id="future" class="anchor"></span>
        <div class="box3">
            <div class="bday-txt">
                <p><span class="sketchnote-square">The Future</span>
                    <br/>We feel that this is the right moment—the necessary moment—to revive and update The Belly Project.
                    Seeing the bodies of people who have experienced love, pain, attention, hate, connection, and everything else possible around their abdomens and their
                    reproductive experiences is powerful.</p>
                    <p><i class="fa-solid fa-heart"></i> We need to make public conversions about the private elements of pregnancy, miscarriage, and abortion.</p>
                    <p><i class="fa-solid fa-heart"></i> We need to make public the ways that gender identity intersects with these conversations.</p>
                    <p><i class="fa-solid fa-heart"></i> We need to acknowledge that these are private matters that people aren’t often comfortable having publicly, so they remain faceless.</p>
                    <p><i class="fa-solid fa-heart"></i> We need to acknowledge that our laws are coming ever closer to requiring termination, and thus its intersection with the grief of miscarriage, specifically, to be hidden.</p>
                    <p><i class="fa-solid fa-heart"></i> We need to make art and connection.</p>
                    <p><i class="fa-solid fa-heart"></i> We need you to join us to make this revised, updated project a reality.</p>
                </p>
            </div>
        </div>

        <span id="submit-your-belly" class="anchor"></span>
        <div class="box3">
            @include('fundraisers.thebellyproject.form')
        </div>
    </div>

    <style>
        /*iPhone*/
        @media screen and (max-width: 780px){
            .box1 {
                margin: auto;
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
                margin: auto;
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
        .anchor {
            scroll-margin-top: 125px;
        }
    </style>
</section>
@endsection
