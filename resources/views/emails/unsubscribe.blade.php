@extends('layouts.app')

@section('content')
<section>
    <!-- UNSUBSCRIBE CARD-->
    <div class="containerNoCrumb">
        <div class="loginCard">
            <div class="card-body">
                <img src="{{ asset('img/octos/ollie-laptop-sad.png') }}" alt="UN|HUSHED">
                <p class="diazo">unsubscribe</p>
                <p class="sketchnote-square" style="line-height:30px;">Are you sure you want to unsubscribe?</p>
                <form method="POST" action="#">
                    @csrf
                    <div class="form-group px-5">
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                    </div>
                    <input name="unsubscribe" id="unsubscribe" class="btn btn-secondary" type="submit" value="UNSUBSCRIBE" data-toggle="button" aria-pressed="false" autocomplete="off">
                </form>
            </div>
        </div>
    </div>

</section>
@endsection

