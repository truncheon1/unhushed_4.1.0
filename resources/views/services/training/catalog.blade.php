@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/services') }}">Services Offered </a> |
            <a href="{{ url($path.'/classes') }}"> Classes</a> |
            <a href="{{ url($path.'/consulting') }}">Consulting</a> |
            <a href="{{ url($path.'/subscription-info') }}">Curricula</a> |
            Training
        </div>
    </div>

    <!-- ORG VALUES HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto mx-auto">
                    <img src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Let's talk training!</p>
                    <hr>
                    <p class="sketchnote-square text-justify" style="max-width:630px;">It takes a lot to teach. Patience, strength, time, and dedication. It takes a lot to train.
                        We respect the processes of teaching and training so much that we
                        spend over a hundred hours each year training facilitators, mental health practitioners, and nursing professionals on how to talk to people of all ages about human sexuality.</p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center py-3 mb-5">
            <div class="row justify-content-center">
                <!-- dynamic products -->
                @foreach(\App\Models\Products::where('category', 7)->orderBy('sort', 'ASC')->get() as $training)
                    <div class="lrgCard">
                        <a href="{{ url($path.'/trainings/'.$training->slug) }}">
                            <img class="lrgImg" src="{{ url('uploads/products/'.$training->image) }}" alt="{{$training->name}}" />
                            <p class="lrgText">{{$training->name}}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</section>
@endsection
