@extends('layouts.app')
@section('content')
<section>
    <!-- PHONE SIDEBAR -->
    <div class="phone">
        <div class="scrollmenu">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url($path.'/swag') }}"> Swag, Clothes, Donation Gifts</a> |
            <span style="font-weight: bold;color:#9acd57">Donate</span>
        </div>
    </div>

    <!-- BREADCRUMBS -->
    <div class="crumbBar desk">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url($path.'/swag') }}"> Swag, Clothes, Donation Gifts</a> |
            <span style="font-weight: bold;color:#9acd57">Donate</span>
        </div>
    </div>    

    <div class="container">
        <div class="row">
            <div class="col-md-3 md-order-1 col-sm-12 sm-order-2">
                <!-- IMAGE -->
                <div class="box-img">
                    <div class="row justify-content-center justify-content-sm-start">
                        <!-- images -->
                        <div class="col-auto order-sm-1 order-2">
                        </div>
                        <div class="col-md-6 order-md-2 order-1 bg-black">
                            <div class="gallery">
                                <img src="{{ url('img/octos/ollie-donate.png') }}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 md-order-2 col-sm-12 sm-order-1">
            <!-- INFO -->
            <div class="row no-gutters">
                <!-- NAME -->
                <div class="col-12 box-name">
                    Donate
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <!-- PRICE -->
                <div class="col-auto pr-5 info">
                    PRICE
                </div>
                <div class="col-auto infoA">
                    <b>$ 5 - $5,000 your donation counts!</b>
                </div>
                <div class="col-auto pr-5 infoA">
                    We're not joking when we say, "<b>Sex Ed Saves Lives</b>."
                    <br/>With your help we can reach a broader audience, keep the materials up to date and medically accurate, and continue to expand our content
                    in vital ways. In short, you are helping us to work towards a kinder, smarter, safer world. <b>Thank you for believing in what we do</b>.
                </div>
            </div>
            <!-- CART -->
            <div class="col-md-3 col-sm-12 order-3">
                <div class="col-md-4 col-sm-12 box-cart">
                    <div class="col-12">
                        <h4>Donate</h4>
                    </div>
                    <button href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q" target="_blank" class="btn btn-secondary add-cart">DONATE</button>
                </div>
            </div>
        </div>
    </div>
</section>

