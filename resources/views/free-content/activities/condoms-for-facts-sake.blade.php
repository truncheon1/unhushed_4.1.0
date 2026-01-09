@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <a href="{{ url($path.'/free-activities') }}">Activities</a> |
            <span style="font-weight: bold;color:#9acd57">Condoms. For fact's sake!</span>
        </div>
    </div>

    <!-- ACTIVITY HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/condoms/condom1.png') }}" alt="What is a condom?">
                </div>
                <div class="col-10 mx-auto p-3 bg-white text-center">
                    <a href="https://player.vimeo.com/video/428098832" target="_blank" allow="autoplay; fullscreen"
                    onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=640,height=360');
                    return false;">
                    <input name="play1" id="play1" class="btn btn-secondary" type="submit" value="PLAY VIDEO" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                    </a>
                </div>
                <div class="col-10 mx-auto bg-white">
                    <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/condoms/condom2.png') }}" alt="What is a condom?">
                </div>
                <div class="col-10 mx-auto p-3 bg-white text-center">
                    <a href="https://player.vimeo.com/video/428049005" target="_blank" allow="autoplay; fullscreen"
                    onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=640,height=360');
                    return false;">
                    <input name="play2" id="play2" class="btn btn-secondary" type="submit" value="PLAY VIDEO" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                </a>
            </div>
            <div class="col-10 mx-auto bg-white">
                <img class="img-fluid max-width:100% height:auto" src="{{ asset('img/activities/condoms/condom3.png') }}" alt="What is a condom?">
            </div>
            <div class="col-10 mx-auto p-3 bg-white text-center">
                <a href="https://player.vimeo.com/video/428049005" target="_blank" allow="autoplay; fullscreen"
                onClick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=640,height=360');
                return false;">
                <input name="play3" id="play3" class="btn btn-secondary" type="submit" value="PLAY VIDEO" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
            </a>
        </div>
        </div>
    </div>

</section>
@endsection
