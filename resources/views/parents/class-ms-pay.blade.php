@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/parents') }}">Home </a> |
            <a href="{{ url($path.'/middle-school-classes') }}"> Middle School Classes</a> |
            <span style="font-weight: bold;color:#9acd57">Payment</span>
        </div>
    </div>

    <!-- MS CLASS REG HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-cart.png') }}" alt="Ollie pushing a shopping cart."></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">MS Payment Options</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:500px">
                        We're so glad you've chosen to register your youth in our up coming Middle School Sex Ed Classes.
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center pt-3">
            <!-- pay for classes -->
            <div class="col-md-3 col-sm-12 text-center">
                <b>Full student tuition</b>
                <br/>(1 payment of $750)
                <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="FFJEXCA5CKM96">
                    <div class="form-group mt-2">
                        <input name="submit" id="submit" class="btn btn-secondary" type="submit" value="ADD TO CART" data-toggle="button" aria-pressed="false" autocomplete="off">
                    </div>
                </form>
            </div>
            <div class="col-md-3 col-sm-12 text-center">
                <b>Tuition Payment plan</b>
                <br/>(6 payments of $125)
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="W9WC7BSM8AYXL">
                    <div class="form-group mt-2">
                        <input name="submit" id="submit" class="btn btn-secondary" type="submit" value="ADD TO CART" data-toggle="button" aria-pressed="false" autocomplete="off">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style type="text/css">
a.btn{
    color: #fff;
}
</style>

<script>
    $(".thumbs a").on('click', function(e) {
        e.preventDefault();
        $('.big').attr('src', $(this).attr('href'));
    });
</script>

@endsection
