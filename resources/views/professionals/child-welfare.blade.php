@extends('layouts.app')
@section('content')
<section>
    <div class="containerHandbook" style="font-size:16px">
        <!-- Buy it -->
        <div class="row hcwp justify-content-md-center">
            <div class="col-1 m-auto">
                <a href="{{ url($path.'/store/handbook-child-welfare-providers') }}"><img src="{{url('uploads/products/'.($product->primary_image_path ?? $product->image))}}" alt="{{$product->name}}"></a>
            </div>
            <div class="col-md-5 col-sm-12 mx-auto desBox">
                <div class="row">
                <p style="font-size:20px; font-weight:bold; text-transform: uppercase;">AN INTRODUCTION TO SEXUALITY EDUCATION:
                <br/><span style="color:#024318;">{{$product->name}}</span></p>
                <p><i>"These authors offer a comprehensive overview of very real and complex challenges child welfare professionals and care givers face every day."</i></p>
                <p>This manual provides child welfare providers, from foster parents to case workers, with the tools to combat problematic, inaccurate, and
                    harmful sexuality information that comes from families, classrooms, peers, partners, and the media. It supports providers as they consider their own biases, history, and knowledge base,
                    and affirms that sexuality education, provided in trauma informed ways, is an integral piece to the child welfare setting.</p>
                </div>
                <div class="row">
                    <div class="col-auto mx-auto">
                        <form action="{{url($path.'/add_to_cart/product')}}" method="get" id="addForm">
                            <input type="hidden" name="item_id" value="{{$product->reference_id}}" />
                            <div class="form-row">
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="qty" name="qty" placeholder="1" value="1">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="total" placeholder="$ {{number_format($product->price, 2)}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <a href="{{ url($path.'/cart') }}" class="btn btn-secondary add-cart"><span style="color:#fff;">ADD TO CART</span></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section overview -->
        <div class="row" style="padding-top:80px; color:#024318;">
            <div class="col-auto mx-auto my-auto">
                <h3>HANDBOOK OVERVIEW</h3>
            </div>
        </div>
        <div class="row sections" style="padding-top:40px; padding-bottom:80px; color:#024318;">
            <div class="col-3 mx-auto my-auto" style="text-align:center;">
                <h4><i class="fa-solid fa-circle-question"></i>
                <br/>Understanding Your Personal Perspective</h4>
            </div>
            <div class="col-5 mx-auto">
                <p>The first section provides guidance on self-analysis for you, the child welfare provider.
                    Understanding your personal perspective before you bring sexuality education into the child welfare space is critical.</p>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-3 mx-auto my-auto" style="text-align:center;">
                <h4><i class="fa-solid fa-tower-broadcast"></i>
                <br/>Broad Overview
                <br/>Sex & Sexuality Topics</h4>
            </div>
            <div class="col-5 mx-auto">
                <p>The second section provides a broad overview on numerous topics related to sexuality that young people bring into the child welfare setting.
                    These include anatomy, physiology, sexual arousal, reproduction, communication styles, intimate partner violence, masturbation, and more.</p>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-3 mx-auto my-auto" style="text-align:center;">
                <h4><i class="fa-solid fa-file-circle-check"></i>
                <br/>Usable Client
                <br/>Handouts & Forms</h4>
            </div>
            <div class="col-5 mx-auto">
                <p>The third section includes more than 24 handouts child welfare providers can copy and use with young people along with guides and recommendations on how to use them.</p>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-3 mx-auto my-auto" style="text-align:center;">
                <h4><i class="fa-solid fa-books"></i>
                <br/>Additional Resources</h4>
            </div>
            <div class="col-5 mx-auto">
                <p>The final section is an extensive list of resources, organized by topic and annotated with a brief description of what content they cover.</p>
            </div>
        </div>
        <!-- Praise -->
        <div class="row h-green" style="padding-top:50px;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-message-quote fa-fw"></i> PRAISE FOR THE HANDBOOK </h3>
            </div>
        </div>
        <div class="row h-green" style="padding:25px;">
            <div class="col-6 mx-auto" style="text-align:justify;">
                <p><i class="fa-solid fa-quote-left"></i> The diversity and educational content given in this book is astonishing. Not only does it have the ability to
                    challenge child welfare workers to continue evaluating their beliefs and biases and to keep an open mind when working with young people, it can also
                    be used as a tool to educate and train mentors and parents about the world of sex and sexuality. As an expert with lived experiences in foster care,
                    a young parent, and social worker, I would highly recommend this book as a tool to all professionals working with youth and adolescents.
                    <i class="fa-solid fa-quote-right"></i></p>
                <p style="text-align:right;">– Sheila VanWert, BSW
                    <br/>Expert with Lived Experiences and Child Welfare Advocate</p>
            </div>
        </div>
        
        <!-- Training -->
        <div class="row" style="padding-top:50px; color:#024318;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-headset fa-fw"></i> WE OFFER TRAININGS ON THIS HANDBOOK</h3>
            </div>
        </div>
        <div class="row justify-content-md-center" style="padding-bottom:25px; color:#024318;">
            <div class="col-3 rounded" style="text-align: center; margin:30px; padding:20px 50px 20px 50px;">
                <h4>Get more info on training</h4>
                <p style="text-align: justify;">
                    Would your organization benefit from a group training on this handbook?
                    We've got you covered!
                    Fill out the form to learn more about trainings offered by our our team of Certified Sex Educators.
                </p>
            </div>
            <div class="col-3 bg-white rounded">
                <div class="_form_17"></div>
                <script src="https://unhushed.activehosted.com/f/embed.php?id=17" charset="utf-8"></script>
            </div>
        </div>
        <!-- About UN|HUSHED -->
        <div class="row" style="padding-top:50px; background:#f0f3fa;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-address-card fa-fw"></i> ABOUT UN|HUSHED</h3>
            </div>
        </div>
        <div class="row laptop" style="background:#f0f3fa;">
            <div class="col-6 mx-auto" style="text-align:justify;">
                <p>Here at UN|HUSHED we write handbooks and curriculum on hard topics the easy way, sex ed done right! And our mission is to break the silence surrounding human sexuality.
                    The thing is, sex ed is more than just “sex”.
                    It’s about relationships, communication, and so much more.
                    <a href="{{ url($path.'/about') }}">Learn More Here</a></p>
            </div>
        </div>
        <div class="row justify-content-md-center laptop" style="padding-bottom:25px; background:#f0f3fa;">
            <div class="col-3 rounded bg-white" style="text-align: center; margin:30px; padding:30px;">
                <p><b>Other Books Available</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/breaking-the-hush-factor-2') }}">Breaking the Hush Factor v2.0</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-child-welfare-providers') }}">A Handbook for Child Welfare Providers</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/handbook-nursing-professionals') }}">A Handbook for Nursing Professionals</a>
                    <br/><i class="fa-solid fa-book fa-fw"></i> <a href="{{ url($path.'/store/how-i-got-into-sex-ed') }}">How I Got Into Sex... Ed</a>
                    
                </p>
            </div>
            <div class="col-3 rounded laptop bg-white" style="text-align: center; margin:30px; padding:30px;">
                <p><b>Additional Services</b></p>
                <p style="text-align: left; color: #9acd57;">
                    <i class="fa-solid fa-bus-school fa-fw" ></i> <a href="{{ url($path.'/curricula') }}">K-12 Comprehensive Curricula</a>
                    <br/><i class="fa-solid fa-headset fa-fw"></i> <a href="{{ url($path.'/trainings') }}">Virtual & In-person Trainings</a>
                    <br/><i class="fa-solid fa-phone fa-fw"></i> <a href="{{ url($path.'/consulting') }}">Consultation Services</a>
                </p>
            </div>
        </div>
    </div>
