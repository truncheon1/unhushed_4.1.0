@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <a href="{{ url($path.'/free-activities') }}">Activities</a> |
            <span style="font-weight: bold;color:#9acd57">Explaining Corona Virus to Kids</span>
        </div>
    </div>

    <!-- ACTIVITY HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <!-- Title -->
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/explaining-corona.png') }}" alt="Explaining Corona to Kids">
                </div>
                <div class="col-10 mx-auto px-5 py-1 bg-white text-left">
                    <p>What's included?
                        <ul class="custom">
                            <li><a href="#intro">Introduction</a></li>
                            <li><a href="#why-talk-about-Coronavirus">Why talk about coronavirus? And how?</a></li>
                            <li><a href="#what-is-Coronavirus">Answering their Question: What is Coronavirus?</a></li>
                            <li><a href="#what-can-we-do-about-it">Answering the Question: What can we do about it?</a></li>
                            <li><a href="#activity-1">ACTIVITY 1: How do we wash our hands?</a></li>
                            <li><a href="#activity-2">ACTIVITY 2: How do germs spread? And why do we use soap?</a></li>
                            <li><a href="#activity-3">ACTIVITY 3: Obstacle course!</a></li>
                        </ul>
                    </p>
                </div>
                <!-- Intro -->
                <span id="intro" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/intro.png') }}" alt="Introduction">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <p>Why didn’t the school bus come today?
                        <br/>Why do you keep talking to people on the computer?
                        <br/>Do frogs sleep in the water or on the land?
                        <br/>Why can’t we go to grandma’s house?
                        <br/>What are all the colors that whales can be?
                        <br/>Can I eat this?
                        <br/>...and hey, what’s Coronavirus?</p>
                    <p>As we make big, important changes to our daily lives in an effort to protect public health, it’s likely that your kids will have some questions.
                    Their world has changed rapidly in ways that can be confusing, and even a little scary.</p>
                    <p>We’ve pulled together a few tips, talking points, and activities to help you navigate these conversations with your children.
                    Ideally, these will leave you prepared to answer their questions, soothe anxieties, and have fun.</p>
                    <p>The activities included are likely best for children ages 3-10, though this will vary by child. That doesn’t mean everyone can’t get involved, though!
                    Older children can easily jump in as teachers, facilitators, and/or obstacle course engineers.</p>
                    <p><b>NOTE:</b> We're using “coronavirus” to refer to both the virus and the infection, rather than the technical terms of SARS-CoV-2 and COVID-19, respectively.
                    More information from the World Health Organization (WHO) about the naming of the virus and its infection can be found
                    <a href="https://www.who.int/emergencies/diseases/novel-coronavirus-2019/technical-guidance/naming-the-coronavirus-disease-(covid-2019)-and-the-virus-that-causes-it" target="_blank">here</a>.</p>
                    <br/>
                </div>
                <!-- Why talk about coronavirus? And how? -->
                <span id="why-talk-about-Coronavirus" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/why-and-how.png') }}" alt="Why talk about coronavirus? And how?">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <p>Change and uncertainty can be overwhelming for all of us. Children are no exception.
                    Being able to have open, honest conversations with loving caregivers can help children cope with the changes they’re experiencing. </p>
                    <p>Unless your child seeks out additional information, stick to the basics about what coronavirus is and what actions they can take to address it.
                    Talking about the importance of handwashing? Great! Talking about the predicted death rate? Not necessary, unless they are specifically asking questions about it. </p>
                    <p>For a more in-depth exploration of the “whys” and “hows” of the corona virus conversation, check out
                    <a href="https://childmind.org/article/talking-to-kids-about-the-coronavirus/" target="_blank">this article</a> from the Child Mind Institute.</p>
                </div>
                <!-- Answering their Question: What is Coronavirus? -->
                <span id="what-is-Coronavirus" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/what-is-corona.png') }}" alt="Answering their Question: What is Coronavirus?">
                </div>
                <div class="col-10 mx-auto px-5 pt-3 bg-white text-justify">
                    <ul class="custom">
                        <li>
                            <p><b>Answer, 3-6 years old:</b><br/>
                            The Coronavirus is a germ. Germs are super small – so small, you can’t usually see them without a special tool like a microscope.
                            Some of them can cause sicknesses like a cold or the flu. The sickness caused by coronavirus is kind of like the flu, but it can be a bit more dangerous for people who are already sick or who are older.
                            </p>
                        </li>
                        <li>
                            <p><b>Answer, 7-10 years old:</b><br/>
                            This <a href="https://www.youtube.com/watch?v=OPsY-jLqaXM&feature=share" target="_blank">video</a> by Dr. Michelle Dickinson does a great job explaining Coronavirus in kid-friendly language.
                            It even features glow in the dark “germs”!</p>
                        </li>
                    </ul>
                </div>
                <!-- Answering the Question: What can we do about it? -->
                <span id="what-can-we-do-about-it" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/what-can.png') }}" alt="Answering the Question: What can we do about it?">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <ul class="custom">
                        <li>
                            <p><b>Stay at home.</b>
                            Germs, for the most part, need people to travel. Germs can be passed from person to person through things like touch, the mucus from a sneeze, or the spit from a cough.
                            Germs can also sometimes hang out for a little while on different objects.
                            They usually stay around longer on things that are slick and flat, like a phone or a countertop, rather than things that have a bumpier texture, like clothes or hair.
                            People can pick up germs by touching surfaces that have germs on them.</p>

                            <p>The coronavirus is really, really good at traveling from person to person. Even if you don’t feel sick, it’s possible to spread coronavirus to other people.
                            That’s why it’s important to stay home right now, to help lower the chances of it traveling to other people. We’re helping keep the people who might get really sick from coronavirus safe.
                            </p>
                        </li>
                        <li>
                            <p><b>Wash your hands!</b> Washing your hands is a great way to get rid of harmful germs at any time! To wash your hands, you should:
                                <ul class="custom">
                                    <li>Get your hands wet
                                    <li>Put soap on your hands
                                    <li>Scrub your hands together for at least 20 seconds! (This is about the time it takes to sing the ABCs once, or Happy Birthday twice.)
                                    Make sure you get the fronts and backs of your hands, between your fingers, and around your finger nails!
                                    <li>Rinse off your hands with water
                                    <li>Dry your hands with a towel
                                </ul>
                            <br/>
                            <b>NOTE:</b> While a hand sanitizer with a high alcohol content (over 60%) can also be helpful, soap and water are still your best tools for busting germs.
                            If you’re interested in learning more about the science behind how soap destroys viruses, we found
                            <a href="https://www.youtube.com/watch?v=-LKVUarhtvE&feature=youtu.be" target="_blank">this video by Vox</a> extremely helpful.
                            It’s a little advanced for elementary-aged kids, but we would recommend it for any older kids or adults who want to learn more.</p>
                        </li>
                        <li>
                            <p><b>Cover your coughs and sneezes.</b>
                            When you feel a sneeze or a cough coming on, it’s important to try and cover your mouth and nose. If you have a tissue handy, that’s a great choice!
                            You can also cough or sneeze into your elbow or the upper part of your arm. There’s lots of fun names for this, like “The Vampire Cough” or the “Super V!”

                            <p>To see a demonstration of this, check out <a href="https://www.youtube.com/watch?v=pA5_-w8sWoY&feature=share" target="_blank">this video by Simply Outrageous Youth</a>.
                            </p>
                        </li>
                        <li>
                            <p><b>Be kind to yourself.</b>
                            Things can be a little difficult and scary right now. In times like this, it’s extra important to make sure you’re being kind to your body.
                            Try and give it the food, exercise, and rest it needs. And know – it’s okay to be scared, or frustrated, or confused.
                            You can always talk to the adults around you if you have any questions or need some extra cuddles.
                            </p>
                        </li>
                        <li>
                            <p><b>Be kind to others.</b>
                            Even when you love someone very, very much, it can be hard to be stuck at home with them for a long period of time! Try and do what you can to be kind to the people around you. Focus on being patient.
                            If you find yourself getting upset, take a break and go do an activity by yourself. Challenge yourself to find one nice thing to do for each person in your house every day.
                            You could wash dishes, sing someone a song, or tell them how much you love them!
                            </p>
                        </li>
                    </ul>
                </div>
                <!-- ACTIVITY 1: How Do We Wash Our Hands? -->
                <span id="activity-1" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/wash-hands.png') }}" alt="ACTIVITY 1: How Do We Wash Our Hands?">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <p>For a simple and effective introduction to handwashing, check out <a href="https://confidencemeetsparenting.com/childrens-mucinex/" target="_blank">
                        this activity by ConfidenceMeetsParenting.com</a>. Using cinnamon and olive oil to visually represent “germs,” you can show your child the proper steps of handwashing and explore how well different
                        techniques work (just water vs. soap and water, running your hands under the water vs. actually scrubbing them, etc.) The linked blog post includes great examples of questions to ask
                        throughout the process to get your kid thinking about what they’re doing and why they’re doing it.</p>
                    <p>And if you’re looking for a fun visual reminder on the proper steps of handwashing, we’ve got you covered with this fun free
                        <br/><a href="{{ asset('pdfs/Free/UNHUSHED_H-Scrub-a-dub-dub!_v01.pdf') }}" download="UNHUSHED_H-Scrub-a-dub-dub!_v01.pdf" class="px-1">
                        <i class="fas fa-link fa-fw fa-lg align-middle"></i> Handout: Scrub-a-dub-dub!</a>
                        You can even print it out and display it near your sinks.</p>
                </div>
                <!-- ACTIVITY 2: How Do Germs Spread? And Why Do We Use Soap? -->
                <span id="activity-2" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/germs.png') }}" alt="ACTIVITY 2: How Do Germs Spread? And Why Do We Use Soap?">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <p>This activity (inspired by <a href="https://thesimpleparent.com/glitter-germs-activity-for-kids/" target="_blank">this blog post by TheSimpleParent.com</a>)
                        is similar to the cinnamon handwashing activity, but it focuses more on transmission of germs to other people and different surfaces.</p>

                    <p><b>NOTE:</b> This time, the germs are represented by lotion and biodegradable glitter (you can purchase that here: <a href="{{ url('https://ecostardust.com/collections/signature-star-sign-blends') }}">https://ecostardust.com/collections/signature-star-sign-blends</a>). Now, we know many people have strong feelings about glitter and the fabulous mess it makes.
                        But we did try it out this week, and the lotion actually helps prevent most of the biodegradable glitter from going rogue.
                        <br/>We also tried it with cinnamon and lotion, and it works almost as well. If you go this route we <b>highly</b> recommend using an unscented lotion. (We tried this with scented. It was a mistake.)
                        We also tried it with olive oil and cinnamon and we found it was too “drippy” for this activity and made a pretty big mess. We wouldn’t recommend it.</p>

                    <p><b>SUPPLIES:</b>
                        <ul class="custom">
                            <li>Soap</li>
                            <li>Water</li>
                            <li>Lotion</li>
                            <li>Glitter (or cinnamon)</li>
                            <li>Plastic toys (At least six, plastic animals work great.)</li>
                            <li>Two large tubs or bowls (Fill these with water ahead of time.)</li>
                            <li>Things to scrub with—toothbrush, wash cloth, etc.</li>
                        </ul>

                    <p><b>PROCEDURE:</b>
                        <ol type="1">
                            <li>
                                <p>Ask kids if they can tell you what germs are. If you haven’t already had a discussion about what germs are, this is a great time to do that!
                                (Check out the “Answering Their Question” section above for help)</p>
                            </li>
                            <li>
                                <p>Ask kids if they can tell you how germs are spread. Again, if you haven’t had a discussion about that, now is the perfect time.</p>
                            </li>
                            <li>
                                <p>Tell kids that you’re going to cover their hands in “pretend germs.” Apply the lotion and glitter to their hands, and ask them to rub it in.</p>
                                    <p class="mx-auto" style="text-align:center; max-width:394px">
                                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/hand1.png') }}" alt="hands with lotion and glitter">
                                    This is how much glitter and lotion I used. I think you can always use more glitter, but this will get the job done!</p>
                                    </p>
                                </p>
                            </li>
                            <li>
                                <p>Now, ask them to touch you, their own arms, each other’s noses – whatever floats your boat.</p>
                            </li>
                            <li>
                                <p>Ask them to observe where they passed any “germs.”</p>
                            </li>
                            <li>
                                <p>Bring out the plastic toys. Ask them to touch the toys and observe if any “germs” are passed. Encourage them to really get those toys glittery!</p>
                            </li>
                            <li>
                                <p>Show your kids the two containers of water you filled up earlier. Tell them they’re going to experiment to see what does the best job at getting rid of germs.</p>
                            </li>
                            <li>
                                <p>Allow them to put soap in one container and agitate the water to form bubbles.</p>
                            </li>
                            <li>
                                <p>Place half of the glitter-exposed animals in the container with just water, and the other in the container with soap and water.</p>
                            </li>
                            <li>
                                <p>Challenge your kids to try and remove the “germs” off of the toys. Encourage them to try out different techniques—
                                just sitting in the container, washing the toy with their hands, washing the toy with a scrubbing tool, washing the toy for a short time vs. a long time.</p>
                            </li>
                            <li>
                                <p>As they experiment, ask them questions about what they’re observing:</p>
                                <ul class="custom">
                                    <li>
                                        <p>What worked better – plain water, or water and soap? If one worked better, why do you think that happened?
                                        <p><b>NOTE:</b> The water and soap combination should do a better job, but these aren’t exactly pristine experimental conditions.
                                        As little hands go from one container to the other, soap might travel into both. This is something you can also discuss!</p>
                                    </li>
                                    <li>
                                        <p>What worked better – letting a toy sit, scrubbing it with your hands, or scrubbing it with tools?</p>
                                    </li>
                                    <li>
                                        <p>Were any toys harder to wash than others? If so, why do you think that might be?</p>
                                    </li>
                                    <li>
                                        <p>Did the “germs” come off your hands as you were washing the toys?</p>
                                    </li>
                                    <li>
                                        <p>Do you think anything else might do a better job getting the “germs” off the toys?.</p>
                                    </li>
                                </ul>
                            </li>
                            <p class="mx-auto" style="text-align:center; max-width:394px">
                                <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/dino.png') }}" alt="hands with lotion and glitter">
                                Working with toys of different textures is a great way to demonstrate why it’s important to really scrub in all the nooks and crannies of your hands. Prompt your kids to notice where the glitter congregates, and what parts are the hardest to wash. While viruses may survive better on slick surfaces, these “glitter germs” behave more like viruses do on human skin. They’re able to hang out better in those harder-to-reach crevices.</p>
                                </p>
                        </ol>
                    </p>
                </div>
                <!-- ACTIVITY 3: Obstacle Course! -->
                <span id="activity-3" class="anchor"></span>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/obstacle.png') }}" alt="ACTIVITY 3: Obstacle Course!">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <p>At-home obstacle courses are a great way to get the wiggles out. By combining some of the hygiene activities you’ve discussed with a few
                        DIY obstacle course staples, you can create your very own coronavirus-fighting course!
                    <p>Obstacle courses are easy to customize. Short or long, inside or outside, simple or elaborate—they’re all great options!
                        Below, you’ll find some suggestions of activities to include in your obstacle course, but you can add anything you want.
                        This is definitely something that you can get older kids involved with—especially since setting up the obstacle course is often just as much fun as completing it!</p>
                    <p>You also have a lot of freedom in how you run the obstacle course. You can make it timed, or just focus on completing it. If you have multiple “runners,” they can absolutely race.
                        However, if sibling rivalry might become an issue, you have a couple options. They can run the course separately and cheer each other on.
                        You can also do a relay-style run—as one runner finishes, the next one gets to start. You can time their total run and have them work together to beat their previous times. </p>
                    <p><b>SUPPLIES:</b>
                        <ul class="custom">
                            <li>Wash your hands—
                                <ul class="circle">
                                    <li>soap</li>
                                    <li>water</li>
                                    <li>towel</li>
                                    <li>timer</li>
                                </ul>
                            </li>
                            <li>Tissue balance—
                                <ul class="circle">
                                    <li>tissues</li>
                                </ul>
                            </li>
                            <li>Tissue toss—
                                <ul class="circle">
                                    <li>Tissue box with tissues</li>
                                    <li>paper</li>
                                    <li>trash can</li>
                                </ul>
                            </li>
                            <li>Move the soap bubble—
                                <ul class="circle">
                                    <li>balloon</li>
                                    <li>designated points A and B</li>
                                </ul>
                            </li>
                            <li>Shoot the germs—
                                <ul class="circle">
                                    <li>"germ" targets like ping pong balls or plastic cups</li>
                                    <li>something to shoot the balls with like nerf guns, water guns, or even rubber bands (if you're brave)</li>
                                </ul>
                            </li>
                            <li>Spy vs. germs—
                                <ul class="circle">
                                    <li>painter's tape</li>
                                    <li>yarn</li>
                                </ul>
                            </li>
                            <li>Avoid the cough—
                                <ul class="circle">
                                    <li>oscillating fan</li>
                                    <li>crepe paper streamers or something similar</li>
                                </ul>
                            </li>
                            <li>Uncover a secret message—
                                <ul class="circle">
                                    <li>surface to write message on (ie; window, sliding glass door, plexi glass, shower door)</li>
                                    <li>dry erase marker or paper/pen/tape</li>
                                    <li>something messy (ie; shaving cream or paint)</li>
                                    <li>supplies for cleaning (ie; wet washcloth, glass cleaner, etc)</li>
                                </ul>
                            </li>
                        </ul>
                    </p>

                    <p><b>PROCEDURE:</b>
                        <ul class="custom">
                            <li>
                                <p>Wash your hands. This is exactly what it sounds like. Have your kids go through the proper steps of hand washing.
                                They have to do every step correctly before they can make their way to the next part of the course.
                                (we would recommend having a timer during this portion so contestants aren’t tempted to speed up those 20 seconds of scrubbing.)</p>
                            </li>
                            <li>
                                <p>Tissue balance. Walk a specified route while balancing a tissue on your nose. If it falls to the floor, you have to start over again!</p>
                            </li>
                            <li>
                                <p>Tissue toss. Kids can demonstrate the proper way to use a tissue, a take it in the trash. (Bonus points if they add fun sneezing sound effects!)</p>
                                <p>You could also ball up paper for “pretend tissues” and have them throw them into a trashcan or basket.</p>
                            </li>
                            <li>
                                <p>Move the soap bubble. Blow up a balloon to represent a “soap bubble.” Kids will need to get it from point A to point B without touching it.
                                (The can blow on it, or think of other creative ways to move the balloon.)</p>
                            </li>
                            <li>
                                <p>Shoot the germs. Create some germ targets for kids to shoot. They can then hit them with nerf guns, water guns, or even rubber bands (if you’re brave).</p>
                            </li>
                            <li>
                                <p>Spy vs. germs. This might be my favorite. Tape or attach yarn or string across a hallway to represent “lasers.”
                                (There’s a great example of this
                                <a href="http://www.mykidsadventures.com/string-laser-maze/" target="_blank">here by MyKidsAdventures.com</a>.)
                                Have your germ-fighting spies sneak past the lasers without touching them to get to the treasure.
                                The treasure could be soap, a box of tissues, a fun message, or an object they’ll need for the next obstacle.</p>
                            </li>
                            <li>
                                <p>Avoid the cough. Got an oscillating fan? Set it up in a small space like a hallway, and have kids sneak around it without getting “coughed” on by the fan.
                                You can also attach something light and fluttery to the fan to make it easier to see where the fan is blowing air.</p>
                            </li>
                            <li>
                                <p>Uncover a secret message. Put those new-found cleaning skills to the test! Write a message like “Use soap to destroy germs” or “You are a germ-fighting warrior!”
                                    <ol type="1">
                                        <li><p>Pencil/Pen option: on a piece of paper. Tape it to one side of a glass door or window.</p></li>
                                        <li><p>Dry erase marker option: on the "outside" of your surface (like the outside of your sliding glass door).</p></li>
                                    </ol>
                                Cover up the message by spreading something messy like shaving cream or paint on the opposite side or "inside" of your surface.
                                Then give your kids the tools to remove the mess and uncover the message!</p>
                            </li>
                        </ul>
                    </p>
                </div>
                <!-- End -->
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/corona/fun.png') }}" alt="Did you have fun?">
                </div>
                <div class="col-10 mx-auto px-5 py-4 bg-white text-justify">
                    <p>If you do decide to try some of these out, please let us know how it goes! If you are sharing pictures on social media, we would be absolutely tickled pink if you tagged us #unhushed.</p>
				    <p>If you are looking for more activities, don’t hesitate to <a href="mailto:info@unhushed.org">get in touch</a>!
                    We’d love to <a href="https://blog.unhushed.org/2020/03/27/explaining-corona-virus-to-kids/" target="_blank">chat</a> about your experiences and know what your kids might be interested in next. </p>
                    <p>For more free handouts and sample activities you can get a free account <a href="{{ url($path.'/register') }}">here</a>.</p>
                    <p>This activity was created with love by <a href="{{ url($path.'/team/jessica') }}">Jessica Smarr</a>.</p>
                </div>
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
