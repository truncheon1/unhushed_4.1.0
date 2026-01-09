@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <span style="font-weight: bold;color:#9acd57">Our Mission & Values</span>
        </div>
    </div>

    <!-- VALUES HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo text-center">Our Mission & Values</p>
                    <hr>
                    <p class="sketchnote-square">Here at UN|HUSHED<span style="font-size:16px; vertical-align:top;">®</span> our mission is to <span style="font-weight:bold; color:#9acd57;">
                    <i>break the silence surrounding human sexuality.</i></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- VALUES BUTTONS-->
    <div class="row justify-content-center">
        <div class="col-auto m-auto py-3">
            <!-- Buttons -->
            <a href="#org" class="org"><button type="button" class="btn btn-secondary p-3" style="width:150px">
                Organizational
            </button></a>
            <a href="#sexual" class="sexual"><button type="button" class="btn btn-secondary p-3" style="width:150px">
                Sexual
            </button></a>
            <a href="#facilitator" class="facilitator"><button type="button" class="btn btn-secondary p-3" style="width:150px">
                Facilitator
            </button></a>
            <a href="#why-sex-ed" class="why-sex-ed"><button type="button" class="btn btn-secondary p-3" style="width:150px">
                Why Sex Ed?
            </button></a>
        </div>
    </div>
    <!-- VALUES CARDS-->
    <div class="row justify-content-center">
        <div class="col-10 m-auto anchor" id="org">
            <div class="tBox1">
                <p class="diazo text-center"><img class="img-s" src="{{ asset('img/octos/ollie-values.png') }}" alt="Org Values"> organizational values</p>
                <p class="sketchnote-square">At UN|HUSHED, we believe:</p>
                <ul>
                    <li>
                        <span class="sketchnote-square details">Rights</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Sexuality education is a basic human right.</b>
                                        It is our mission to provide this education, regardless of the ability to pay, to the greatest degree that we are able.</li>
                                    <li><b>A living wage is a basic human right.</b>
                                        Our employees should earn a wage that allows them to live a full and productive life.
                                        This includes at least three to four weeks of annual PTO, health insurance, and sufficient after-tax income to support themselves in their home area.</li>
                                    <li><b>Sexual rights are necessary.</b>
                                        As a member organization, we stand by the World Association of Sexual Health’s
                                        <a href="https://worldsexualhealth.net/wp-content/uploads/2013/08/Declaration-of-Sexual-Rights-2014-plain-text.pdf" target="_blank">Declaration of Sexual Rights</a> and our own.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Inclusivity</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Everyone’s voice is welcomed, valued, and sought after.</b>
                                        This includes people of all genders, orientations, races, ethnicities, religions, ages, abilities, and more.
                                        We are only able to continue to improve our organization to the degree that we are diverse and inclusive.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Justice</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Social justice and anti-racism are the underpinnings of our work.</b>
                                        Without these elements as our compass, for understanding our work, our impact, and our community, we cannot truly achieve our mission.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Connection</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Transparency and accountability create trust and connection.</b>
                                        These elements raise the quality of organizations’ work.
                                        We are committed to engaging in these practices within our organization because we want to be the change we hope to see in the world at large,
                                        where individuals and organizations aspire to be their best at all times.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Innovation</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>We value and support innovation.</b>
                                        Sexuality education is a constantly evolving field, and to remain relevant to participants of all ages,
                                        sexuality education must transform and revolutionize itself with and revolutionizes itself as the surrounding culture evolves.
                                        UN|HUSHED will strive to remain at the cutting edge of those processes.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Joy</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Both work and education should be beautiful and fun.</b>
                                        We aim to provide employees, facilitators, and participants with experiences that are enjoyable and aesthetically pleasing.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Development</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Individual growth leads to societal evolution.</b>
                                        Professional growth, networking, and collaboration are all vital, as is the ability to make and learn from mistakes.
                                        We support this growth and connection in accountable spaces because it enriches the world in ways that are kind, courageous, and impactful.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Empathy</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>There are many ways to make the world more just, more ecologically viable, and more empathetic.</b>
                                        As a blueprint for healthy human communication and connection, sexuality education is one of them.
                                        UN|HUSHED has a critical place within the global dialogue about improving the human experience.</li>
                                </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-10 m-auto anchor" id="sexual">
            <div class="tBox1">
                <p class="diazo text-center"><img class="img-s" src="{{ asset('img/cards-sm/v_sexual.png') }}" alt="Sexual Values"> sexual values</p>
                <p class="sketchnote-square">At UN|HUSHED, we teach that sexuality should be…</p>
                <ul>
                    <li>
                        <span class="sketchnote-square details">A human right</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Consent is a basic human right.</b>
                                        Sexual activities must be desired and agreed upon. Without consent, sexual contact is violence. Sexual violence includes acts from the abstract, like 
                                        heterosexism, to the concrete, like rape. Sexual violence is anything that infringes on another person’s bodily autonomy in ways that harm them 
                                        physically and psychologically in both the short and long term.</li>
                                    <li><b>Sexuality education is a basic human right.</b>
                                        As all people have bodies and are sexual beings throughout their lives, they have a right to know how their body works so that they are able to 
                                        make decisions about it that are in their best interest.</li>
                                    <li><b>Sexual and reproductive justice are basic human rights.</b>
                                        This includes the right to choose to parent, to not parent, to be pregnant, to not be pregnant. This includes the right to choose sexual activity, 
                                        to not engage in sexual activity, and to specify the kind of sexual activity.</li>
                                    <li><b>Sexual well-being is a basic human right.</b>
                                        Well-being of the body is a necessity for personal, physical, and emotional autonomy. This includes physical and reproductive sexual health as well 
                                        as a very broad understanding of all facets of sexuality.</li>
                                    <li><b>Restricting access to human rights is an act of violence.</b>
                                        This includes access to physical well-being, sexuality education, and reproductive choices. Human rights are a baseline through which we access 
                                        loving, compassionate, higher levels of connection and relationship. </li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Expansive</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Sexuality includes gender, orientation, arousal, attraction, physical and emotional relationships, and so much more.</b>
                                        The concept of sexuality should be contextualized in this very broad way.</li>
                                    <li><b>All people are sexual beings their entire lives.</b>
                                        This is true even when a person, such as an infant or a person who is asexual, does not engage in sexual activities. Because sexuality is such a 
                                        broad topic, its impact reaches far beyond sexual activities.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Inclusive</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>All gender identities and sexual orientations are normal and natural.</b>
                                        The psychological and cultural understanding of sexual identities is expanding. What we know is that human gender, biological makeup, and sexual 
                                        orientation are broad and diverse.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Kind</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Kindness is the core of healthy sexuality.</b>
                                        Because sexuality has the potential to bring such deep joy and pain, all people benefit from increased compassion and empathy. Learning about the 
                                        potential joy and pain in sexuality increases the kindness people show themselves and others.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Pleasurable</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Sexual pleasure is part of a healthy human experience.</b>
                                        It is a common, natural, and functional experience of bodies, and an inherently healthy part of being human.</li>
                                </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-10 m-auto anchor" id="facilitator">
            <div class="tBox1">
                <p class="diazo text-center"><img class="img-s" src="{{ asset('img/cards-sm/v_facilitator.png') }}" alt="Facilitator Values"> facilitator values</p>
                <p class="sketchnote-square">At UN|HUSHED, we believe that a facilitated learning space is…</p>
                <ul>
                    <li>
                        <span class="sketchnote-square details">Distinctive</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Facilitation is interactive.</b>
                                        A facilitator’s goal is to create an environment where participants can learn the content through interaction and engagement, rather than to 
                                        provide specific pieces of information or processes. </li>
                                    <li><b>Participants’ opinions matter.</b>
                                        When participants’ educational needs and interests are considered important, their perspective of the learning environment shifts to become 
                                        one of ownership and engagement. These elements increase buy-in and thus learning outcomes.</li>
                                    <li><b>Fun makes learning more accessible.</b>
                                        When participants are enjoying a learning process, they require fewer repetitions of content. While creating a fun learning environment might 
                                        appear to take longer, it is actually a shorter path to learning.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Expansive</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Participants learn from each other.</b>
                                        Peer-to-peer dialogue offers potential for a special kind of learning and growth. This kind of learning is particularly important in sexuality 
                                        education because participants will navigate sexual activities with their peers, so learning how to talk about sexual things with peers is 
                                        critical.</li>
                                    <li><b>Facilitators are always learning.</b>
                                        When a facilitator has constant curiosity about their content area, they create a dynamic internal environment and bring energy to their 
                                        facilitation that is difficult to achieve in other ways. Within the field of sexuality, which covers a wide range of topics, there is always 
                                        the possibility of learning more.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Nuanced</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>Language is important.</b>
                                        The language that a facilitator uses impacts the feeling of the group and must be taken seriously. Language evolves quickly, and it is okay to 
                                        make mistakes in your words, but it is critical to acknowledge a mistake and work to not make it again.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Respectful</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    <li><b>All questions deserve to be answered.</b>
                                        This is true even when facilitators think that a question is designed to be shocking or is inappropriate in some way. It is the 
                                        facilitator’s responsibility to answer questions in a way that is informational and appropriate given the setting, participants’ 
                                        ages, and content.</li>
                                    <li><b>Facilitators teach boundaries by modeling them.</b>
                                        In sexuality education spaces, participants can cross boundaries among themselves and between them and the facilitator. When this 
                                        happens, the facilitator is able to show participants how to hold their own boundaries in other situations by modeling the process 
                                        of assertive communication.</li>
                                    <li><b>Saying “I don’t know” is one of the most important tools in the facilitator toolbox.</b>
                                        Participants can tell when a facilitator doesn’t know the answer to a question. When a facilitator fabricates even part of an answer, 
                                        they decrease participant trust, which is critical to future learning. When a facilitator says they don’t know, and then finds the answer, 
                                        they model growth and increase participant trust.</li>
                                </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-10 m-auto anchor" id="why-sex-ed">
            <div class="tBox1">
                <p class="diazo text-center"><img class="img-s" src="{{ asset('img/cards-sm/v_why.png') }}" alt="Why Sex Ed?"> why sex education?</p>
                <p class="sketchnote-square">Lack of quality human sexuality education contributes to:</p>
                <ul>
                    <li>
                        <span class="sketchnote-square details">Child marriage.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                    UNICEF reports that 5% of the world’s girls are married by age 15 and 21% by age 18 (these numbers are 12% and 40%, respectively, in the least developed countries).
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Intimate partner violence.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                The WHO reports that 30% of all women experience physical or sexual violence from a partner, with the numbers ranging from 23% in high-income countries to 38% in some low-income countries. That these numbers are so much higher than the incidence of violence against women by a non-partner (7%) clarifies that violence against women is primarily a relationship-specific issue.
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">STI transmission.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                The WHO estimates that there are 357 million combined new infections of chlamydia, gonorrhea, syphilis, and trichomoniasis every year, 500 million people living with herpes, and 290 million women infected with HPV, and UNAIDS estimates that there are currently 37 million people living with HIV.</li>
                                </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Stigma, bigotry, and violence based on gender identity and sexual orientation.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                The Williams Institute reported that out of 141 studied countries, 57% have experienced an increase in acceptance of LGBTQ+ people since 1980 while 33% experienced a decline in acceptance. However, FBI data from 2016 indicates that even in the US, which has seen a substantial increase in acceptance, LGBTQ+ people experience the highest rate of hate crimes of any minority group, twice that of people of color.</li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Unwanted pregnancy.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                The Guttmacher Institute estimates that 44% of pregnancies worldwide are unintended, 25% of all pregnancies were ended through induced abortion, and 45% of all induced abortions are unsafe.</li>
                            </ul>
                        </details>
                    </li>
                </ul>
                <p class="sketchnote-square pt-5">Where other curricula falls short:</p>
                <ul>
                    <li>
                        <span class="sketchnote-square details">They’re emotionally manipulative.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                Participants are likely to shut down and not take in any information when a curriculum tries to emotionally manipulate them into particular behaviors (like abstinence-only or abstinence-plus curricula). If you tell someone that they will contract an STI the first time they have unprotected sex, and that participant has already engaged in that behavior, then they know you are using scare tactics instead of medically accurate information. Why should they then believe anything you have to say?</li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">They focus exclusively on teenagers.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                Yes! Teenagers need human sexuality education. And all people need to have access to information about sexuality across their entire lives. In school, we don’t expect children to pick up a college level book and read it cover to cover. Rather, we nurture their love of reading from board books up to high school literature classes and hope that they will continue to read for learning and enjoyment through adulthood. Why, then, do we expect people to have safe consensual sexual relationships without any—or very brief at best—sexuality eductation?</li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">They’re not culturally relevant.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                Most curricula are required to be taught with absolute fidelity, which ignores individuals’ and communities’ educational needs. The reality is such that different groups need more information on specific topics than others. Ignoring this is a diservice to the communities and individuals we are trying to educate.</li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">They’re not inclusive.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                LGBTQ+ identities are either left out entirely or are relegated to identity-specific units rather than being interwoven throughout all content areas. These methods help reinforce heteronormativity, rather than inclusion and acceptance across the spectrum. Intersex persons are not included and biological sex is only discussed in a binary. Leaving out these members of our society is physically and emotionally harmful.</li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">They’re not trauma-informed.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                Teaching topics such as sexual violence without being trauma-informed has the potential to actively harm and re-harm participants. Because there is such an emotional component tied to sexuality, there is also an emotional component tied to teaching about it. It seems like such a simple concept, and yet it is not always accounted for in other curricula.</li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <span class="sketchnote-square details">Their facilitators lack information.</span>
                        <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                            </summary>
                                <ul class="values">
                                This prompts them to provide incorrect or outdated information to students.
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .anchor {
            scroll-margin-top: 125px;
        }
        .img-s {
            width: 130px;
            height: auto;
        }
        .vImg1, .vImg2, .vImg3 {
            background: linear-gradient(180deg, rgba(240,243,250,1) 0%, rgba(1,167,211,1) 100%);
            -moz-box-shadow:    4px 5px 6px 4px #ccc;
            -webkit-box-shadow: 4px 5px 6px 4px #ccc;
            box-shadow:         4px 5px 6px 4px #ccc;
            width: 180px;
            height: 180px;
            border-radius: 100px;
            overflow: hidden;
            margin: 10px;
            padding-top: 20px;
            color: #01a7d3;
            font-weight: bolder;
            text-transform: uppercase;
            text-align: center;
            font-size: 14px;
            position: relative;
            top: 0;
            transition: top ease 0.1s;
        }
        .vImg1:hover, .vImg2:hover, .vImg3:hover {
            top: -5px;
            color: #265a8e;
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
            content: '...';
            font-size: 60px;
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
            color: #696969;
            padding-left: 20px;
            font-size: 18px;
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

        if(window.location.hash == '#values' ) { 
            var valueVarName = document.querySelector('#sexual'); // theTabID of the tab you want to open
            var tab = new bootstrap.Tab(valueVarName);
            tab.show();
        }

        var triggerEl = document.querySelector('#sexual button[data-bs-target="#sexual"]')
        bootstrap.Tab.getInstance(triggerEl).show()
    </script>
</section>
@endsection
