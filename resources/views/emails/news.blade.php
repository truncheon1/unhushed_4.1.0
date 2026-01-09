@extends('layouts.app')
@section('content')
<section style="background:#f0f3fa;">
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{ url($path.'/support') }}">Client Support</a> |
            <span style="font-weight: bold;color:#9acd57">News</span>
        </div>
    </div>

    <!-- CONTACT CARD-->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mx-auto my-3" style="max-width: 500px;">
                    <div class="loginCard p-3">
                        <div class="card-body">
                        <img class="img-s" src="{{ asset('img/octos/ollie-typewriter.png') }}" alt="Ollie the octopus typing on a typewriter">
                        <p class="diazo">Newsletter</p>
                        <div class="_form_5"></div>
                        <script src="https://unhushed.activehosted.com/f/embed.php?id=5" charset="utf-8"></script>
                    </div>
                </div>
                <div class="col-12 text-center pt-3">
                    <button class="btn btn-secondary" type="submit" onClick="refreshPage()">REFRESH PAGE</button>
                </div>
            </div>
        </div>
  </div>
</section>
@endsection
