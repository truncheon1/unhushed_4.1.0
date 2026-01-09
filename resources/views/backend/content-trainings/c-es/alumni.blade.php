@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}">Dashboard</a> |
                <a href="{{ url($path.'/dashboard/trainings') }}">Trainings</a> |
                <span style="font-weight: bold;color:#9acd57">The Elementary School Curriculum</span>
            </div>
        </div>

        <!-- Header -->
        <div class="row">
            <div class="col-8 pb-2">
                <div class="card">
                    <div class="col-12 text-center pt-2">
                        <h3>The Elementary School Curriculum (in person)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-md-center">
                            <div class="col-8">
                                <ul class="green">Thank you for joining us for our past curricula trainings!
                                    <li class="pl-3">2024, May—Austin, TX</li>
                                    <li class="pl-3">2023, June—Online</li>
                                    <li class="pl-3">2022, July—Austin, TX</li>
                                    <li class="pl-3">2021, July—Online</li>
                                    <li class="pl-3">2018, Aug—Austin, TX</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row">
                    <div class="col-12 pb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class=" font-weight-bold" style="color:#9acd57;">Have a suggestion?</p>
                                <p class="text-justify">Email us at <a href="mailto:info@unhushed.org">info@unhushed.org</a> to suggest additional features or ask questions about your purchased products.</p>
                                <a href="mailto:info@unhushed.org"><i class="fa-solid fa-envelope fa-fw fa-2xl"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class=" font-weight-bold" style="color:#9acd57;">Want more?</p>
                                <p class="text-justify">Browse our ever growing selection of amazing trainings.</p>
                                <a href="{{ url($path.'/trainings') }}"><i class="fa-solid fa-cart-shopping fa-fw fa-2xl"></i></a>
                            </div>
                        </div>
                    </div>
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





