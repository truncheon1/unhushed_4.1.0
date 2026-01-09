@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <a href="{{ url($path.'/free-activities') }}">Activities</a> |
            <span style="font-weight: bold;color:#9acd57">Explaining Abortion</span>
        </div>
    </div>

    <!-- ACTIVITY HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <!-- Title -->
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/abortion.png') }}" alt="Explaining Abortion">
                </div>
                <div class="col-10 mx-auto px-5 py-1 bg-white text-left">
                    <p><b>What's included?</b>
                        <ul class="green">Start here
                            <li class="pl-3"><a href="#what-happened">What Happened: Friday, June 24th, 2022</a></li>
                            <li class="pl-3"><a href="#checking-in-with-yourself">Before You Begin: Check in With Yourself</a></li>
                        </ul>
                        <ul class="green">Continue with the age(s) of your child/ren
                            <li class="pl-3"><a href="#preschool-to-2nd-grade">Guidelines for talking with your kids: Preschool-2nd grade</a></li>
                            <li class="pl-3"><a href="#3rd-grade-to-6th-grade">Guidelines for talking with your kids: 3rd Grade-6th Grade</a></li>
                            <li class="pl-3"><a href="#middle-schoolers">Guidelines for talking with your kids: Middle Schoolers</a></li>
                            <li class="pl-3"><a href="#high-schoolers">Guidelines for talking with your kids: High Schoolers</a></li>
                        </ul>
                    </p>
                </div>
                <!-- Intro -->
                <span id="what-happened" class="anchor">
                    <div class="col-10 mx-auto bg-white">
                        <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/intro.png') }}" alt="What Happened: Friday, June 24th, 2022">
                    </div>
                    <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                        <p> On Friday, June 24th, 2022,, the Supreme Court overturned Roe v. Wade and Planned Parenthood v. Casey, finding that there is no constitutional right to an abortion and that the issue
                            should be sent back to the states. What this means for a person's right to obtain an abortion will depend on the laws of their state. Some states, such as Texas,
                            Arkansas, Kentucky, and several others, have already passed a “trigger” law to go into effect upon the Court’s opinion overturning Roe passing significant
                            restrictions on or outright banning abortion.</p>

                        <p>This decision is concerning for many other rights conveyed through judicial opinions that are not specifically named in our Constitution. For instance, Justice
                            Thomas stated in his part of the opinion that other Supreme Court opinions such as Lawrence, Obergefell, and Griswold should also be reconsidered and possibly
                            overturned on similar grounds as those discussed in Dobbs today. These decisions dealt with private sexual acts, same-sex marriage, and right to contraceptives,
                            respectively.</p>
                    </div>
                </span>
                <!-- Before You Begin: Checking in With Yourself -->
                <span id="checking-in-with-yourself" class="anchor">
                    <div class="col-10 mx-auto bg-white">
                        <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/before.png') }}" alt="Why talk about coronavirus? And how?">
                    </div>
                    <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                        <p><b>Hold space for yourself</b><br/>
                            In the wake of this decision, many of us are overwhelmed with worry, anger, and despair. You deserve the time and space to acknowledge these feelings.
                            You have permission to take your time with these conversations. Wait a few days if you need to. Take breaks. Go for a walk, talk with a friend, or maybe start a
                            new hobby.
                        </p>
                        <p><b>Seek out support</b><br/>
                            Regardless of your stance on abortion, many people feel very lonely right now. Connecting with friends, religious professionals, therapists, and the networks of
                            people who have been preparing for this. (Some great networks to start with are the National Network of Abortion Funds and the United State of Women.)
                        </p>
                        <p><b>Seek out information</b>
                            <br/>Here are some helpful links to sites that provide medically/legally accurate information:
                            <ul class="green">
                                <li class="pl-3">Abortion Facts:</li>
                                    <li class="two"><a href="https://www.plannedparenthood.org/learn/abortion">Planned Parenthood’s page on, "Abortion"</a></li>
                                    <li class="two"><a href="https://prochoice.org/">The National Abortion Federation's website</a></li>
                                <li class="pl-3">More details about how this may impact state's laws.</li>
                                    <li class="two"><a href="https://reproductiverights.org/maps/what-if-roe-fell/">The Center for Reproductive Rights’s page, “What if Roe Fell?”</a>
                                </li>
                            </ul>
                        </p>
                        <p><b>Identify your own thoughts and feelings about abortion</b><br/>
                            Take the time to journal, talk with friends, or otherwise consider how you feel about abortion. Here are some questions that might be useful to be part of that
                            process:
                            <ul class="green">
                                <li class="pl-3">How do I think about abortion as a personal topic, related to myself and those closest to me?</li>
                                <li class="pl-3">How do I think about abortion as a political topic, related to the legal and medical systems of this country?</li>
                                <li class="pl-3">What are my biggest feelings about abortion? </li>
                                <li class="pl-3">How would I feel talking about all of these things with my child/ren?</li>
                            </ul>
                        </p>
                        <p><b>Speak with other important adults in your child’s life</b>
                            <br/>Who, other than you, might your child want to talk with about abortion? Who, other than you, might want to talk with your child? Chatting how you might all
                            handle these conversations will help you prepare yourself and your child for balancing different perspectives, different needs, and different feelings.
                        </p>
                        <p><b>Identifying your own boundaries</b><br/>
                            Your story is your own. Your child may ask you about personal experiences you’ve had around abortion and pregnancy. You may choose to share that
                            information, or you may want to model healthy boundary setting and choose not to share. Your choice of what and how much to share may be shaped by your child’s
                            age, your environment, and your own comfort and safety.</p>
                        <p>If your child asks these questions of you, how would you like to respond?
                            <ul class="green">
                                <li class="pl-3">Q: Have you or someone you know ever had an abortion?</li>
                                <li class="pl-3">Q: What does an abortion feel like?</li>
                                <li class="pl-3">Q: Was your pregnancy with me planned?</li>
                            </ul>
                        </p>
                        <p>Here’s some example language of how you might want to answer that first question.
                            <ul class="green">
                                <li class="pl-3"><i>Sharing now:</i>
                                    <br/>“Yes, I had an abortion.”
                                    <br/><b>NOTE:</b> It’s best to keep your answers as succinct as possible. If your child wants to know more, they may ask follow-up questions.
                                    But providing them with too many extra details can quickly become overwhelming for your child and for you.
                                </li><br/>
                                <li class="pl-3"><i>Sharing later:</i>
                                    <br/>“That’s a very good question. I’m not sure how I want to answer that right now. Why don’t you help me make dinner?
                                    I’ll think about it while we cook and eat, and then we can talk about it afterwards.”<br/>
                                    <br/>“That’s a fantastic question. I am not ready to share that information with you right now. I may be ready when you are a few years older.
                                    Do you have any other questions?”
                                </li><br/>
                                <li class="pl-3"><i>Choosing not to share:</i>
                                    <br/>“That’s a great question. I’m very happy to talk through the facts about abortion with you, but I am not comfortable talking about my own story
                                    with you. I can tell you that about 1 in 4 people who can get pregnant will have an abortion.”
                                </li>
                            </ul>
                        </p>
                        <p><b>Start the converstaion with your child/ren</b><br/>
                            Click on the link below that best fits your child/ren's age(s).
                            <ul class="green">
                                <li class="pl-3"><a href="#preschool-to-2nd-grade">Guidelines for talking with your kids: Preschool-2nd grade</a></li>
                                <li class="pl-3"><a href="#3rd-grade-to-6th-grade">Guidelines for talking with your kids: 3rd Grade-6th Grade</a></li>
                                <li class="pl-3"><a href="#middle-schoolers">Guidelines for talking with your kids: Middle Schoolers</a></li>
                                <li class="pl-3"><a href="#high-schoolers">Guidelines for talking with your kids: High Schoolers</a></li>
                            </ul>
                    </div>
                </span>
                <!-- Guidelines for talking with your kids: Ages 3-2nd Grade -->
                <span id="preschool-to-2nd-grade" class="anchor">
                    <div class="col-10 mx-auto bg-white">
                        <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/preschool-to-2nd-grade.png') }}" alt="Guidelines for talking with your kids: Preschool-2nd grade">
                    </div>
                    <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                        <p><b>What is age appropriate?</b>
                            <br/>Children in this age group are constantly being introduced to the wonders of birth and growth—from chicken eggs and tadpole legs, to a baby sibling, or their very
                            own development. They are often ready to have conversations about the basics of human reproduction, pregnancy, and abortion.</p>
                        <p>While talking to children this young about abortion may feel daunting, remember that a four-year-old will be approaching this conversation without the heavy weight
                            of personal experience or cultural context. Learning the definition of abortion will most likely have less of an impact on their day than the discovery of a new
                            crayon color or animal sound. However, introducing these concepts early can build a foundation for more complex conversations as they grow and develop. They also
                            let your child know that you are open to conversations about pregnancy and abortion if they have any questions.</p>
                        <p><b>Before you begin: Have you talked about pregnancy?</b>
                            <br/>To understand abortion, a child must first understand the basics of reproduction and pregnancy. One of the easiest ways to start these conversations is by
                            reading books together that tackle the topic. Not only do they provide helpful information and visuals for your kids, but it can also provide you with examples
                            of child-friendly language to complex questions. Here’s a few book recommendations to get you started:
                            <ul class="green">
                                <li><a href="https://www.goodreads.com/book/show/15701778-what-makes-a-baby" target="_blank">
                                    <i>What Makes a Baby?</i></a> by Cory Silverberg
                                    <br/>Best for: Ages 3-5. Ideal for kids who are more narrative-focused and who generally do well with some abstract representations.</li>
                                <li><a href="https://www.goodreads.com/en/book/show/14850532-what-s-in-there" target="_blank">
                                    <i>What’s in There?: All About Before You Were Born</i></a> by Robie Harris
                                    <br/>Best for: Ages 3-5. Ideal for the “encyclopedia kid” who wants all the specifics.</li>
                                <li><a href="https://www.goodreads.com/book/show/312200.It_s_Not_the_Stork_?from_search=true&from_srp=true&qid=5aAuixeKwk&rank=1" target="_blank">
                                    <i>It’s Not The Stork!</i></a> by Robie Harris
                                    <br/>Best for: Ages 5-7
                                </li>
                            </ul>
                        </p>
                        <p><b>Starting the Conversation</b>
                            <br/>You can start by reading the phenomenal book
                            <a href="https://www.goodreads.com/book/show/60471345-what-s-an-abortion-anyway" target="_blank"><i>What’s an Abortion, Anyway?</i></a>, written by Carly Manes and
                            illustrated by Emulsify. It provides not only a clear definition of abortion, but also discussion around why someone might chose to terminate a pregnancy.</p>
                        <p><b>Continuing the Conversation</b>
                            <br/>Don’t feel pressured to cover every detail in one sitting. It’s great if they walk away with a working definition of abortion, but it’s even better if they leave
                            knowing you love them and are open to their curiosity. Ideally, this will be an evolving conversation you will revisit throughout their life.</p>
                        <p>Example questions and answers:
                            <ul class="green">
                                <li>Q: How are babies made?</li>
                                <li class="two"><i>Answer:</i> To make a baby, you need three things—an egg, a sperm, and a uterus. The egg, which is about the size of a teeny tiny pencil dot, almost
                                    always comes from someone with a vulva. The egg that helped make you came from _____. The sperm almost always comes from someone with a penis and testicles.
                                    The sperm that helped make you came from ______. When the egg and sperm meet, they combine and are ready to start growing. But they need a place to grow.
                                    <br/><br/>
                                    So they go to the uterus, which is a body part that most people with a vulva have. It’s inside a person's body where you can’t see it, just like your
                                    stomach or your lungs. The uterus you grew in is inside _______. The time the combined sperm and egg spend growing in the uterus is called a pregnancy.
                                </li><br/>
                                <li>Q: What is a miscarriage?</li>
                                <li class="two"><i>Answer:</i> A miscarriage is when a pregnancy ends before a baby can grow and be born. This can happen for a lot of different reasons. Often, that combined
                                    egg and sperm weren’t healthy and couldn’t grow. This can be very sad for people who want to have a baby.</li><br/>
                                <li>Q: What is an abortion?</li>
                                    <li class="two"><i>Answer:</i> An abortion is when someone choses to end a pregnancy before a baby can grow and be born. They usually do this with the help
                                    of medical providers. People can choose to do this for lots of reasons. Sometimes, an abortion may be important to keep the person with a uterus healthy
                                    and safe. Other times, someone may choose to end a pregnancy because they are not prepared to give a baby all the love and safety they deserve.
                                </li>
                            </ul>
                        </p>
                    </div>
                </span>
                <!-- Guidelines for talking with your kids: 3rd Grade-6th Grade -->
                <span id="3rd-grade-to-6th-grade" class="anchor">
                    <div class="col-10 mx-auto bg-white">
                        <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/3rd-grade-to-6th-grade.png') }}" alt="Guidelines for talking with your kids: Ages 3-2nd Grade">
                    </div>
                    <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                        <p><b>What is age appropriate?</b>
                            <br/>By third grade, most kids are ready to start learning more specifics about puberty and reproduction. Conversations around abortion will be much easier to navigate
                            if your child has basic understanding of conception, pregnancy, and the menstrual cycle.</p>
                        <p><b>Before you begin: Have you talked about pregnancy?</b>
                            <br/>If you haven’t started talking about these concepts yet, here are a few resources to get you started:
                            <ul class="green">
                                <li><a href="https://www.goodreads.com/book/show/222507.It_s_Perfectly_Normal" target="_blank">
                                    <i>It’s Perfectly Normal</i></a> by Robie Harris</li>
                                <li><a href="https://amaze.org/" target="_blank">AMAZE videos</a> by Amaze.org</li>
                                <li><a href="https://sexedtalk.com/popup" target="_blank">
                                    <i>Vaginas and Periods 101</i></a> by Christian Hoeger and Kristin Lilla</li>
                            </ul>
                        </p>
                        <p><b>Starting the Conversation</b>
                            <br/>This conversation can be intense for both you and your child, so it can help to chat while you do an activity together. You could go for a walk, do a puzzle,
                            or fold some laundry. Start by asking them if they’ve heard about the recent supreme court decision about abortion. Let them know that this is an important topic
                            to you, and you would like for them to be able to come to them with any questions or concerns they may have. </p>
                        <p>Your child has likely already heard messages about abortion - whether it was from you, the media, family, friends, or other members of their community. As you
                            continue the conversation, it’s a good idea to ask them lots of questions and give them a chance to demonstrate their expertise. Some questions you may ask are:
                            <ul class="green">
                                <li>Q: What do you know about abortion?</li>
                                <li>Q: Do you know any of the opinions people have about abortion?</li>
                                <li>Q: What do you think about abortion?</li>
                            </ul>
                        </p>
                        <p>Let them know you’d like to watch a video together. While this <a href="https://amaze.org/video/pregnancy-reproduction-what-is-abortion/" target="_blank">video by AMAZE</a> predates the recent Dobbs v. Jackson decision, it provides a good explanation on what
                            abortion is, how it is accesed, and some of the opinions people have about it. You can follow this up with some further discussion. Some questions you may ask
                            include:
                            <ul class="green">
                                <li>Q: Did you learn anything new from that video?</li>
                                <li>Q: Was there anything in this video you found confusing?</li>
                                <li>Q: Do you have any more questions?</li>
                            </ul>
                        </p>
                        <p><b>Continuing the Conversation</b>
                            <br/>You can follow your child’s lead when it comes to expanding on this topic. They may be satisfied with the basic information and ready to move onto the next topic.
                            However, if your child has more questions or expresses an interest in continuing this conversation, try exploring some of the following resources together:</p>
                            <ul class="green">
                                <li><i>For more information about the procedures:</i></li>
                                    <li class="two">Planned Parenthood has videos and fact sheets on both
                                        <a href="https://www.plannedparenthood.org/learn/abortion/the-abortion-pill" target="_blank">medication abortions</a> and
                                        <a href="https://www.plannedparenthood.org/learn/abortion/in-clinic-abortion-procedures" target="_blank">in-clinic abortions</a>. The videos are very clear
                                        and provide some helpful visual representations.</li>
                                <br/>
                                <li><i>For more information about people who receive abortions:</i></li>
                                    <li class="two">Teen Vogue has a collection of <a href="https://www.teenvogue.com/story/abortion-stories" target="_blank">39 stories</a>
                                        from people who received abortions. You can read through the stories yourself first. They vary widely in not
                                        only experiences depicted, but in their level of detail and complexity of language. Choose two or three stories to print out and read together with your
                                        child.</li>
                                    <li class="two"><a href="https://www.nytimes.com/interactive/2021/12/14/upshot/who-gets-abortions-in-america.html?unlocked_article_code=pl6r1jdEEbpHBA_1DXin-cTDiO2ocs4cwcfsmgiujXUy9vlYZzgVJwHadMDcaBwksSQzmm-eq0WkPpzcbCYrnaTvbpZhtTGUq6sF7wQXdE3QAW3eF-WFvg1IXhcpQtVX6WNEoVF6MIm0IvW2tg1PPb3Q2PSXjwxSNmNqZT3gFTq_NO_dGPiCNSpr7YvgLnrZE1fj63VVnHQInM1E6nygCpNAnjdgTPfh6czyjVJ-p6_VC58l1xpwR4fruTyZSGR8iW3YESDdNzY3OHxk2WInPf8h7CG1f4DeL34GqpbT7lznc8aNu5-b6KleMP4c9vyzE9AHop__Xe1P5wgsM3i5NlvoabKlE-LogQf4AhUdul5OAjQarBw&smid=url-share" 
                                        target="_blank">“Who Gets Abortions in America?”</a>
                                        by the New York Times includes research  findings and some helpful graphs. If you have a particularly numbers-oriented child, this may be a good article
                                        to explore together.</li>
                                <br/>
                                <li><i>For more conversations around reproductive justice:</i></li>
                                    <li class="two">Search for images of “reproductive justice protest signs.” Save 3-5 of them to discuss with your child. Here are some questions you might
                                        ask:</li>
                                        <li class="three">Q: What do you think this sign means?</li>
                                        <li class="three">Q: Why do you think someone made this sign?</li>
                                        <li class="three">Q: If you could make any changes to this sign, what would it be?</li>
                            </ul>
                        </p>
                    </div>
                </span>
                <!-- Guidelines for talking with your kids: Middle Schoolers -->
                <span id="middle-schoolers" class="anchor">
                    <div class="col-10 mx-auto bg-white">
                        <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/middle-schoolers.png') }}" alt="Guidelines for talking with your kids: Middle Schoolers">
                    </div>
                    <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                        <p><b>What is age appropriate?</b>
                            <br/>Early adolescents will have a wide range of access to (and interest in) news sources outside of their immediate circle of family and friends.</p>
                        <p><b>Starting the Conversation:</b><br/>
                            <i>If your child <b>is not interested in or aware of</b> the news:</i>
                            <ul class="green">
                                    <li>You still need to talk with them. Middle schoolers will eventually hear about these issues. They are already making their way through places that have
                                    otherwise been nonpolitical. If your child hears about the conversation from other places, but not from you, they may not realize that you are an avenue for
                                    dialogue about what might be a very confusing, very emotional topic that they are ill-prepared to deal with alone. You do not need to go into a lot of depth if
                                    they don’t show much interest in the conversation, but you do need to provide information, give them an opportunity to consider their own beliefs, and to share
                                    your beliefs.</li><br/>
                                    <li>Here is some example language:</li>
                                        <li class="two">“There was a big change in abortion rights recently. What do you know about abortion?”</li>
                                        <li class="two">“When I think about abortion, I feel ______. How about you?”</li>
                                        <li class="two">“I believe ________ about abortion. What do you believe about it?”</li>
                                    </li>
                            </ul>
                        </p>
                        <p><i>If your child <b>is interested in or aware of</b> the news:</i>
                            <ul class="green">
                                <li>You need to bring up the conversation and engage with your child on the topic. This will likely involve conversations about religion, life,
                                    spirituality, the legal system, trust, distrust, fears, and more. Be willing to sit with questions and uncertainty. Be willing to read the news
                                    articles that they bring and to look at their social media together. Middle schoolers, even politically savvy ones, may not understand the implications
                                    of Thomas’ concurring opinion that raises questions about Griswold (protects the right to contraception), Lawrence (protects gay relationships), and
                                    Obergafell (protects gay marriage). Be open to these elements of the dialogue (particularly as they relate to your child and your child’s friends and
                                    peers) as and when they arise.<br/>
                                </li><br/>
                                <li>Here is some example language:</li>
                                    <li class="two">“How are you feeling about what the Supreme Court said about abortion last week?”</li>
                                    <li class="two">“Do you have any questions about the decision statements? I’d be happy to try and find answers to your questions together.”</li>
                                </li>
                            </ul>
                        </p>
                        <p><b>Continuing the Conversation:</b>
                            <br/>Work together to make choices about putting the news and social media down to be together. Putting away the outside world may still involve
                            conversations about the issues, and that’s okay for a little while. It should also eventually move into activities or conversations that bring joy.
                            If the conversation returns to the Supreme Court, that’s also okay, as long as you are taking breaks from it together.
                            <ul class="green">
                                <li>Here is some example language:</li>
                                    <li class="two">“We’ve been talking and thinking about this for a long time now. Let’s find a way to clear our heads—we can come back to it later,
                                        but taking a break will help us think more clearly when we do come back to it.”</li>
                            </ul>
                    </div>
                </span>
                <!-- Guidelines for talking with your kids: High Schoolers -->
                <span id="high-schoolers" class="anchor">
                    <div class="col-10 mx-auto bg-white">
                        <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/abortion/high-schoolers.png') }}" alt="Guidelines for talking with your kids: High Schoolers">
                    </div>
                    <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                        <p><b>What is age appropriate?</b>
                            <br/>Whether your high schooler is politically connected or not, this is a conversation that will be happening around them and they need to be able to engage with
                            it. Your high schooler is preparing to enter a world of citizenship and you must be part of that transition. They need you to be present, to start conversations,
                            and to listen to them as they grapple with the big issues of the day. At no time is this process more salient than when political issues are ripping our country apart.
                        </p>
                        <p>Your adolescen children also need to know that you are not afraid of or unable to have or don’t care about abortion. Because so many parents haven’t had clear, explicit
                            conversations with their adolescent children on this topic before, their children don’t necessarily know that it is something you value. So show them. Start the
                            conversation and then slow down to start listening.</p>
                        <p>The thing about middle and older teenagrs is that they need to be able to identify the issues and consider and weigh them on their own terms and with full
                            information. As their parent, you have the capacity to offer them that platform and that space if you step fully into it. But they will be thinking about it and
                            talking about it regardless of what you do - so you aren’t protecting them from anything by ignoring the conversation. Only ensuring you won’t be part of their
                            conversation.</p>
                        <p><b>Starting the Conversation:</b>
                        <br/>One of the most powerful things you can do to spur conversation with your teenager is to bring them real stories, real examples, real moral and ethical dilemmas and
                            talk them out together. Here are a few resources that can provide that for you:</p>
                        <p><i>For more information about people who receive abortions:</i>
                            <ul class="green">
                                    <li>Teen Vogue has a collection of <a href="https://www.teenvogue.com/story/abortion-stories" target="_blank">39 stories</a>
                                        from people who received abortions.</li>
                                    <li><a href="https://www.nytimes.com/interactive/2021/12/14/upshot/who-gets-abortions-in-america.html" target="_blank">“Who Gets Abortions in America?”</a>
                                        by the New York Times includes research  findings and some helpful graphs.</li>
                            </ul>
                        </p>
                        <p><i>For information about reproductive justice:</i>
                            <ul class="green">
                                <li>Visit websites of organizations that support reproductive justice together and talk about what you learn about those organizations. Some examples are:</li>
                                    <li class="two"><a href="https://reproductiverights.org" target="_blank">
                                        Center for Reproductive Rights</a></li>
                                    <li class="two"><a href="https://www.sistersong.net" target="_blank">
                                        SIster Song</a></li>
                            </ul>
                        </p>
                        <p>Here is some example language:
                            <ul class="green">
                                <li>“How are you feeling about what the Supreme Court said about abortion last week?”</li>
                                <li>“Do you have any questions about the decision statements? I’d be happy to answer them or to try and find answers to your questions if I don’t know.”</li>
                                <li>“Let’s talk about abortion broadly. What do you think are reasons it should be available? What are reasons it shouldn’t be?”</li>
                                <li>“How might other people answer those two questions differently, and why do you think you’re right and they’re wrong?”</li>
                            </ul>
                        </p>
                        <p><b>Continuing the Conversation:</b>
                            <br/>Find times over the next few days, weeks, and months to circle back to this conversation. Making sure that the dialogue happens over and over again, rather
                            than all at once, ensures that engaging on difficult topics becomes a natural and integrated part of your relationship. Here are some additional questions and
                            conversation topics to spur discussion in the coming months:
                            <ul class="green">
                                <li>“It’s been a few days/weeks/months since that Supreme Court ruling. Have you been thinking about it much?”</li>
                                <li>“What do your friends think about abortion? Have y’all talked about the recent Supreme Court ruling on it?”</li>
                                <li>“I read an interesting article about the Surpreme Court abortion ruling. It said ___________. I thought it was interesting. What do you think?”</li>
                            </ul>
                        </p>
                    </div>
                </span>
            </div>
        </div>
    </div>

    <style>
        .anchor {
            scroll-margin-top: 125px;
        }
    </style>
</section>
@endsection
