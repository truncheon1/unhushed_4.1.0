@extends('layouts.app')
@section('content')
<section>
    <div class="containerHandbook" style="font-size:16px">
        <!-- Buy it -->
        <div class="row hpcp justify-content-md-center">
            <div class="col-1 m-auto">
                <a href="{{ url($path.'/store/handbook-primary-care-providers') }}"><img src="{{url('uploads/products/'.($product->primary_image_path ?? $product->image))}}" alt="{{$product->name}}"></a>
            </div>
            <div class="col-md-5 col-sm-12 mx-auto desBox">
                <div class="row">
                <p style="font-size:20px; font-weight:bold; text-transform: uppercase;">AN INTRODUCTION TO SEXUALITY EDUCATION:
                    <br/>{{$product->name}}
                </p>
                <p><i>"This book is an essential guide to help practitioners feel more at ease and more well-resourced when it comes to addressing the sexual health of their patients."</i></p>
                <p>This manual provides primary care providers, including MDs, PAs, and others, with the tools to combat problematic, inaccurate,
                    and harmful sexuality information that comes from families,
                    classrooms, peers, partners, and the media. It supports PCPs as they consider their own biases, history, and knowledge base,
                    and affirms that sexuality education is an integral piece to all clinical work.</p>
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
        <div class="row" style="padding-top:80px; color:black;">
            <div class="col-auto mx-auto my-auto">
                <h3>HANDBOOK OVERVIEW</h3>
            </div>
        </div>
        <div class="row sections" style="padding-top:40px; padding-bottom:80px; color:black;">
            <div class="col-3 mx-auto my-auto" style="text-align:center;">
                <h4><i class="fa-solid fa-circle-question"></i>
                <br/>Understanding Your Personal Perspective</h4>
            </div>
            <div class="col-5 mx-auto">
                <p>The first section provides guidance on self-analysis you, the primary care provider. Understanding your personal perspective before bringing sexuality education into the clinical
                    space is critical.</p>
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
                <p>The second section provides a broad overview to numerous topics related to sexuality that clients bring into the clinical setting.
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
                <p>The third section includes 22 handouts you can copy and use with clients along with guides and recommendations on how to use them.</p>
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
        <div class="row h-grey" style="padding-top:50px;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-message-quote fa-fw"></i> PRAISE FOR THE HANDBOOK </h3>
            </div>
        </div>
        <div class="row h-grey">
            <div class="col-8 mx-auto">
                <div class="slideshow-container">
                    <div class="mySlides">
                        <p><i class="fa-solid fa-quote-left"></i> This is a comprehensive book on sexual health with concise, positive information on sexual health. A ‘must read’ 
                            for primary care physicians. It is packed with factual, relevant information that is an invaluable resource for improving all-inclusive healthcare 
                            delivery. The authors are well grounded in clinical practice, and provide a guide to helping clinicians develop comfort with discussing sexuality 
                            with their patients. 
                        <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">
                            – Renee J. Flores, MD, MHSA, FACP, EdD, CSE, CSC
                        </p>
                    </div>
                    <div class="mySlides">
                        <p><i class="fa-solid fa-quote-left"></i> Sexuality education is our work in primary care. My favorite features in this handbook are the handouts and 
                        that it’s broken out by age group, making it a wonderful clinical reference tool.  Time based billing allows me schedule sexuality health follow-up 
                        visits between preventive exams. Patients trust us and not only need this information, but need to know they have a place in their primary care home to 
                        discuss sexual health - this book fills a gap in our comfort level to provide appropriate, accurate, and person-centered sexuality education.
                        <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">
                            – Emily Cheshire, DNP, MS, FNP-BC
                    </div>
                    <div class="mySlides">
                        <p><i class="fa-solid fa-quote-left"></i> This book is an essential guide to help practitioners feel more at ease and more well-resourced when it comes 
                        to addressing the sexual health of their patients.
                        <i class="fa-solid fa-quote-right"></i></p>
                        <p style="text-align:right;">– Caitlin O'Connor, ND</p>
                    </div>
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Training -->
        <div class="row laptop" style="padding-top:50px; color:black;">
            <div class="col-auto mx-auto my-auto">
                <h3><i class="fa-solid fa-headset fa-fw"></i> WE OFFER TRAININGS ON THIS HANDBOOK</h3>
            </div>
        </div>
        <div class="row laptop justify-content-md-center" style="padding-bottom:25px; color:black;">
            <div class="col-3 rounded" style="text-align: center; margin:30px; padding:20px 50px 20px 50px;">
                <h4>Get more info on training</h4>
                <p style="text-align: justify;">
                    Would your organization benefit from a group training on this handbook?
                    We've got you covered!
                    Fill out the form to learn more about trainings offered by our our team of Certified Sex Educators.
                </p>
            </div>
            <div class="col-3 bg-white rounded" style="color:#024318;">
                <div class="_form_23"></div>
                <script src="https://unhushed.activehosted.com/f/embed.php?id=23" charset="utf-8"></script>
            </div>
        </div>
        <!-- About UN|HUSHED -->
        <div class="row" style="padding-top:50px; background:#f0f3fa;">
                <div class="col-auto mx-auto my-auto">
                    <h3><i class="fa-solid fa-address-card fa-fw"></i> ABOUT UN|HUSHED</h3>
                </div>
            </div>
            <div class="row" style="background:#f0f3fa;">
                <div class="col-6 mx-auto" style="text-align:justify;">
                    <p>Here at UN|HUSHED we write handbooks and curriculum on hard topics the easy way, sex ed done right! And our mission is to break the silence surrounding human sexuality.
                        The thing is, sex ed is more than just “sex”.
                        It’s about relationships, communication, and so much more.
                        <a href="{{ url($path.'/about') }}">Learn More Here</a></p>
                </div>
            </div>
            <div class="row justify-content-md-center" style="padding-bottom:25px; background:#f0f3fa;">
                <div class="col-3 rounded bg-white" style="text-align: center; margin:30px; padding:20px 50px 20px 50px;">
                    <p><b>Other Books Available</b></p>
                    <p style="text-align: left;">
                        <i class="fa-solid fa-book fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/store/breaking-the-hush-factor') }}">Breaking the Hush Factor</a>
                        <br/><i class="fa-solid fa-book fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/store/handbook-child-welfare-providers') }}">A Handbook for Child Welfare Providers</a>
                        <br/><i class="fa-solid fa-book fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/store/handbook-nursing-professionals') }}">A Handbook for Nursing Professionals</a>
                        <br/><i class="fa-solid fa-book fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/store/how-i-got-into-sex-ed') }}">How I Got Into Sex... Ed</a>
                    </p>
                </div>
                <div class="col-3 rounded bg-white" style="text-align: center; margin:30px; padding:20px 50px 20px 50px;">
                    <p><b>Other Services Offered</b></p>
                    <p style="text-align: left;">
                        <i class="fa-solid fa-bus-school fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/curricula') }}">K-12 Comprehensive Curricula</a>
                        <br/><i class="fa-solid fa-headset fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/trainings') }}">Virtual & In-person Trainings</a>
                        <br/><i class="fa-solid fa-phone fa-fw" style="color: #9acd57;"></i> <a href="{{ url($path.'/consulting') }}">Consultation Services</a>
                    </p>
                </div>
            </div>
        </div>

    <style>
        .hpcp{
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            background: url({{url('img/bgs/hpcp.jpg')}}) no-repeat center center fixed;
            padding: 20px 50px;
        }
        .desBox{
            background-color: rgba(0, 0, 0, 0.4);
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
        .h-grey{
            background-color:#7f858c;
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
            border-radius: 3px;
            user-select: none;
            color: white;
            text-decoration: none !important;
        }
        /* Position the "next button" to the right */
        .next {
            position: absolute;
            right: 0;
            border-radius: 3px;
        }
        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
            color: white;
        }
    </style>
    <script type="text/javascript">
        //add books to cart
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
