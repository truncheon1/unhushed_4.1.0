@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <span style="font-weight: bold;color:#9acd57">Parent Page</span>
            </div>
        </div>

        <!-- Training Card -->
        <div class="row">
            <div class="col-9">
                <div class="d-flex justify-content-center align-items-md-center flex-wrap pb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 text-center align-self-center">
                                    <img src="/img/products/class-ms.png" alt="Ollie"></a>
                                </div>
                                <div class="col-lg-8 col-md-12 p-md-5 text-justify">
                                    <p class="text-justify">Thank you for signing up. You should receive a recipt from PayPal with a charge from UN|HUSHED. 
                                        <!-- This page will contain a google calendar with your youth's class schedule as well as any other information you may need to 
                                        know during the semester.--> </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">&nbsp;</div>
                            </div>
                            @include('backend.content-trainings.pdf-instruct')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal video 1 -->
    <div id="video1" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    <!-- <iframe src="#" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe> -->
                    Video Coming Soon.
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#video1").on('hidden.bs.modal', function (e) {
            $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
        });
    </script>
</section>
@endsection






