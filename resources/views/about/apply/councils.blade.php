@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <a href="{{ url($path.'/apply') }}">Apply </a> |
            <span style="font-weight: bold;color:#9acd57">Councils</span>
        </div>
    </div>

    <div class="container pb-5">
        <!-- COUNCIL HEADER-->
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-globe.png') }}" alt="Ollie the octopus holding a globe"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Join a council</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:740px">The UN|HUSHED Advisory Councils are seaking new members.</p>
                </div>
            </div>
        </div>
        <!-- COUNCIL CONTENT-->
        <div class="row justify-content-center mx-auto pb-5" style="max-width:800px">
            <div class="row">
                <div class="col-auto m-auto">
                    <p>Are you ready to join a group of dedicated volunteers who feel passionatly about youth getting accurate up-to-date information? If the UN|HUSHED mission, 
                        values, and this opportunity suit you, please submit your application to the appropriate posting below. We look forward to hearing from you!
                    </p>
                </div>
            </div>
            <!-- YAC -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/Z7nPR6fFxSG5WvZ36" target="_blank">Youth Advisory Council</a></span></p>
                    <p>Near peers ages 18-25 | Any location
                    <br>2 year term
                    <br>We are looking for young adults passionate about human sexuality and improving the quality of the curricula their peers will be educated with. This is a paid position that meets quarterly.</p>
                    <!-- Apply here -->
                    <a href="https://forms.gle/Z7nPR6fFxSG5WvZ36" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>
            <!-- CAC -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/do2MWana66UqeEgw8" target="_blank">Curricula Advisory Council</a></span></p>
                    <p>Any location
                    <br>Part time
                    <br>We are looking for experts and educators to review our curricula annually and revise as needed to stay current, and even better yet-cutting edge. This is not a paid position.</p>
                    <!-- Apply here -->
                    <a href="https://forms.gle/do2MWana66UqeEgw8" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>
            <!-- LAC -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="#" target="_blank">Legal Advisory Council</a></span></p>
                    <p>Any location
                    <br>Part time
                    <br>We are looking for professionals in the field of law and policy passionate about human sexuality and improving the quality of human sexuality curricula. This is not a paid position.</p>
                    <!-- Apply here -->
                    <a href="https://forms.gle/usLaPuBFhDRmr2LQ9" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>
            <!-- MAC -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="#" target="_blank">Medical Advisory Council</a></span></p>
                    <p>Any location
                    <br>Part time
                    <br>We are looking for medical professionals passionate about human sexuality and improving the quality of human sexuality curricula. This is not a paid position.</p>
                    <!-- Apply here -->
                    <a href="https://forms.gle/MUYMd5SbH162KCYZ8" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