<style type="text/css">
    ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    /*add to cart text */
    a.btn {
        color: #fff;
    }
    .info {
        color: #000;
        font-variant-caps: all-small-caps;
        font-weight: bold;
        padding: 10px 0;
    }
    .infoA {
        padding: 10px 0;
    }

    @media (max-width: 1145px) { /* mobile */
        .box-name {
            text-align: center;
            width: 100%;
            height: auto;
            padding: 0;
            font-family: diazo-mvb-rough2-cond, sans-serif;
            font-style: normal;
            font-weight: 700;
            font-size: 50px;
            line-height: 20px;
            font-size: 45px;
        }
        .box-img {
            text-align: center;
            width: 100%;
            height: auto;
            margin: 0 auto 20px auto;
        }
        .box-cart {
            text-align: center;
            width: 100%;
            height: auto;
            margin: 0 auto;
            padding: 60px;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 20px 30px;
        }
        .box-price {
            display: none;
        }
        .box-info {
            top: 40em;
            width: 100%;
            height: auto;
            padding: 20px 30px;
        }
    }
    @media (min-width: 960px) { /* desktops */
        .box-img {
            position: fixed;
            top: 12em;
            bottom: 0;
            left: 1em;
            width: 30%;
            height: 400px;
            padding: 15px 20px;
        }
        .box-name {
            width: 600px;
            height: 35px;
            margin-left: 30%;
            margin-right: 25%;
            font-family: diazo-mvb-rough2-cond, sans-serif;
            font-style: normal;
            font-weight: 700;
            font-size: 50px;
            line-height: 30px;
            font-size: 30px;
        }
        .box-price {
            width: 600px;
            height: 35px;
            margin-bottom: 30px;
            margin-left: 30%;
            margin-right: 25%;
        }
        .box-info {
            width: 600px;
            height: auto;
            margin-left: 30%;
            margin-right: 25%;
        }
        .box-cart {
            position: fixed;
            top: 12em;
            right: 2em;
            text-align: center;
            width: 300px;
            max-width: 300px;
            height: 300px;
            margin-top: 14px;
            padding: 60px 40px;
            border: 1px solid #ccc;
            border-radius: 6px;
            z-index: 1;
            background-color: white;
        }
    }
    .gallery {
        width: 100%;
        height: auto;
    }
    .gallery .inner {
        position: relative;
        display: block;
        width: auto;
        max-width: 800px;
    }
    .gallery img {
        display: none;
    }
    .main {
        position: relative;
        width: 300px;
        max-width: 200px;
        height: 200px;
        background: #fff;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        margin: 0 auto 5px auto;
    }
    .main-selected {
        animation: crossfade 0.5s ease;
        -webkit-animation: crossfade 0.5s ease;
        -moz-animation: crossfade 0.5s ease;
    }
    @keyframes crossfade {
        0% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    @-webkit-keyframes crossfade {
        0% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    @-moz-keyframes crossfade {
        0% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    .thumb-roll {
        position: relative;
        width: auto;
        overflow-x: auto;
        overflow-y: hidden;	
        white-space: nowrap;
    }
    .thumb {
        display: inline-block;
        position: relative;
        width: 100px;
        height: 100px;
        margin-right: 5px;
        background: #fff;
        overflow: hidden;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        overflow: hidden;
        cursor: pointer;
    }
    .thumb:last-of-type {
        margin-right: 0px;
    }
    .thumb:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
    }
    .thumb.current:after {
        cursor: default;
    }
    .thumb:hover:after {
        box-shadow: inset 2px 2px 0px rgba(51, 204, 255, 1), inset -2px -2px 0px rgba(51, 204, 255, 1);
    }
</style>
<script>
    // Fit inner div to gallery
    $('<div />', { 'class': 'inner'  }).appendTo('.gallery');
        // Create main image block and apply first img to it
        var imageSrc1 = $('.gallery').children('img').eq(0).attr('src');
        $('<div />', { 'class': 'main'  }).appendTo('.gallery .inner').css('background-image', 'url(' + imageSrc1 + ')');
        // Create image number label
        var noOfImages = $('.gallery').children('img').length;
        // Create thumb roll
        $('<div />', { 'class': 'thumb-roll'  }).appendTo('.gallery .inner');
        // Create thumbail block for each image inside thumb-roll
        $('.gallery').children('img').each( function() {
            var imageSrc = $(this).attr('src');
            $('<div />', { 'class': 'thumb'  }).appendTo('.gallery .inner .thumb-roll').css('background-image', 'url(' + imageSrc + ')');
    });

    // Make first thumbnail selected by default
    $('.thumb').eq(0).addClass('current');

    // Select thumbnail function
    $('.thumb').click(function() {
        // Make clicked thumbnail selected
        $('.thumb').removeClass('current');
        $(this).addClass('current');
        // Apply chosen image to main
        var imageSrc = $(this).css('background-image');
        $('.main').css('background-image', imageSrc);
        $('.main').addClass('main-selected');
        setTimeout(function() {
            $('.main').removeClass('main-selected');
        }, 500);
        // Change text to show current image number
        var imageIndex = $(this).index();
    });

    // Arrow key control function
    $(document).keyup(function(e) {

    // If right arrow
    if (e.keyCode === 39) {
        // Mark current thumbnail
        var currentThumb = $('.thumb.current');
        var currentThumbIndex = currentThumb.index();
        if ( (currentThumbIndex+1) >= noOfImages) { // if on last image
            nextThumbIndex = 0; // ...loop back to first image
        } else {
            nextThumbIndex = currentThumbIndex+1;
        }
        var nextThumb = $('.thumb').eq(nextThumbIndex);
        currentThumb.removeClass('current');
        nextThumb.addClass('current');
        // Switch main image
        var imageSrc = nextThumb.css('background-image');
        $('.main').css('background-image', imageSrc);
        $('.main').addClass('main-selected');
        setTimeout(function() {
            $('.main').removeClass('main-selected');
        }, 500);
    }
    
    // If left arrow
    if (e.keyCode === 37) { 
        // Mark current thumbnail
        var currentThumb = $('.thumb.current');
        var currentThumbIndex = currentThumb.index();
        if ( currentThumbIndex == 0) { // if on first image
            prevThumbIndex = noOfImages-1; // ...loop back to last image
        } else {
            prevThumbIndex = currentThumbIndex-1;
        }
        var prevThumb = $('.thumb').eq(prevThumbIndex);
        currentThumb.removeClass('current');
        prevThumb.addClass('current');
        // Switch main image
        var imageSrc = prevThumb.css('background-image');
        $('.main').css('background-image', imageSrc);
        $('.main').addClass('main-selected');
        setTimeout(function() {
            $('.main').removeClass('main-selected');
        }, 500);
        // Change text to show current image number
        $('.gallery .inner .main span').html('Image ' + (prevThumbIndex+1) + ' of ' + noOfImages);
        }
    });
</script>
@endsection
