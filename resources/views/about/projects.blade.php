@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/about') }}">About </a> |
            <span style="font-weight: bold;color:#9acd57">Projects</span>
        </div>
    </div>

    <!-- ABOUT HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Our Projects</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:600px">We are always working hard to expand the resources and materials we are able to offer. Below is a list of our current ventures.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row" style="font-size:20px;">
            <!-- about -->
            <p><ul class="green">
                <li><a href="{{ url($path.'/thebellyproject') }}">The Belly Project v2.0</a></li>
                    <li class="two"><a href="https://thebellyproject.wordpress.com" target="_blank">The Original Belly Project</a></li>
                <li><a href="{{ url($path.'/free-content') }}">Free Content we provide on our website</a></li>
                    <li class="two"><a href="{{ url($path.'/sex-ed-dictionary') }}">Free Online Dictionaries</a></li>
                    <li class="two"><a href="{{ url($path.'/arcade') }}">Free Online Games</a></li>
                    <li class="two"><a href="{{ url($path.'/menstruation-information-station') }}">Menstruation Information Station!</a></li>
                <li><a href="{{ url($path.'/subscription-info') }}">Our K-12 comprehensive curricula</a></li>
                    <li class="two"><a href="{{ url($path.'/about#standards') }}">Mapping our curricula to standards</a></li>
            </ul>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <!-- Donate -->
        <div class="row">
            <div class="mx-auto p-1">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input name="donate" id="donate" class="btn btn-secondary" type="submit" value="DONATE" data-toggle="button" aria-pressed="false" autocomplete="off">
                    <input type="hidden" name="cmd" value="_s-xclick" >
                    <input type="hidden" name="hosted_button_id" value="VFH5ZPLVX5352" >
                </form>
            </div>
        </div>
    </div>

</section>
@endsection