</section>

    <style>
        .hcwp{
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            background: url({{url('img/bgs/hcwp.jpg')}}) no-repeat center center fixed;
            padding: 20px 50px;
        }
        .desBox{
            background-color: rgba(30, 147, 70, 0.8);
            margin: 20px;
            padding: 60px;
            border-radius: 10px;
            color: #fff;
            font-size: 20px;
            text-align: justify;
        }
        .sections{
            padding: 30px 300px;
            text-align: justify;
        }
        .h-green{
            background-color: #2e8f40;
            color: #fff;
        }
                /* Slideshow container */
                * {box-sizing: border-box}
        .slideshow-container {
            position: relative;
        }
        /* Slides */
        .mySlides {
            display: none;
            padding: 30px 80px;
            text-align: justify;
        }
        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -30px;
            padding: 16px;
            font-weight: bold;
            font-size: 20px;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }
        /* Position the "next button" to the right */
        .next {
            position: absolute;
            right: 0;
            border-radius: 3px 0 0 3px;
        }
        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
            color: white;
        }
    </style>
    <script type="text/javascript">
        //add books to store
        $(".add-cart").on("click", function(e){
            e.preventDefault();
            $("#addForm").submit();
        })
        $("#qty").on("change", function(){
            console.log("GOT HERE");
            if($(this).val() < 1){
                    $(this).val(1);
            }
            $.ajax({
                url: '{{url($path."/store/".$product->id."/calc_total_products")}}',
                type: 'get',
                data: 'qty='+$(this).val(),
                success: function(response){
                    console.log(response);
                    if(response.error == true){
                        alert(response.message);
                        return;
                    }
                    $("#total").val("$"+response.total);
                },
                fail: function(){ alert("Error"); }
            });
        });
        //slider
        var slideIndex = 1;
            showSlides(slideIndex);
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
        function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}    
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "block";
        }
    </script>
</section>
@endsection
