<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/kzn4uks.css">
    <link rel="stylesheet" href="{{url('css/games/sharks.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/91ed7ffc0c.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <script src="{{url('js/giffer.js')}}"></script>
</head>

<body>
    <div class="slides-container">
        <!-- SLIDE 1 start-->
        <div class="slide slide-1">
            <div class="text-box">
            </div>
            <a class="pill start-btn action-btn" rel="slide-2" href="#slide-2">start</a>
        </div>
        <!-- SLIDE 2 assignment-->
        <div class="slide slide-2">
            <div class="text-box">
                <div class="text-content">
                    <p style="font-size: 25px; color:#fff;">Case III: Space Sharks</p>
                    <p style="color:#fff;">In your final assignment for BES, you will login to the hottest new game, Space Sharks! You must safely navigate a chat exchange from an anonymous player.
                    <br>Click the green text to start your assignment.
                    <br><a class="option-btn action-btn" rel="slide-3" href="#slide-3"><span class="blinker">...Let's Start</span></a></p>
                </div>
            </div>
        </div>
        <!-- SLIDE 3 login-->
        <div class="slide slide-3">
            <div class="slideBG title">
                <a class="pill login-btn action-btn" rel="slide-4" href="#slide-4">LOGIN</a>
            </div>
        </div>
        <!-- SLIDE 4 user info-->
        <div class="slide slide-4">
            <div class="slideBG">
                <div class="text-box">
                    <div class="text-content text-white">
                        <p style="font-size: 22px;">Make your selections to play...</p>
                        <form method="POST" action="{{ url($path.'/sharks') }}" id="sharks" style="font-size: 16px;">
                            @csrf
                            <div class="row pb-2 pb-2">
                                <label for="username" class="col-form-label col-6 text-left">Username</label>
                                <div class="col-6">
                                    <input id="username" type="text" class="form-control" name="username" value="">
                                </div>
                            </div>
                            <div class="row pb-2 pb-2">
                                <label for="age" class="col-form-label col-6 text-left">Enter your age</label>
                                <div class="col-md-6">
                                    <input id="age" type="number" class="form-control" name="age" min="1">
                                </div>
                            </div>
                            <div class="row pb-2 pb-2">
                                <label for="crewmate" class="col-form-label col-6 text-left">Choose a crewmate</label>
                                <div class="col-6">
                                    <select class="form-select" id="crewmate" name="crewmate">
                                        <option value="" selected disabled hidden>Please choose...</option>
                                        <option value="pelicant">pelican't</option>
                                        <option value="oSnap">oSnap</option>
                                        <option value="Turtle-Burger">Turtle-Burger</option>
                                    </select>
                                </div>
                            </div>
                            <input name="continue" id="continue" class="pill continue-btn" type="submit" data-bs-toggle="button" aria-pressed="false" autocomplete="off" value="CONTINUE">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- SLIDE 5 Bes welcome -->
        <div class="slide slide-5">
            <div class="slideBG loading">
                <div class="besbox bbY animate one">
                    <p class="besText">Welcome Special Agent in Training <span class="fill-name"></span>.
                    <br>We will be communicating with you throughout your training using the BES messaging system. Click okay to proceed.</p>
                    <div class="besBtn"><button class="action-btn" rel="slide-6" href="#slide-6">okay</button></div>
                </div>
                <span class="blinkerWhite">Loading...</span>
            </div>
        </div>
        <!-- SLIDE 6 beat first level -->
        <div class="slide slide-6">
            <div class="slideBG won">
                <div class="besbox bbY animate clickoff1" style="--delay: 1s;">
                    <p class="besText">You and <span class="fill-crew"></span> breeze through the level, easily beating the other team. A chat box pops up.</p>
                    <div class="besBtn"><button onclick="clickoff1()">okay</button></div>
                </div>
                <div class="besbox bbY animate" style="--delay: 10s;">
                    <p class="besText">Think carefully about how you will respond. Click on the chat you'd like to send back to <span class="fill-crew"></span>.</p>
                    <div class="besBtn">
                        <button class="action-btn mx-1" rel="slide-8" href="#slide-8">No thanks.</button>
                        <button class="action-btn mx-1" rel="slide-7" href="#slide-7">Yeah.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message chat__message_A" style="--delay: 8s;">
                       <div class="chat__content">
                            <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                            <p>
                                <span style="font-weight: bold" class="fill-crew"></span>
                                <br>Wanna play again?
                            </p>
                       </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 6s;">
                       <div class="chat__content">
                            <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                            <p>
                                <span style="font-weight: bold" class="fill-crew"></span>
                                <br>Good job!
                            </p>
                        </div>
                    </div>
                 </section>
            </div>
        </div>
        <!-- SLIDE 7 answer from slide 6 "Yeah!"-->
        <div class="slide slide-7">
            <div class="slideBG won">
                <div class="besbox bbY animate clickoff2" style="--delay: 1s;">
                    <p class="besText">You and <span class="fill-crew"></span> play again. This match is close, but you and <span class="fill-crew"></span> are able to secure a second victory.</p>
                    <div class="besBtn"><button onclick="clickoff2()">okay</button></div>
                </div>
                <div class="besbox bbY animate" style="--delay: 10s;">
                    <p class="besText">Do you tell <span class="fill-crew"></span> your real age?
                    <br>Click on the chat you'd like to send.</p>
                    <div class="besBtn">
                        <button type="button" class="action-btn" rel="slide-11" href="#slide-11">I'm <span class='fill-age'></span></button>
                        <button class="besBtn action-btn" rel="slide-12" href="#slide-12">I'm 84</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 8s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 6s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work.
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 8 answer from slide 6 "No thanks." -->
        <div class="slide slide-8">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 8s;">
                    <p class="besText">Do you tell <span class="fill-crew"></span> your real age?
                    <br>Click on the chat you'd like to send.</p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-9" href="#slide-9">I'm <span class='fill-age'></span></button>
                        <button class="action-btn" rel="slide-10" href="#slide-10">I'm 84</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 6s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                            <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                            <p>
                                <span style="font-weight: bold" class="fill-name"></span>
                                <br>No thanks.
                            </p>
                        </div>
                    </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew">crew</span>
                                 <br>Wanna play again?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Good job!
                             </p>
                         </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 9 answer from slide 8 "I'm (age)."-->
        <div class="slide slide-9">
            <div class="slideBG won">
                <div class="besbox bbY animate " style="--delay: 7s;">
                    <p class="besText">Do you share your exact address, or maybe just your state?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-14" href="#slide-14">My real physical address</button>
                        <button class="action-btn" rel="slide-13" href="#slide-13">Texas</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 5s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                            <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                            <p>
                                <span style="font-weight: bold" class="fill-name"></span>
                                <br>No thanks.
                            </p>
                        </div>
                    </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew">crew</span>
                                 <br>Wanna play again?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Good job!
                             </p>
                         </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 10 answer from slide 8 "I'm 84"-->
        <div class="slide slide-10">
            <div class="slideBG won">
                <div class="besbox bbY animate " style="--delay: 7s;">
                    <p class="besText">Do you share your exact address, or maybe just your state?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-16" href="#slide-16">My real physical address</button>
                        <button class="action-btn" rel="slide-15" href="#slide-15">Texas</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 5s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Lol sure you are
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                            <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                            <p>
                                <span style="font-weight: bold" class="fill-name"></span>
                                <br>No thanks.
                            </p>
                        </div>
                    </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew">crew</span>
                                 <br>Wanna play again?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Good job!
                             </p>
                         </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 11 answer from slide 7 "I'm (age)."-->
        <div class="slide slide-11">
            <div class="slideBG won">
                <div class="besbox bbY animate " style="--delay: 7s;">
                    <p class="besText">Do you share your exact address, or maybe just your state?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-18" href="#slide-18">My real physical address</button>
                        <button class="action-btn" rel="slide-17" href="#slide-17">Texas</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 5s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 12 answer from slide 7 "I'm 84"-->
        <div class="slide slide-12">
            <div class="slideBG won">
                <div class="besbox bbY animate " style="--delay: 7s;">
                    <p class="besText">Do you share your exact address, or maybe just your state?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-20" href="#slide-20">My real physical address</button>
                        <button class="action-btn" rel="slide-19" href="#slide-19">Texas</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 5s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Lol sure you are
                             </p>
                        </div>
                     </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 13 correct answer from slides 9 -->
        <div class="slide slide-13">
            <div class="slideBG won">
                <div class="besbox bbY clickoff3 animate" style="--delay: 4s;">
                    <p class="besText">
                        Good Job, soon to be Special Agent <span class="fill-name"></span>!
                        <br>You're being careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn"><button onclick="clickoff3()">Got it!</button></div>
                </div>
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        Send a joke back?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-21" href="#slide-21">Yes, her name is Ehtel.</button>
                        <button class="action-btn" rel="slide-22" href="#slide-22">Lol, no.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 14s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 14 incorrect answer from slides 9 -->
        <div class="slide slide-14">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 4s;">
                    <p class="besText">
                        Yikes!
                        <br>Stay on your toes, trainee <span class="fill-name"></span>. Remember to be careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-13" href="#slide-13">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>123 Baker Street
                                    <br>City, State 12345
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 15 correct answer from slides 10 with real age-->
        <div class="slide slide-15">
            <div class="slideBG won">
                <div class="besbox bbY clickoff4 animate" style="--delay: 4s;">
                    <p class="besText">
                        Good Job, soon to be Special Agent <span class="fill-name"></span>!
                        <br>You're being careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn"><button onclick="clickoff4()">Got it!</button></div>
                </div>
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        Send a joke back?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-21" href="#slide-21">Yes, her name is Ehtel.</button>
                        <button class="action-btn" rel="slide-22" href="#slide-22">Lol, no.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 14s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Lol sure you are
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 16 incorrect answer from slides 10 -->
        <div class="slide slide-16">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 4s;">
                    <p class="besText">
                        Yikes!
                        <br>Stay on your toes, trainee <span class="fill-name"></span>. Remember to be careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-15" href="#slide-15">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>123 Baker Street
                                    <br>City, State 12345
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Lol sure you are
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>You're really awesome at this, btw
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No prob, maybe later
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 17 correct answer from slide 11 TX-->
        <div class="slide slide-17">
            <div class="slideBG won">
                <div class="besbox bbY clickoff3 animate" style="--delay: 4s;">
                    <p class="besText">
                        Good Job, soon to be Special Agent <span class="fill-name"></span>!
                        <br>You're being careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn"><button onclick="clickoff3()">Got it!</button></div>
                </div>
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        Send a joke back?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-25" href="#slide-25">Yes, her name is Ehtel.</button>
                        <button class="action-btn" rel="slide-26" href="#slide-26">Lol, no.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 14s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 18 incorrect answer from slide 11 TX-->
        <div class="slide slide-18">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 4s;">
                    <p class="besText">
                        Yikes!
                        <br>Stay on your toes, trainee <span class="fill-name"></span>. Remember to be careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-15" href="#slide-15">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>123 Baker Street
                                    <br>City, State 12345
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 19 correct answer from slide 11 TX-->
        <div class="slide slide-19">
            <div class="slideBG won">
                <div class="besbox bbY clickoff3 animate" style="--delay: 4s;">
                    <p class="besText">
                        Good Job, soon to be Special Agent <span class="fill-name"></span>!
                        <br>You're being careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn"><button onclick="clickoff3()">Got it!</button></div>
                </div>
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        Send a joke back?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-27" href="#slide-27">Yes, her name is Ehtel.</button>
                        <button class="action-btn" rel="slide-28" href="#slide-28">Lol, no.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 14s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Lol sure you are
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 20 incorrect answer from slide 11 TX-->
        <div class="slide slide-20">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 4s;">
                    <p class="besText">
                        Yikes!
                        <br>Stay on your toes, trainee <span class="fill-name"></span>. Remember to be careful about sharing personal information.
                        Sharing general details about yourself with people you just met (online or in-person) is usually fine.
                        Sharing specific details can be a potential safety issue.
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-19" href="#slide-19">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>123 Baker Street
                                    <br>City, State 12345
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Yes! Team work makes the dream work
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 21 yes answer from slides 13 -->
        <div class="slide slide-21">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Yes, and her name is Ehtel.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 22 yes answer from slides 13 -->
        <div class="slide slide-22">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-31" href="#slide-31">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-32" href="#slide-32">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Lol no.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 23 yes answer from slides 15 -->
        <div class="slide slide-23">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Yes, her name is Ehtel.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 24 yes answer from slides 15 -->
        <div class="slide slide-24">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Lol no.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 25 yes answer from slide 17 TX-->
        <div class="slide slide-25">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Yes, her name is Ehtel.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 26 no answer from slide 17 TX-->
        <div class="slide slide-26">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Lol no.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm <span class="fill-age"></span>
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 27 yes answer from slide 19 TX-->
        <div class="slide slide-27">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Yes, her name is Ehtel.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Lol sure you are
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 28 no answer from slide 19 TX-->
        <div class="slide slide-26">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 16s;">
                    <p class="besText">
                        What do you think?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-29" href="#slide-29">Sure send me the link.</button>
                        <button class="action-btn" rel="slide-30" href="#slide-30">nah i'm just going to play here for now</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 12s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 9s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Lol no.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Texas
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>Where do you live?
                             </p>
                        </div>
                     </div>
                    <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>No way! Me too!
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-name"></span>
                                 <br>I'm 84
                             </p>
                        </div>
                     </div>
                     <div class="chat__there">
                        <div class="chat__content">
                             <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                             <p>
                                 <span style="font-weight: bold" class="fill-crew"></span>
                                 <br>How old are you?
                             </p>
                        </div>
                     </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 29 incorrect answer from slides 21, 23, 25, 27 -->
        <div class="slide slide-29">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 3s;">
                    <p class="besText">
                        Yikes! Visiting other sites can have potential issues. 
                        Like different rules (or lack thereof) or privacy issues (like stealing info or downloading bugs to your computer).
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-30" href="#slide-30">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Sure send me the link.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Yes, and her name is Ehtel.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 30 correct answer from slides 21, 23, 25, 27 -->
        <div class="slide slide-30">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 3s;">
                    <p class="besText">
                        Good Job! Visiting other sites can have potential issues. 
                        Like different rules (or lack thereof) or privacy issues (like stealing info or downloading bugs to your computer).
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-33" href="#slide-33">Cool.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>nah i'm just going to play here for now
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Yes, and her name is Ehtel.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 31 incorrect answer from slides 22, 24, 26, 28 -->
        <div class="slide slide-31">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 3s;">
                    <p class="besText">
                        Yikes! Visiting other sites can have potential issues. 
                        Like different rules (or lack thereof) or privacy issues (like stealing info or downloading bugs to your computer).
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-32" href="#slide-32">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Sure send me the link.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Lol no.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 32 correct answer from slides 22, 24, 26, 28 -->
        <div class="slide slide-32">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 3s;">
                    <p class="besText">
                        Good Job! Visiting other sites can have potential issues. 
                        Like different rules (or lack thereof) or privacy issues (like stealing info or downloading bugs to your computer).
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-33" href="#slide-33">Cool.</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>nah i'm just going to play here for now
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Lol no.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Wow, Texas! Do you ride a cow to school?
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 33 mom is making them log off -->
        <div class="slide slide-33">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 13s;">
                    <p class="besText">
                        Do you give <span class="fill-crew"></span> your number?
                    </p>
                    <div class="besBtn">
                        <button class="action-btn mb-1" rel="slide-35" href="#slide-35">Okay! My number is (555) 123-4567</button>
                        <button class="action-btn" rel="slide-34" href="#slide-34">Why don’t I just add you as a friend on here?</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 6s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>That way we can plan when to play together.
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 3s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Do you want to exchange numbers so we can text?
                                </p>
                        </div>
                    </div>
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>My mom is about to make me log off.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>nah i'm just going to play here for now
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 34 goodjob -->
        <div class="slide slide-34">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 2s;">
                    <p class="besText">
                        Good Job! It could be potentially risky to share other methods of communication.
                        Your phone number and even your email address can give someone you don't know a lot more information about who you are and where you live than you might think.
                    </p>
                    <div class="besBtn"><button class="action-btn mb-1" rel="slide-36" href="#slide-36">Okay</button></div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Why don’t I just add you as a friend on here?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>That way we can plan when to play together.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Do you want to exchange numbers so we can text?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>My mom is about to make me log off.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>nah i'm just going to play here for now
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 35 goodjob -->
        <div class="slide slide-35">
            <div class="slideBG won">
                <div class="besbox bbN animate" style="--delay: 3s;">
                    <p class="besText">
                        Yikes! It could be potentially risky to share other methods of communication. Your phone number/email address/etc can give someone a lot more info about who you are and where you live than you might think.
                        Ask a guardian before sharing your number with someone.
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-34" href="#slide-34">Change my answer</button>
                    </div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_A" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Okay! My number is (555) 123-4567
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>That way we can plan when to play together.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Do you want to exchange numbers so we can text?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>My mom is about to make me log off.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>nah i'm just going to play here for now
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 36 crewmate logs off go to ad -->
        <div class="slide slide-36">
            <div class="slideBG won">
                <div class="besbox bbY animate" style="--delay: 4s;">
                    <p class="besText">
                        You play a few more games by yourself, then see that Space Shark! is running a limited time sale on game credits. It ends in an hour, right before your parents get home.
                        Do you enter in your parent’s credit card information? It’s a limited time offer!
                        Or wait for the next sale? There will probably be another one tomorrow.
                    </p>
                    <div class="besBtn"><button class="action-btn" rel="slide-37" href="#slide-37">Ready!</button></div>
                </div>
                <section class="chat">
                    <div class="chat__message  chat__message_B" style="--delay: 1s;">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>has logged off
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>Why don’t I just add you as a friend on here?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>That way we can plan when to play together.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Do you want to exchange numbers so we can text?
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>My mom is about to make me log off.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Rshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-name"></span>
                                    <br>nah i'm just going to play here for now
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>You wanna check it out? It’s just called Space Shark! Online.
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>It’s way better than the original imo
                                </p>
                        </div>
                    </div>
                    <div class="chat__there">
                        <div class="chat__content">
                                <img class="crewImg" src="{{ asset('img/games/space-sharks/Bshark.png') }}">
                                <p>
                                    <span style="font-weight: bold" class="fill-crew"></span>
                                    <br>Hey my friend made some mods to the game and is hosting it on a private server
                                </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- SLIDE 37 ad-->
        <div class="slide slide-37">
            <div class="slideBG ad">
                <div class="tooltip1 popup">
                    <a class="pill sale-btn sale-btn-purchase" href="#">Purchase $5.99</a>
                    <div class="tooltiptext1">
                        Click to enter in your parent’s credit card information?
                        <br>It’s a limited time offer!
                    </div>
                </div>
                <div class="tooltip2">
                    <a class="x-btn sale-btn-x" href="#">x</a>
                    <span class="tooltiptext2">Click to wait for the next sale?
                        <br>There will probably be another one tomorrow.</span>
                </div>
                <article class="clock" id="model3">
                    <div class="count">
                        <div id="timer"></div>
                    </div>
                </article>
            </div>
            <div class="besbox bbN popuptext clickoff6" id="popPurchase">
                <p class="besText">Yikes, trainee <span class="fill-name"></span>! Using a credit card without asking is stealing. Don't steal. It's not nice.</p>
                <div class="besBtn"><button onclick="clickoff6()">Try again.</button></div>
            </div>
            <div class="besbox bbY popuptext" id="popClose">
                <p class="besText">Good job, trainee <span class="fill-name"></span>! Spending money from someone's credit card without asking is stealing. And we all know stealing isn't nice.
                Whoo! You're going to be a real agent in no time!</p>
                <div class="besBtn"><button class="action-btn" rel="slide-38" href="#slide-38">Let's keep going.</button></div>
            </div>
        </div>
        <!-- SLIDE 38 leaderboard-->
        <div class="slide slide-38">
            <div class="slideBG leader">
                <div class="besbox bbY animate" style="--delay: 1s;">
                    <p class="besText">
                        You scroll through this week’s leaderboards, and see a username that includes a word you’ve heard some older kids using.
                        You think it’s bad, but you’re not completely sure what it means. You decide to…
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-41" href="#slide-41">go ask a trusted adult</button>
                        <button class="action-btn" rel="slide-39" href="#slide-39">just google it</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- SLIDE 39 animation-->
        <div class="slide slide-39">
            <div class="besbox bbN animate" style="--delay: 3s;">
                <p class="besText">
                    Whoops, that could have been very bad! The internet is full of things you might not want to see.
                </p>
                <div class="besBtn">
                    <button class="action-btn" rel="slide-40" href="#slide-40">Change my answer</button>
                </div>
            </div>
            <div id="gif"><img data-bs-gifffer="{{url('img/games/space-sharks/googles.gif')}}"/> </div>
        </div>
        <!-- SLIDE 40 leaderboard-->
        <div class="slide slide-40">
            <div class="slideBG leader">
                <div class="besbox bbY animate" style="--delay: 1s;">
                    <p class="besText">
                        You scroll through this week’s leaderboards, and see a username that includes a word you’ve heard some older kids using.
                        You think it’s bad, but you’re not completely sure what it means. You decide to…
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-41" href="#slide-41">go ask a trusted adult</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- SLIDE 41 animation-->
        <div class="slide slide-41">
            <div class="slideBG again">
                <div class="besbox bbY animate" style="--delay: 2s;">
                    <p class="besText">
                        Well done. The internet is full of things you might not want to see. You play one more game and smash it! You log off and reward yourself with a snack.
                    </p>
                    <div class="besBtn">
                        <button class="action-btn" rel="slide-42" href="#slide-42">strawberries!</button>
                        <button class="action-btn" rel="slide-42" href="#slide-42">a cheese stick!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- SLIDE 42 end-->
        <div class="slide slide-42">
            <div class="cert-name"></div>
            <div class="cert-date">
                {{ date('m-d-Y') }}
            </div>
        </div>
    </div>
    </div>

    <style>
        .slide{
            display: none;
            opacity: 0;
            background: #000;
        }
        /** SLIDE SPECIFIC CSS **/
        .slide-1 { background-image: url({{url('img/games/space-sharks/01-title.jpg')}}); }
        .slide-2, .slide-3, .slide-4,.slide-5, .slide-6, .slide-7, .slide-8, .slide-9, .slide-10,
        .slide-11, .slide-12, .slide-13, .slide-14, .slide-15, .slide-16, .slide-17, .slide-18,
        .slide-19, .slide-20, .slide-21, .slide-22, .slide-23, .slide-24, .slide-25, .slide-26,
        .slide-27, .slide-28, .slide-29, .slide-30, .slide-31, .slide-32, .slide-33, .slide-34,
        .slide-35, .slide-36, .slide-37, .slide-38, .slide-39, .slide-40, .slide-41
        { background-image: url({{url('img/games/space-sharks/desktop.jpg')}}); }
        .title { background-image: url( {{url('img/games/space-sharks/03-title.png')}} ); }
        .loading { background-image: url( {{url('img/games/space-sharks/05-loading.png')}} ); }
        .won { background-image: url( {{url('img/games/space-sharks/05-won.png')}} ); }
        .ad { background-image: url( {{url('img/games/space-sharks/21-ad.png')}} ); }
        .leader { background-image: url( {{url('img/games/space-sharks/24-leader.png')}} ); }
        .again { background-image: url( {{url('img/games/space-sharks/27-again.png')}} ); }
        .slide-42 { background-image: url( {{url('img/games/space-sharks/end.jpg')}} ); }
        .bbY { background-image: url( {{url('img/games/space-sharks/besbox.png')}} ); }
        .bbN { background-image: url( {{url('img/games/space-sharks/besW.png')}} ); }
        /** SLIDE 7 **/
        .hide {
            display: none;
            visibility: hidden;
        }
        .popup .popuptext {
            visibility: hidden;
        }
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }
        #popPurchase, #popClose{
            visibility: hidden;
        }
        /** SLIDE 40 **/
        #gif {
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-image: url( {{url('img/games/space-sharks/googles.gif')}} );
        }
    </style>

    <script>

        var gifs = Gifffer();

        setTimeout( function() {
            gifs[0].click(); //will play the first gif
        }, 1000);
        //slides
        var _currentSlide = 'slide-1';
        var _username, _age, _crewmate;
        function fillUserData(){
            if(Cookies.get('username') != "undefined") _username = Cookies.get('username');
            if(Cookies.get('age') != "undefined") _age = Cookies.get('age');
            if(Cookies.get('crewmate') != "undefined") _crewmate = Cookies.get('crewmate');
            $("#username").val(_username);
            $(".cert-name").text(_username);
            $(".fill-name").text(_username);
            $("#age").val(_age);
            $(".fill-age").text(_age);
            $("#crewmate").val(_crewmate);
            $(".fill-crew").text(_crewmate);
        }
        function clickoff1(){}
        function clickoff2(){}
        $(document).ready(function(){
            startGame();
            fillUserData();

            $('body').on('click', '.action-btn', function(e){
                let _nextSlide = $(this).attr('rel');
                switchSlide(_nextSlide);
            })
            $("#continue").on("click", function(e){
                e.preventDefault();
                let username = $("#username").val();
                if(username.length < 3){
                     alert('Please enter username');
                     return;
                }
                let age = $("#age").val();
                if(age.length < 1 || isNaN(age)){
                     alert("Please enter your age");
                     return;
                }
                let crewmate = $("#crewmate").val();
                if(!crewmate){
                    alert("Please pick a crewmate!");
                    return;
                }
                Cookies.set('username', $("#username").val());
                Cookies.set('age', $("#age").val());
                Cookies.set('crewmate', $("#crewmate").val());
                $(".cert-name").text(Cookies.get('username'));
                _username = $("#username").val();
                _age = $("#age").val();
                _crewmate = $("#crewmate").val();
                fillUserData();
                //    let stateObj = {
                //            slide: "slide-5",
                //        }
                //    history.pushState(stateObj, "slide-5", "#slide-5")
                switchSlide("slide-5");
            });
            $('body').on('click', ".sale-btn-purchase", function(e){
                e.preventDefault();
                $("#popPurchase").css({'visibility': 'visible'});
            })
            $('body').on('click', ".sale-btn-x", function(e){
                e.preventDefault();
                $("#popClose").css({'visibility': 'visible'});
            })
            //ALL DIVS ON AND OFF
            //slide-6
            $('.clickoff1').click(function() {
                $('.clickoff1').hide();
            });
            //slide-7
            $('.clickoff2').click(function() {
                $('.clickoff2').hide();
            });
            //slide-11, 17, 19
            $('.clickoff3').click(function() {
                $('.clickoff3').hide();
            });
            //slide-15
            $('.clickoff4').click(function() {
                $('.clickoff4').hide();
            });
            //slide-37
            $('.clickoff6').click(function() {
                $('.clickoff6').hide();
            });
        });

        //this is just a helper function while developing so I don't start each time from the first slide
        function startGame(){
            _hash = location.hash;
            _nextSlide = _hash.length? _hash.slice(1) : _currentSlide;
            switchSlide(_nextSlide);
        }

        //slide switcher
        function switchSlide (_nextSlide) {
            console.log(_currentSlide, _nextSlide);
            if(_nextSlide != 'slide-2'){
                $('.' + _currentSlide).hide();
                $('.' + _nextSlide).show();
                $('.' + _nextSlide).css({'opacity': 100})
            }else if(_nextSlide = 'slide-2'){
                $('.' + _currentSlide).animate({opacity: 0}, {
                    duration: 200, complete: function () {
                        $(this).hide();
                        $('.' + _nextSlide).show().animate({opacity: 100}, 200);
                    }
                });
            }else if(_currentSlide = 'slide-39'){
                //end the animation
                $(document).ready(function () {
                    window.setTimeout(function(){
                        window.location.href = "{{ url($path.'/space-sharks-1#slide-40') }}";
                    }, 3000)
                });
            }
            _currentSlide = _nextSlide;
            window.location.href = "{{ url($path.'/space-sharks-1#') }}"+_currentSlide;
        }

        //slide 23 Space Sharks Ad
        function purchase(e) {
            e.preventDefault();
            popup = $("#popPurchase");
            console.log($(popup).html());
            popup.css({'visibility': 'visibile'});
        }

        //timer
        var sec = 1800,
            countDiv = document.getElementById("timer"),
            secpass,
            countDown = setInterval(function () {
                'use strict';

                secpass();
            }, 1000);
        function secpass() {
            'use strict';
            var min     = Math.floor(sec / 60),
                remSec  = sec % 60;
            if (remSec < 10) {
                remSec = '0' + remSec;
            }
            if (min < 10) {
                min = '0' + min;
            }
            countDiv.innerHTML = min + ":" + remSec;
            if (sec > 0) {
                sec = sec - 1;
            } else {
                clearInterval(countDown);
                countDiv.innerHTML = 'Sale Ended';
            }
        }
    </script>
</body>
</html>
