@extends('layouts.app')
@section('content')
@include('layouts.dictionbar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerStore">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/store') }}"> Dictionary</a> |
                <span style="font-weight: bold;color:#9acd57">Biological Sex Glossary</span>
            </div>
        </div>
        <!-- DICTIONARY HEADER-->
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('img/cards-med/bio.png') }}" alt="Ollie using a projector screen." style="max-width:100px">
                    </div>
                    <div class="col-8 pt-2">
                        <p class="diazo2">Biological Sex Glossary</p>
                        <p style="text-align:justify"><p class="text-justify">Biological sex is made up of many different components, 
                            and people have tried to categorize it into two groups (female and male) for a long time. There are generally 
                            two ways of grouping people: by external features (more appropriately understood as gender expression rather 
                            than biological sex) and by biological markers. We will focus on a more in-depth understanding of biological 
                            markers here, as the range and variance of gender expression as reaching beyond two rigid options is generally 
                            well enough understood.
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-10 mx-auto mb-2 text-justify">
                        <p>Most people are taught from an early age that there are two sexes: female (with two X chromosomes, higher levels of estrogen and progesterone, a uterus, vagina, clitoris, breasts, and wide hips) and male (with one X and one Y chromosome, androgens, testosterone, a penis, testicles, scrotum, low voice, and facial hair). These assumptions about the characteristics and biology of the sexes will be familiar to almost everyone.</p>
                        <p>Assumptions about a person’s biology are not, however, able to be known or even guessed just by looking at a person.</p>
                        <p>Let’s begin breaking out of the binary assumption of gender by look at the biological categories, or markers, that people use when they are discussing sex. These characteristics can be placed in two general categories: Primary Sexual Characteristics (PSC) and Secondary Sexual Characteristics (SSC). PSC include parts of the body directly related to reproduction. SSC include non-reproductive biologically related differences between females and males. (See the tables below for details.)</p>
                        <p>When discussing secondary sexual characteristics, many people understand intuitively how there is a range of possibilities rather than merely two possibilities. For example, though men are, on average, taller than women, there are some women who are taller than some men. Similarly, some woman have very little breast tissue, while some men have quite a lot.</p>
                        <p>It is when discussing primary sexual characteristics that many people are less certain about the existence of a range rather than merely two options. When a person’s primary sexual characteristics do not fall under the categories of female or male, they are Intersex. Intersex can mean a wide range of things for different people. For example, someone might have XX chromosomes, but their clitoris is enlarged and looks more like a small penis. (For more information, visit the <a href="{{ url('https://interactadvocates.org/') }}" target="_blank">InterACT Advocates for Intersex Youth</a>)</p>
                        <p>It is clear, given the biological evidence, that biological sex is a range in the same ways that gender identity and expression are ranges. However, most people in the United States are less aware of the potential range of biological sex. Keeping this information in mind will help you support participants’ understanding of themselves and other people. The following table goes into some depth describing the many variations that are possible. Keep in mind science is ever evolving and this table may not include every possible variation.</p>
                    </div>
                    <div class="col-10 mx-auto mb-5">
                        <table class="table table-striped table-bordered" style="width: 100%" id="biologiesTable">
                            <thead>
                                <tr>
                                    <th id="term" colspan="1" style="text-align:center">Term</th>
                                    <th id="chromosomes" colspan="1" style="text-align:center">Chromosomes</th>
                                    <th id="hormones" colspan="1" style="text-align:center">Hormones</th>
                                    <th id="gonads" colspan="1" style="text-align:center">Gonads</th>
                                    <th id="external" colspan="1" style="text-align:center">External</th>
                                    <th id="internal" colspan="1" style="text-align:center">Internal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($biologies as $biology)
                                <tr id="row_{{$biology->id}}">
                                    <td colspan="1">{{ $biology->term}} </td>
                                    <td colspan="1">{{ $biology->chromosomes}} </td>
                                    <td colspan="1">{{ $biology->hormones }}</td>
                                    <td colspan="1">{{ $biology->gonads }}</td>
                                    <td colspan="1">{{ $biology->external }}</td>
                                    <td colspan="1">{{ $biology->internal }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
