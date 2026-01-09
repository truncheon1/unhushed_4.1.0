@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Client Support</span>
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
                    <p class="diazo">Contact Us</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:620px">We’re all about help. Click the option that best fits your needs below.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SUPPORT -->
    <div class="container">
        <div class="row pt-2 pb-5">
            <div class="d-inline-flex justify-content-center flex-wrap mx-auto">
                <a href="{{ url($path.'/contact') }}">
                    <div class="lrgCard1">
                        <div class="txtBox">Contact Us</div>
                        <img src="{{ asset('img/octos/ollie-phone.png') }}" style="width: 150px; height: 150px;" alt="">
                    </div>
                </a>
                <a href="{{ url($path.'/c-review') }}">
                    <div class="lrgCard2">
                        <div class="txtBox">Curriculum Review</div>
                        <img src="{{ asset('img/octos/ollie-computer.png') }}" style="width: 150px; height: 150px;" alt="">
                    </div>
                </a>
                <a href="{{ url($path.'/bug') }}">
                    <div class="lrgCard3">
                        <div class="txtBox">Report a Bug</div>
                        <img src="{{ asset('img/octos/ollie-bug.png') }}" style="width: 150px; height: 150px;" alt="">
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        .lrgCard1 {
            background: linear-gradient(180deg, rgba(240,243,250,1) 0%, rgba(1,167,211,1) 100%);
            -moz-box-shadow:    4px 5px 6px 4px #ccc;
            -webkit-box-shadow: 4px 5px 6px 4px #ccc;
            box-shadow:         4px 5px 6px 4px #ccc;
            width: 225px;
            height: 225px;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px;
            color: #01a7d3;
            font-weight: bolder;
            text-transform: uppercase;
            text-align: center;
            font-size: large;
            position: relative;
            top: 0;
            transition: top ease 0.1s;
        }
        .lrgCard2 {
            background: linear-gradient(180deg, rgba(240,243,250,1) 0%, rgba(38,90,142,1) 100%);
            -moz-box-shadow:    4px 5px 6px 4px #ccc;
            -webkit-box-shadow: 4px 5px 6px 4px #ccc;
            box-shadow:         4px 5px 6px 4px #ccc;
            width: 225px;
            height: 225px;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px;
            color: #01a7d3;
            font-weight: bolder;
            text-transform: uppercase;
            text-align: center;
            font-size: large;
            position: relative;
            top: 0;
            transition: top ease 0.1s;
        }
        .lrgCard3 {
            background: linear-gradient(180deg, rgba(240,243,250,1) 0%, rgba(154,205,87,1) 100%);
            -moz-box-shadow:    4px 5px 6px 4px #ccc;
            -webkit-box-shadow: 4px 5px 6px 4px #ccc;
            box-shadow:         4px 5px 6px 4px #ccc;
            width: 225px;
            height: 225px;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px;
            color: #01a7d3;
            font-weight: bolder;
            text-transform: uppercase;
            text-align: center;
            font-size: large;
            position: relative;
            top: 0;
            transition: top ease 0.1s;
        }
        .lrgCard1:hover, .lrgCard2:hover, .lrgCard3:hover{
            top: -5px;
            color: #265a8e;
        }
        .txtBox {
            width: 225x;
            height: 55px;
            padding-top: 15px;
        }


</style>

</section>
@endsection
