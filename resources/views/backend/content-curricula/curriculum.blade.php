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
                    <a href="{{ url($path.'/dashboard/curricula') }}"> Curricula, Games, & Activites</a> |
                    <span style="font-weight: bold;color:#9acd57">{{$package->name}}</span>
                </div>
            </div>

            <!--  TABLE -->
            <div class="row">
                <div class="col mr-5 mb-5">
                    <table class="table" id="elementaryTable">
                        <thead>
                            <tr>
                                <th style="text-align:center; width: 15%;" class="align-middle">UNIT</th>
                                <th style="text-align:center; width: 8%;" class="align-middle">SESSION</th>
                                <th style="text-align:center; width: 5%;" class="align-middle"><i class="fas fa-download"></i></th>
                                <th style="text-align:left; width: 22%;" class="align-middle">DOCUMENT</th>
                                <th style="text-align:center; width: 10%;" class="align-middle">TIME (mins)</th>
                                <th style="text-align:left; width: 30%;" class="align-middle">KEYWORDS</th>

                                <th style="text-align:center; width: 10%;" class="align-middle">UPDATED</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($package->units() as $c)
                            @foreach($c->sessions() as $s)
                            @foreach($s->documents() as $d)
                            <tr>
                                <!-- unit -->
                                <td style="text-align:center; width: 15%;" class="align-middle">{{$c->name}}</td>
                                <!-- session -->
                                <td style="text-align:center; width: 8%;" class="align-middle">{{str_pad($s->number, 2, 0, STR_PAD_LEFT)}}</td>
                                <!-- document -->
                                <td style="text-align:center; width: 5%;" class="align-middle">
                                    @empty($d->filenfo)
                                        <a href="{{ url($d->url) }}" target="_blank"><i class="fas fa-link fa-lg"></i></a>
                                    @else
                                        <a href="{{ curriculum_doc_url($d->filenfo) }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-download fa-lg"></i></a>
                                    @endempty
                                </td>
                                <td style="text-align:left; width: 22%;" class="align-middle">{!!$d->name!!}</td>
                                <td style="text-align:center; width: 10%;" class="align-middle">{{$d->time}}</td>
                                <td style="text-align:left; width: 30%;" class="align-middle">{{$d->keywords}}</td>
                                <td style="text-align:center; width: 10%;" class="align-middle">{{date('m-d-Y', strtotime($d->updated_at))}}</td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-12 mb-5">&nbsp;</div>
            </div>
        </div>

        <style type="text/css" media="all">
            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                background-color: #265a8e;
                border-radius: 10px;
                overflow: hidden;
            }
            thead, tbody, tr, td, th {
                display: block;
            }
            tr:after {
                content: ' ';
                display: block;
                visibility: hidden;
                clear: both;
            }
            thead th {
                height: auto;
                line-height: 30px;
            }
            tbody {
                height: 600px;
                overflow-y: auto;
                background-color: #fff;
            }
            thead {
                width: 97%;
                width: calc(100% - 17px);
                color: white;
                font-size: small;
                padding: 1px;

            }
            tbody {
            }
            tbody td, thead th {
                float: left;
            }
            tbody td:last-child, thead th:last-child {
                border-right: none;
            }
        </style>
    </section>
@endsection
