@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/projects') }}">Projects </a> |
            <a href="{{ url($path.'/free-content') }}">Free Content </a> |
            <a href="{{ url($path.'/research') }}">Research</a> |
            <span style="font-weight: bold;color:#9acd57">Statistics</span>
        </div>
    </div>

    <!-- PEDAGOGICAL HEADER-->
    <div class="row" style="padding-top:35px;">
        <div id="headerBar" class="headerBar col-lg-8 col-md-12 px-5 mx-auto">
            <div class="row">
                <div class="col-1 m-auto">
                    <img src="{{ asset('img/octos/ollie-stats.png') }}" alt="Ollie the octopus holding a bar chart" style="max-width:160px">
                </div>
                <div class="col-8 m-auto">
                    <p class="diazo" style="padding-top:5px;">Statistical Citations</p>
                    <p class="sketchnote-square">At UN|HUSHED we believe in Evidence Informed Curricula.</p>
                </div>
            </div>
            <p style="text-align:justify;" class="mb-0">This is different from Evidence Based Curricula because we are not bound by a five to ten year lag time—and we still adhere 
                to medically and statistically accurate information informed by proven pedagogy theory.</p>
            <hr>
        </div>
        <!-- STATISTICS CONTENT-->
        <div class="row justify-content-center mx-auto pb-5">
            <!-- search bar -->
            <div id="search" class="search">
                <div class="pr-4 mx-auto">
                    <form action="{{ url($path.'/statistics') }}" method="GET" role="search">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="term" placeholder="Search keywords" id="term">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit" title="Search keywords">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                                <a href="{{ url($path.'/statistics') }}" button class="btn btn-secondary" type="button" title="Refresh page">
                                    <i class="fas fa-sync-alt"></i></a>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- entries -->
            <div class="col-8">
                @foreach ($entries as $entry)
                <div class="row rounded p-3 my-2" style="min-width: 400px; background-color:#f7f8fb;">
                    <p style="font-size:14pt"><a href="{{ $entry['url'] }}" target="_blank">{{ $entry['title'] }}</a></span>
                    <br/><span style="font-size:11pt">{{ $entry['author'] }}
                    <br/>@if($entry['journal']){{ $entry['journal'] }}, @endif 
                        @if($entry['month']){{ $entry['month'] }}, @endif 
                        @if($entry['year']){{ $entry['year'] }}@endif</span></p>
                    <p><span style="font-size:11pt;color:#666666"><u>Abstract</u></span>
                    <br/><span style="font-size:10pt">{{ $entry['abstract'] }}</span></p>
                    <p><span style="font-size:11pt;color:#666666"><u>Keywords</u></span>
                    <br/><span style="font-size:10pt">{{ $entry['keywords'] }}</span></p>
                </div>
                @endforeach
        </div>
    </div>
    <style>
        p {
            font-size:18px;
            color: #265a8e;
        }
        .search {
            width: 100%;
            max-width: 800px;
            height: 40px;
            z-index: 1;
        }
        .sticky {
            position: fixed;
            top: 115px;
            right: 310px;
            height: 50px;
            max-width: 600px;
            background: white;
            padding: 5px 0 5px 10px;
            border-radius: 0 0 0 6px;
            border-bottom: 1px solid #d8dce7;
            border-left: 1px solid #d8dce7;
        }
    </style>

    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {
            stickySearchFunction()
        };
        // Get the header
        var search = document.getElementById("search");
        var headerBar = document.getElementById("headerBar");
        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function stickySearchFunction() {
            if (window.pageYOffset > 290) {
                search.classList.add("sticky");
            } else {
                search.classList.remove("sticky");
            }
        }
    </script>

</section>
@endsection
