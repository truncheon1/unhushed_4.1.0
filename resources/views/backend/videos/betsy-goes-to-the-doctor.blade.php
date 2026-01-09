@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
            <a href="{{ url($path.'/dashboard/curricula') }}"> Back to Curriculum</a> |
            <span style="font-weight: bold;color:#9acd57">Betsy Goes to the Doctor</span>
        </div>
    </div>

    <!-- ACTIVITY HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center pt-5 mb-5">
            <div class="row">
                <iframe src="https://player.vimeo.com/video/665243111?h=f817e4b588" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

</section>
@endsection
