@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <span style="font-weight: bold;color:#9acd57">Activities</span>
        </div>
    </div>

    <!-- FREE HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Free activities</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:800px">Here at UN|HUSHED we write <a href="{{ url($path.'/curricula') }}">curricula</a> on hard topics the easy way, sex ed done right. The thing is, sex ed is more than just “sex”.
                        It’s about relationships, communication, and so much more. That’s why we’re all about medically accurate information. We’re all about helping our fellow humans.
                        We're all about giving quality resources away at not cost to those in need.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <!-- activity -->
            <div class="lrgCard">
                <a href="{{ url($path.'/condoms-for-facts-sake') }}">
                    <img class="lrgImg" src="{{ asset('img/products/c-condoms.png') }}" alt="Condoms">
                    <p class="lrgText">Condoms for fact's sake!</p>
                </a>
            </div>
            <!-- activity -->
            <div class="lrgCard">
                <a href="{{ url($path.'/explaining-abortion') }}">
                    <img class="lrgImg" src="{{ asset('img/products/c-abortion.png') }}" alt="Abortion">
                    <p class="lrgText">Explaining abortion</p>
                </a>
            </div>
            <!-- activity -->
            <div class="lrgCard">
                <a href="{{ url($path.'/explaining-coronavirus') }}">
                    <img class="lrgImg" src="{{ asset('img/products/c-corona.png') }}" alt="Corona Virus">
                    <p class="lrgText">Explaining Coronavirus</p>
                </a>
            </div>
            <!-- activity -->
            <div class="lrgCard">
                <a href="{{ url($path.'/menstruation-information-station') }}">
                    <img class="lrgImg" src="{{ asset('img/products/uterus.png') }}" alt="Condoms">
                    <p class="lrgText">Menstruation information station</p>
                </a>
            </div>
            <!-- activity -->
            <div class="lrgCard">
                @if(auth()->user())
                    <a href="{{ url($path.'/dashboard/curricula') }}">
                @else
                    <a href="{{ url($path.'/register') }}">
                @endif
                    <img class="lrgImg" src="{{ asset('img/products/c-account.png') }}" alt="Want More?">
                    <p class="lrgText">Want More?
                    <br>sign up for a free account</p>
                </a>
            </div>
        </div>
    </div>

</section>
@endsection
