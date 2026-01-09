@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <a href="{{ url($path.'/free-activities') }}">Activities</a> |
            <a href="{{ url($path.'/sex-ed-dictionary') }}">Dictionaries</a> |
            Research
        </div>
    </div>

    <!-- RESEARCH HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Other resources</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:800px">Here's a list of other websites we trust for gathering information.</p>
                </div>
            </div>
        </div>

        <!-- OTHER SITES-->
        <div class="d-flex justify-content-center">
        <div class="row pb-5">
            <!-- about -->
            <p>
                <ul class="green"> English
                    <li><a href="https://www.cdc.gov/sexualhealth">Centers for Disease Control and Prevention</a></li>
                    <li><a href="https://www.nsvrc.org">National Sexual Violence Resource Center</a></li>
                    <li><a href="https://www.plannedparenthood.org/learn">Planned Parenthood</a></li>
                    <li><a href="https://www.scarleteen.com">Scarleteen</a></li>
                    <li><a href="https://www.futureofsexed.org/">Future of sex ed</a></li>
                </ul>

                <ul class="green"> Greek
                    <li><a href="https://eody.gov.gr/disease/sexoyalikos-metadidomena-nosimata/">National Organization of Public Health</a></li>
                    <li><a href="https://isotita.gr/en/home/ ">Γενική Γραμματέας Δημογραφικής και Οικογενειακής Πολιτικής και Ισότητας των Φύλων</a></li>
                    <li><a href="https://midwives.gr/">Midwives Family Wellbeing</a></li>
                    <li><a href="https://www.youth-life.gr/en/">For Adolescent Health</a></li>
                </ul>
        </div>
    </div>

</section>
@endsection
