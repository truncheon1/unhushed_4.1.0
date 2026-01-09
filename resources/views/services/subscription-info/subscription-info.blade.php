@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Our Curricula</span>
        </div>
    </div>

    <!-- ABOUT HEADER-->
    <div class="row" style="padding:35px 0;">
        <div class="col-12 m-auto pb-2">
            <p class="diazo text-center" style="padding-top:5px;">LET'S TALK K-12 CURRICULA</p>
        </div>
        <div class="col-12 text-center">
            <p class="sketchnote-square">We won't waste your time being modest, our curricula is the best money can buy!</p>
            <p><a href="{{ url($path.'/curricula') }}"><i class="fas fa-shopping-cart"></i> Ready to purchase?</a> &nbsp; &nbsp; or &nbsp; &nbsp; <a href="{{ url($path.'/c-review') }}"><i class="fa-solid fa-magnifying-glass"></i> Request access to review</a>.
                <!-- <br>Haga clic aquí para español. -->
            </p>
        </div>
        <div class="col-12 text-center">
            <!-- Modal trigger ES-->
            <button type="submit"  class="btn" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#c_es">
                <div class="imgCBox">
                    <img class="imgC" src="{{ asset('img/cards-sm/d_es.png') }}" alt="Elementary School"></a>
                    <div class="imgC-text">Elementary School</div>
                </div>
            </button>
            <!-- Modal popup-->
            <div class="modal fade" id="c_es" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @include('services/subscription-info/modal-es')
                    </div>
                </div>
            </div>
            <!-- Modal trigger MS-->
            <button type="submit"  class="btn" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#c_ms">
                <div class="imgCBox">
                    <img class="imgC" src="{{ asset('img/cards-sm/d_ms.png') }}" alt="Middle School"></a>
                    <div class="imgC-text">Middle School</div>
                </div>
            </button>
            <!-- Modal popup-->
            <div class="modal fade" id="c_ms" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @include('services/subscription-info/modal-ms')
                    </div>
                </div>
            </div>
            <!-- Modal trigger HS-->
            <button type="submit"  class="btn" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#c_hs">
                <div class="imgCBox">
                    <img class="imgC" src="{{ asset('img/cards-sm/d_hs.png') }}" alt="High School"></a>
                    <div class="imgC-text">High School</div>
                </div>
            </button>
            <!-- Modal popup-->
            <div class="modal fade" id="c_hs" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @include('services/subscription-info/modal-hs')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center pt-3">
            <p>You can click each level for a full activities list.</p>
        </div>
    </div>

    <!-- Misson & Values -->
    <div class="row" style="padding:50px 0; background:#f0f3fa;">
        <div class="col-12 mx-auto my-auto pb-2">
            <h3 style="text-align:center;">WHAT MAKES UN|HUSHED THE BEST YOU ASK?</h3>
        </div>
        <div class="col-6 mx-auto my-auto" style="font-size:18px; text-align:justify;">
            <p>
                <ul><p class="sketchnote-square">Because our curricula is:</p>
                    <li class="p-3">
                        <span class="details pl-3"><b>Digitally accessible & ready out of the box</b></span>
                        <details>
                            <summary>
                                <span id="open" class="details pl-3"></span>
                                <span id="close">[ close <i class="fa-solid fa-square-chevron-up"></i> ]</span>
                            </summary>
                            <ul class="values">
                                <li>Get rid of expensive out of date textbooks. Our K-12 comprehensive curricula is an annual subscription you access through your UN|HUSHED account. 
                                    You can start with a <a href="{{ url($path.'/register') }}" target="_blank">free account</a> and get a ton of amazing handouts and some cool sample 
                                    activites. With a <a href="{{ url($path.'/curricula?s[]=3') }}" target="_blank">paid account</a> you can access the curriculum as pdfs, 
                                    powerpoint/google slides, online games, and videos. Our curriculum is designed to be taught by adults to participants in person (and we have suggestions 
                                    and modifications outlined for teaching via synchronous online classes). Because our curricula is ready out of the box, it means all of the 
                                    materials are provided for you, and any skill level of sexuality educator can use them right away.
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li class="p-3">
                        <span id="medically" class="details pl-3 anchor"><b>Evidence informed & medically accurate</b></span>
                        <details>
                            <summary>
                                <span id="open" class="details pl-3"></span>
                                <span id="close">[ close <i class="fa-solid fa-square-chevron-up"></i> ]</span>
                            </summary>
                            <ul class="values">
                                <li>Our curricula has been in use and evolving since 2008, and includes medically accurate and pedagogically sound research.
                                    However, unlike evidence based curricula, our Medical, Legal, and Curricular Advisory Councils review, and we update, our curricula at least annually. You can read more 
                                    <a href="{{ url('https://blog.unhushed.org/evidence-informed-vs-evidence-based/') }}" target="_blank">here</a>.
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li class="p-3">
                        <span class="details pl-3"><b>Flexible, modular, & participant focused</b></span>
                        <details>
                            <summary>
                                <span id="open" class="details pl-3"></span>
                                <span id="close">[ close <i class="fa-solid fa-square-chevron-up"></i> ]</span>
                            </summary>
                            <ul class="values">
                                <li>Our curricula are designed to provide facilitators with an engaging, modular program—which means you can teach UN|HUSHED whether 
                                    you have 20 mins or 20 hours with your participants. The curricula is broken up into units. Within those units are sessions, and within the 
                                    sessions are activites. We break our activites down so you know how many minutes it takes to teach each one. Then you pick and choose which 
                                    ones you have time for, engage your community, and fullfill your participants educational needs.
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li class="p-3">
                    <span id="inclusive" class="details pl-3 anchor"><b>Inclusive</b></span>
                        <details>
                            <summary>
                                <span id="open" class="details pl-3"></span>
                                <span id="close">[ close <i class="fa-solid fa-square-chevron-up"></i> ]</span>
                            </summary>
                            <ul class="values">
                                <li>One of our <a href="{{ url($path.'/values') }}" target="_blank">organizational values</a> is inclusivity. This is reflected throughout our curricula. 
                                    We use inclusive language throughtout, not just in the activities specifically about identities. The high school curriculum uses our social justice 
                                    compass to inform activities and dialogue in every content area. We aim to create space for people of every body type, skin color, disability, 
                                    expression, gender identity, genetic makeup, national origin, race, religion, and sexual orientation.
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li class="p-3">
                    <span id="upToDate" class="details pl-3 anchor"><b>Innovative & radically up-to-date</b></span>
                        <details>
                            <summary>
                                <span id="open" class="details pl-3"></span>
                                <span id="close">[ close <i class="fa-solid fa-square-chevron-up"></i> ]</span>
                            </summary>
                            <ul class="values">
                                <li>We strive to be on the cutting edge of our field, as evidenced by winning the 
                                    <a href="https://worldsexualhealth.net/was-awards/" target="_blank">2021 World Association for Sexual Health Award for 
                                        Excellence & Innovation in Sexuality Education</a>. Part of that means we care deeply about being the most up to date, and so we update our 
                                        curricula at least once a year! We have a dedicated team of <a href="{{ url($path.'/team') }}" target="_blank">advisory councils</a> who review our 
                                        materials through the lens of their field (curricula, legal, medical) and then the UN|HUSHED team updates the materials you subscribe to online. 
                                        And because we update so frequently, if there is something you need to effectively facilitate this program, just ask, we'll create it!
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </p>
        </div>
    </div>

    <!-- FAQs -->
    <div class="row" style="padding-top:50px;">
        <div class="col-12 mx-auto my-auto pb-2">
            <h3 style="text-align:center;"><i class="fa-info-circle fa-fw"></i> FAQS</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mx-auto" style="text-align:justify; font-size:18px;">
            <p class="sketchnote-square" style="align:cetner;">What does evidence informed mean?</p>
            <p>
                Great questions! Our curricula has been in use and evolving since 2008, and includes medically accurate and pedagogically 
                sound research. However, unlike "evidence based" curricula, our councils review and we update our curricula annually. 
                <a href="{{ url('https://blog.unhushed.org/evidence-informed-vs-evidence-based/') }}" target="_blank">You can read more here</a>.
            </p><br/>
            <p class="sketchnote-square" style="align:cetner;">What does modular mean?</p>
            <p>
                UN|HUSHED curricula are designed to provide facilitators with an engaging, modular program—which means you can teach UN|HUSHED whether 
                you have 20 mins or 20 hours with your participants. Everyone comes away from the program with an expanded ability to think and talk 
                about sexuality in healthy, diverse, and open ways. Choose a level above to learn more.
            </p><br/>
            <p class="sketchnote-square" style="align:cetner;">Is this an online curricula?</p>
            <p>
                Our curricula is a collection of downloadable files accessed via an online portal by facilitators. Those facilitators then create a 
                program that can be taught online or in person. We provide everything from the activity timing, Powerpoints/Google Slides, handouts, 
                example permission forms, to playable online games. And because we update so frequently, if there is something you need to effectively 
                facilitate this program, just ask, we'll create it!
            </p><br/>
            <p class="sketchnote-square" style="align:cetner;">Does it meet my state's/country's standards?</p>
            <p>
                UN|HUSHED adheres to international guidelines and technical guidance on what constitutes the most effective comprehensive sexuality education.
                <a href="{{ url($path.'/about#standards') }}">We meet or exceed these education standards.</a>
            </p><br/>
            <p class="sketchnote-square" style="align:cetner;">Have another questions?</p>
            <p><a href="{{ url($path.'/contact') }}" target="_blank">Contact Us</a></p>
        </div>
    </div>

    <style>
        .anchor {
            scroll-margin-top: 125px;
        }
        p {
            font-size:18px;
        }
        .modal-open, .modal, .btn:focus, a:focus {
            outline:none!important
        }
        h2, details {
            display:inline;
        }
        details {
            position:relative;
        }
        details summary {
            display:block;
            cursor: pointer;
            color: #265a8e;
        }
        details summary:focus {
            outline:none;
        }
        details[open] {
            display:block;
            padding-bottom:25px;
            padding-top:10px;
            animation: open .2s linear;
        }
        details[open] summary {
            position:absolute;
            bottom: 0;
            left:0;
        }
        details #open {
            display: inline-block;
            position: relative;
            color: #01a7d3;
        }
        details #open:after {
            content: '[ read more ]';
            font-size: 14px;
            line-height: 0;
            color: #01a7d3;
            padding: 0;
            position: relative; 
        }
        details #open:hover {
            color: #265a8e;
        }
        details[open] #open{display:none;}
        details #close{
            display:none;
            color: #01a7d3;
            padding-left: 20px;
            font-size: 14px;
        }
        details[open] #close{display:block;}
        ::-webkit-details-marker {display: none;}
        @keyframes open {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .values {
            font-size: 18px;
            padding-left: 20px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            const hashString = window.location.hash;
            if(hashString.length){
                $("."+hashString.substr(1)).trigger('click');
            }
        })
        $("[href^='#medically']").on("click", function() {
            var $targetSpan = $(this.getAttribute("href"));
            if ($targetSpan.is(":hidden")) {
                $targetSpan.closest("details").prop("open", true);
            }
        });
        $("[href^='#upToDate']").on("click", function() {
            var $targetSpan = $(this.getAttribute("href"));
            if ($targetSpan.is(":hidden")) {
                $targetSpan.closest("details").prop("open", true);
            }
        });
        $("[href^='#inclusive']").on("click", function() {
            var $targetSpan = $(this.getAttribute("href"));
            if ($targetSpan.is(":hidden")) {
                $targetSpan.closest("details").prop("open", true);
            }
        });
    </script>
</section>
@endsection


