@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}"> About</a> |
            <a href="{{ url($path.'/apply') }}">Apply </a> |
            <span style="font-weight: bold;color:#9acd57">Board of Directors</span>
        </div>
    </div>

    <div class="container">
        <!-- BOARD HEADER-->
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-globe.png') }}" alt="Ollie the octopus holding a globe"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Apply to join the board</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:740px">The UN|HUSHED Board of Directors is seeking new members.</p>
                </div>
            </div>
        </div>
        <!-- BOARD CONTENT-->
        <div class="row justify-content-center mx-auto pb-5" style="max-width:800px">
            <div class="row">
                <div class="col-auto m-auto">
                    <p>Are you ready to join in leadership opportunities focused on global business development, fundraising and operational support, 
                        projects and special initiatives, and event planning? If the UN|HUSHED mission, values, and this opportunity suit you, please submit 
                        your application to the appropriate posting below. We look forward to hearing from you!
                    </p>
                </div>
            </div>
            <!-- General -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/H9mBoNQ2TwAdsXfp6" target="_blank">Director</a></span></p>
                    <p>Location: Any
                    <br>Term: 3 year terms, renewable up to 6 years
                    <br>Please note the requirements for this position including membership dues and time commitments while completing your application.</P>
                    <!-- Apply here -->
                    <a href="https://forms.gle/H9mBoNQ2TwAdsXfp6" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>

            <!-- General -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/H9mBoNQ2TwAdsXfp6" target="_blank">General Counsel</a></span></p>
                    <p>Location: U.S. resident preferred
                    <br>Term: 3 year terms, renewable up to 6 years
                    <br>Please note this position requires a Juris Doctor (J.D.), Master of Laws (L.L.M.), or Doctor of Juridical Science (S.J.D.) to apply.</P>
                    <!-- Apply here -->
                    <a href="https://forms.gle/H9mBoNQ2TwAdsXfp6" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>

            <!-- Treasurer -->
            <div class="tBox1">
                <div class="row">
                    <p><span style="font-size:18px"><a href="https://forms.gle/H9mBoNQ2TwAdsXfp6" target="_blank">Treasurer</a></span></p>
                    <p>Location: U.S. resident preferred
                    <br>Term: 3 year terms, renewable up to 6 years
                    <br>Please note this position requires that the applicant is familiar with U.S. taxation, nonprofit finance reporting, and have accounting experience.</P>
                    <!-- Apply here -->
                    <a href="https://forms.gle/H9mBoNQ2TwAdsXfp6" target="_blank"><button type="submit" class="btn btn-secondary m-1">Apply Now</button></a>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
