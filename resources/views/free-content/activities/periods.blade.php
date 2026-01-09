@extends('layouts.app')

@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <a href="{{ url($path.'/free-activities') }}">Activities</a> |
            <span style="font-weight: bold;color:#9acd57">Menstruation Information Station!</span>
        </div>
    </div>

    <!-- FREE HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/products/uterus.png') }}" alt="A uterus 'standing' proud"></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">Menstruation Information Station!</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:800px">Uterus havers unite! Here's a bunch of information that's handy to have if you have a menstruating uterus, or know someone who does.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <!-- about -->
            <p><ul class="green">
                <li><a href="{{ url($path.'/periods') }}">
                    <i class="fa-solid fa-book fa-fw fa-lg align-middle" class="px-1"></i>
                    UN|HUSHED Free Online period dictionary</a></li>
                <br/>
                <li><a href="https://www.biome.com.au/blogs/beauty/how-to-choose-the-right-menstrual-cup" 
                    target="_blank">
                    <i class="fa-solid fa-text-size fa-fw fa-lg align-middle" class="px-1"></i>
                    Biome, a website that has menstrual cup sizing information.</a></li>
                <br/>
                <li><a href="https://www.omnicalculator.com/everyday-life/period-products-cost" 
                    target="_blank">
                    <i class="fa-solid fa-calculator fa-fw fa-lg align-middle" class="px-1"></i>
                    Period product cost calculator by Omni calculator</a></li>
                <br/>
                <li><a href="{{ asset('pdfs/free/unhushed_intro_h-menstrual-products_v01.pdf') }}" 
                    download="unhushed_intro_h-menstrual-products_v01.pdf" class="px-1">
                    <i class="fas fa-file-download fa-fw fa-lg align-middle"></i>
                    Handout: Menstrual Care Products</a></li>
            </ul>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <!-- Donate -->
        <div class="row">
            <div class="mx-auto p-1">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input name="donate" id="donate" class="btn btn-secondary" type="submit" value="DONATE" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                    <input type="hidden" name="cmd" value="_s-xclick" >
                    <input type="hidden" name="hosted_button_id" value="VFH5ZPLVX5352" >
                </form>
            </div>
        </div>
    </div>

</section>
@endsection
