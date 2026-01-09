@extends('layouts.app')

@section('content')
<section>

    <div class="container mb-5">
        <!-- EVENT HEADER-->
        <div class="box1">
            <div class="row">
                <div class="col-auto center">
                    <img src="{{ asset('img/octos/ollie-bday.png') }}" alt="Ollie holding four balloons.">
                </div>
                <div class="col-auto p-3 center">
                    <p class="diazo">It's our 4th birthday!</p>
                    <hr>
                    <p>
                        <span class="sketchnote-square" style="max-width:600px"><i class="fas fa-calendar-alt"></i> Save the date 10-23-2021.</span>
                        <div class="row" style="max-width: 420px">
                            <div class="col-6">• <a href="#e1">So Much to Celebrate!</a></div>
                            <div class="col-6">• <a href="#e2">Sex Ed Trivia Night (18+)</a></div>
                            <div class="col-6">• <a href="#e3">Register for free</a></div>
                            <div class="col-6">• <a href="#e4">Drink Menu</a></div>
                        </div>
                    </p>
                </div>
            </div>
        </div>
        <!-- THERMOMETER -->
        @include('fundraisers.bday.thermometer2021')

        <!-- EVENT 1 -->
        <div class="box3">
            <div class="bday-txt">
                <p>Come celebrate with us with an all-digital, all-fun line-up! Having another year under our belt, during a pandemic, and continuing to create amazing, fun, educational experiences has been an honor and a gift.
                <br>
                <br>We'd love to tell you what we've got going on (including the Youth Advisory Council that was kickstarted at this event last year!), what we're looking forward to next year, and learn sex trivia in our second, highly beloved, adults-only fundraising event!
                <br>
                <br id="e1">Scroll on down to find more information, registration links, and a brilliant drinks menu curated to provide you with the best cocktails and mocktails while you learn about the best sex ed!
                </p>
            </div>
        </div>

        <!-- EVENT 1 -->
        <div class="box3">
            <img class="bday-pic" src="{{ asset('img/banners/bday1.png') }}" alt="Ollie holding four balloons under a banner that reads 'It's our birthday!' in front of falling confetti'.">
            <div class="bday-txt">
                <p>
                    <span class="sketchnote-square" >So Much to Celebrate!</span>
                    <br>Satruday, 5e/4c/3m/2p, 1 hr.
                    <br><a target="_blank" href="https://calendar.google.com/event?action=TEMPLATE&amp;tmeid=NWw4aTVuMmlkM2Vna292NzM1ajdibmNiZTUgY182aWxqNW5ibGVudmg5YWk4N3NicWlwNHFib0Bn&amp;tmsrc=c_6ilj5nblenvh9ai87sbqip4qbo%40group.calendar.google.com">
                    <i class="fas fa-calendar-alt"></i> Add this event to your calendar!</a>
                    <br> All ages welcome.
                    <br id="e2"><a href="#e3"><i class="fas fa-user-plus"></i> Register</a> now, for free.
                </p>
                A baby nonprofit is a an exciting thing to create, build, and nurture. It is also an unnerving, stressful, and uneven process. When a nonprofit is able to survive until its 5th birthday it is usually successful for many years afterwards. We are so thrilled to be 4/5 of the way to that milestone!
                In this hour we will share about receiving the World Association of Sexual Health's Award for Excellence & Innovation in Sexuality Education 2021, our Youth Advisory Council, the UN|HUSHED K-12 comprehensive sex education curriculum, and more!
            </div>
        </div>

        <!-- EVENT 2 -->
        <div class="box3">
            <img class="bday-pic" src="{{ asset('img/banners/bday2.png') }}" alt="Ollie holding a microphone on a stage in front of a neon sign that reads 'Sex Ed Trivia'.">
            <div class="bday-txt">
                <p><span class="sketchnote-square" >Sex Ed Trivia Night</span>
                <br>Saturday, 9e/8c/7m/6p, 2hrs.
                <br><a target="_blank" href="https://calendar.google.com/event?action=TEMPLATE&amp;tmeid=MDVnbzcyY2ZodnM5czFtYXNyanM4b2s3OW0gY182aWxqNW5ibGVudmg5YWk4N3NicWlwNHFib0Bn&amp;tmsrc=c_6ilj5nblenvh9ai87sbqip4qbo%40group.calendar.google.com">
                    <i class="fas fa-calendar-alt"></i> Add this event to your calendar!</a>
                <br>18+ only please.
                <br><a href="#e3"><i class="fas fa-user-plus"></i> Register</a> now, for free.</p>
                <p>Come one, come all, bring a team, fly solo, be silly with us! We're going to laugh til we cry for two hours of trivia fun.<br>
                    <br id="e3">This year we have Gabby Reeve back as our host, along with our Director of Ops & Design, Nyk Rayne. They're going to bring the humor and fun as they wisk you away to break out rooms with your team for round after round of hilarious sex ed trivia.
                    And there will be <b>fun prizes including shirts, hoodies, stickers, and more!</b>
                </p>
            </div>
        </div>

        <!-- STUFF -->
        <!-- REGISTRATION CLOSED 
        <div class="box3" >
            <div class="bday-txt pt-4">
                <p class="sketchnote-square" >Free Event Registration</p>
                !-- added session 'bday' in Welcomer --
                @guest
                    If you already have an account <a href="{{ url($path.'/login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('LOGIN') }}</a> first please!
                    <br><span style="font-size: 12px; color: #ccc">Pro tip when you're logged in we prefill this form out for ya!</span>
                    <hr>
                    <p>Otherwise, let's get you signed up for the fun!</p>
                @endif
                    <form method="POST" action="{{ url($path.'/thanks-for-registering') }}" id="regform">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="sr-only">Name</label>
                            <input type="text" name="name" id="name" class="form-control" @if(auth()->user()) value="{{auth()->user()->name}}" @endif placeholder="Full name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="text" name="email" id="email" class="form-control" @if(auth()->user()) value="{{auth()->user()->email}}" @endif placeholder="Email address">
                        </div>
                        Which event(s) are you attending?
                        <br><span style="font-size: 12px; color: #ccc">You'll get an email or two with the zoom link and event details. Pick one or both.</span>
                        <div class="form-group px-4">
                            !-- add AC tag 'Event: 2021 Birthday Keynote' --
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="Event: 2021 Birthday Keynote" id="keynote" checked>
                                <label class="form-check-label" for="flexCheckDefault">
                                    So Much to Celebrate!
                                </label>
                            </div>
                            !-- add AC tag 'Event: 2021 Birthday Trivia' --
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="Event: 2021 Birthday Trivia" id="teamOption" onchange="sexTrivia(this);">
                                <label class="form-check-label" for="flexCheckChecked">
                                    Sex Ed Trivia Night
                                </label>
                            </div>
                        </div>
                        <div id="sexTrivia" style="display:none">
                        <p>Yay, Trivia night!!! We're going to include a link in your confirmation email to create or join a team.
                        <br>!-- https://forms.gle/3prwRWH6fLkiPq4w8 --
                        </div>
                    <div class="form-group text-center" id="e4">
                        <input name="submit" id="submit" class="btn btn-secondary" type="submit" value="SUBMIT" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                    </div>
                </form>
            </div>
        </div>
        -->

        <!-- DRINK MENU -->
        <div class="box3 bm-4">
            <img class="bday-pic" src="{{ asset('img/banners/bday3.png') }}" alt="Ollie tending bar.">
            <div class="bday-txt">
                <p><span class="sketchnote-square">Specialty After Dark Drink Menu</span>
                <br>for Sex Ed Trivia Night, Satruday, 9e/8c/7m/6p</p>
                <p>We thought it was super fun to share beverage recipes that our staff and volunteers created for our first annual Sex Ed Trivia Night.
                    Here are those recipes so we can virtually toast together again this year! Make a team here or come solo and we’ll hook you up with peeps to play with.</p>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>THE OCTOPUS (mocktail)</b></span></p>
                    <p style="text-align: justify">Our beloved Octopus mascot was created to adorn the cover when we released UN|HUSHED: The Middle School Curricula in 2017. Since then they have been given a name and even a Facebook page. This year as we celebrate our fourth birthday, and Ollie’s, with trivia and a signature mocktail.</p>
                    <ul>
                        <li>2 cups of sugar (divided)</li>
                        <li>8 cups of water (divided)</li>
                        <li>blue gel food coloring <br>(order <a href="https://smile.amazon.com/Americolor-Paste-Color-4-5-Ounce-Royal/dp/B000VTG6BW?pd_rd_w=vMk7x&pf_rd_p=bc4faf6b-63fa-47cb-af0d-6aca62c67af2&pf_rd_r=JR46HB65P0R04YPE2F9S&pd_rd_r=47b6767a-6839-4df7-9498-1564fd47d777&pd_rd_wg=SMc7l&pd_rd_i=B000VTG6BW&ref_=pd_bap_d_rp_1_1_i" target="_blank">
                            here</a> and don’t forget to make UN|HUSHED your Amazon Smile charity of choice!)</li>
                        <li>1 cup of lime juice (12 limes)</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>You’ll be making two batches of simple syrup for this recipe.</li>
                            <li>For the first batch boil one cup of water and one cup of sugar in a small saucepan. Stir the sugar into the sugar until dissolved. Squeeze the blue food get into the pan for 3 seconds. Let the syrup cool (you can pop it into the fridge to speed this along.) For the next batch you’ll boil two cups of water and one cup of sugar. Stir until the sugar dissolves and cool.</li>
                            <li>Juice 12 limes, give or take, to get 1 cup of lime juice. Add five cups of cold water and your clear simple syrup together.</li>
                            <li>Add ice and limeade to a mason jar, then slowly pour the blue syrup down one side (be careful not to get it on the outside rim of the glass, you’ll dye your mouth, face, etc. blue if you’re not careful.)</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>PUSSYFOOT (mocktail)</b></span></p>
                    <p style="text-align: justify">Pussy is slang (that some people really like and others really dislike) for the vulva.
                        The vulva is the exterior part of the labia, clitoris, vaginal opening, and the urethral opening (the hole a person pees from).
                        While vaginas are just one part of the vulva, many people say “vagina” when they really mean the vulva.
                        Recipe created by Gabby Reeve.</p>
                    <ul>
                        <li>6 oz fresh orange juice</li>
                        <li>2 tablespoons fresh lime juice</li>
                        <li>2 tablespoons fresh lemon juice</li>
                        <li>1 egg yoke (optional)</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>Pour all ingredients into a shaker two thirds full of ice.</li>
                            <li>Shake well.</li>
                            <li>Strain into an ice filled wine glass.</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>THE FACILITATOR (cocktail)</b></span></p>
                    <p style="text-align: justify">Created to celebrate those who work so hard to bring knowledge and courage to so many. The Facilitator is the “adult” version of the Octopus created by one of our favorite facilitators, Ryan Dillon. It’s a pretty blue with a kick!</p>
                    <ul>
                        <li>1 oz vodka (1 shot)</li>
                        <li>.5oz blue Curaçao</li>
                        <li>1 shot sour apple mix</li>
                        <li>1 shot sweet and sour mix</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>Mix the vodka of your choice, and blue Curaçao.</li>
                            <li>Next add a sour apple mix and sweet and sour.</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>BETWEEN-THE-SHEETS (cocktail)</b></span></p>
                    <p style="text-align: justify">Consent for any sexual activity that happens between, on top of, or anywhere in the space time continuum in relation to the sheets must be: freely given, reversible, informed, enthusiastic, and specific.
                        Concocted by our volunteer Gabby Reeve.</p>
                    <ul>
                        <li>3 tablespoons brandy</li>
                        <li>1 tablespoon light rum</li>
                        <li>1 tablespoon triple sec</li>
                        <li>1 tablespoon fresh lemon juice</li>
                        <li>1 1/2 tablespoons simple syrup</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>Pour all the ingredients into a shaker two thirds full of ice cubes.</li>
                            <li>Shake well.</li>
                            <li>Strained into a chilled cocktail glass.</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>MAIDEN’S PRAYER (cocktail)</b></span></p>
                    <p style="text-align: justify">In Shakespearean English, a maiden head refers to a hymen and the idea of virginity.
                        The hymen is a thin membrane that may cover the opening to the vagina and is not related to whether the person has had penetrative sex or not.
                        Many people with vaginas are born with no visible hymen (visible, non visible, and no hymen at birth are all perfectly normal).
                        The hymen will typically disintegrate over time. Created by Gabby Reeve.</p>
                    <ul>
                        <li>1 jigger gin</li>
                        <li>1 jigger triple sec</li>
                        <li>1 tablespoon triple sec</li>
                        <li>1/3 jigger lemon juice</li>
                        <li>1/2 teaspoon orange juice</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>Stir with ice and strain to serve.</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>KING’S PEG (cocktail)</b></span></p>
                    <p style="text-align: justify">Pegging is defined as penetrating a partner anally with a toy, usually a strap-on dildo. The term is often used to refer to a female identified person penetrating a male identified person. When using toys with a partner, it’s always important to clean the toy between each sex act (be it with the same partner, another, or a different evening).
                        Libation created by Gabby Reeve.</p>
                    <ul>
                        <li>1 piece of ice</li>
                        <li>1 jigger brandy</li>
                        <li>champagne</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>Place ice in a large wine glass.</li>
                            <li>Add brandy.</li>
                            <li>Fill with iced champagne.</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>THE MASSHOLE (cocktail)</b></span></p>
                    <p style="text-align: justify">Becoming familiar with your genitalia and anus are important aspects of personal health. Use a mirror to check your anus and surrounding skin on a regular basis.
                        Drink created by Gabby Reeve.</p>
                    <ul>
                        <li>1 Dunkin’ Donuts Latte (flavor swirl optional)</li>
                        <li>As many nips of Bailey Irish Cream as you want, no one is the boss of you!</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>Mix and enjoy.</li>
                        </ol>
                    </p>
                </div>
                <hr>
                <div class="px-5">
                    <p style="text-align: center"><span class="sketchnote-square" ><b>SEX ‘ED’ SUNSET (cocktail)</b></span></p>
                    <p style="text-align: justify">A recreation of the classic Sex on the Beach, this cocktail gives a nod to the end of hard day teaching with the colors of a beautiful sunset.
                        Mixed by Ryan Dillon.</p>
                    <ul>
                        <li>1 oz vodka</li>
                        <li>half a glass of orange juice</li>
                        <li>1 splash soda water</li>
                        <li>1 splash of sweet and sour</li>
                        <li>Red grenadine</li>
                    </ul>
                    <p style="text-align: justify">
                        <ol>
                            <li>In a small glass add 1 shot of the vodka of your choice.</li>
                            <li>Add half a glass of orange juice.</li>
                            <li>Add a splash of sweet and sour mix and soda water.</li>
                            <li>Top with red grenadine, and viola!</li>
                        </ol>
                    </p>
                </div>
                <hr>

                <p>Sounds like fun right? <a href="#e3"><i class="fas fa-user-plus"></i> Register</a> now, for free.</p>
                <p>PLEASE NOTE: Our trivia event is open to those 18 and up (sorry no youth) and the legal drinking age (in the US) is 21+. Please be responsible while joining us.</p>
            </div>
        </div>
    </div>

    <style>
        /*iPhone*/
        @media screen and (max-width: 780px){
            .box1 {
                margin: 50px auto 0;
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
                margin: 80px auto 0;
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
    </style>
    <script>
        //Team field
        function sexTrivia(nameSelect)
        {
            if(nameSelect){
                admOptionValue = document.getElementById("teamOption").value;
                if(admOptionValue == nameSelect.value){
                    document.getElementById("sexTrivia").style.display = "block";
                }
                else{
                    document.getElementById("sexTrivia").style.display = "none";
                }
            }
            else{
                document.getElementById("sexTrivia").style.display = "none";
            }
        }
        //Registration form
        $(document).ready(function(){
            $("#submit").on('click', function(e){
                e.preventDefault();
                _url = $("#regform").attr('action');
                _data = $("#regform").serialize();
                $.ajax({
                    url: _url,
                    type: 'post',
                    data: _data,
                    success: function(response){

                        console.log(response);
                        if(response.error === true){
                            alert(response.message);
                        }else{
                            document.location = _url;
                        }
                    },
                    fail: function(){ alert("Error"); }
                });
            })
        })
    </script>

</section>
@endsection
