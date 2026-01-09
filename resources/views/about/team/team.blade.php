@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="/">Home </a> |
            <a href="{{url($path.'/about')}}"> About</a> |
            <span style="font-weight: bold;color:#9acd57">Our Team</span>
        </div>
    </div>

    <div class="container">
        <!-- TEAM HEADER-->
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-1 m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-globe.png') }}" alt="Ollie holding a globe.">
                </div>
                <div class="col-8 px-1 mx-auto">
                    <p class="diazo">OUR TEAM</p>
                    <hr>
                    <p class="sketchnote-square">The UN|HUSHED team is made up of authors, trainers, educators, designers, and so much more!</p>
                </div>
            </div>
        </div>

        <!-- APPLY FLEX-->
        <div class="d-flex row justify-content-center">
            @foreach(\App\Models\Team::orderBy('last', 'ASC')->get() as $member)
                <div class="team">
                    <a href="{{ url($path.'/team/'.$member->slug) }}" class="black">
                        <img class="teamImg" src="{{ url('uploads/team/'.$member->image) }}" alt="{{$member->name}}" />
                        <p class="teamName"><b>{{$member->first}} {{$member->last}}</b></p>
                        <p class="teamTitle">{{$member->title}}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

</section>

<style>
.team {
	width: 200px !important;
	height: auto;
	overflow: hidden;
	margin: 10px;
	text-align: center;
    top: 0;
    color: #000;
}
.teamImg {
    width: 180px;
    height: 180px;
}
.teamImg:hover {
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */
    filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
}
a.black:link { color:#000;}
a.black:visited { color: #000;}
a.black:active { color: #000;}

.teamName {
    height: auto;
    width: 180px;
    margin: auto;
}
.teamTitle {
    height: auto;
    width: 180px;
    margin: auto;
    font-size: 10px;
}
</style>

@endsection
