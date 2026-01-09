@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <span style="font-weight: bold;color:#9acd57">Impressum</span>
        </div>
    </div>

    <!-- Impressum HEADER-->
    <div class="container">
        <div class="row p-5">
            <p>This website is owned and maintained by UN|HUSHED<span style="font-size: 10px; vertical-align:top; ">®</span>.
                <br/>A 501(c)(3) EIN 82-3184837 established 10-24-2017.
                <br/>112 Burros Tail CV, Austin, TX 78737
                <br/><a href="mailto:info@unhushed.org">info@unhushed.org</a>
                <br/>© {{date('Y')}}  UN|HUSHED. All rights reserved.
            </p>
        </div>
    </div>
</section>
@endsection
