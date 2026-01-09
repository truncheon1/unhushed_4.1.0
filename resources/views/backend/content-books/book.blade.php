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
                <a href="{{ url($path.'/dashboard/books') }}"> Handbooks</a> |
                <span style="font-weight: bold;color:#9acd57"> {{$book->name}} </span>
            </div>
        </div>

        <!-- book Card -->
            <div class="row my-3">
                <!-- Welcome -->
                <div class="col-2 center">
                    <img class="med" src="{{ url('uploads/products/'.$book->image) }}">
                </div>
                <div class="col-5 pt-1">
                    <p class="sketchnote-square">{{$book->name}}</b></p>
                    <hr>
                    <p>{!!$book->description!!}</p>
                </div>
            </div>

            <!-- book -->
            <div class="row  my-3">
                <div class="col-7">
                    <table class="table" id="bookTable">
                        <thead>
                            <tr>
                                <th style="text-align:center;" class="align-middle"><i class="fas fa-download"></i></th>
                                <th style="text-align:left;" class="align-middle">RESOURCE</th>
                                <th style="text-align:center;" class="align-middle">TIME (mins)</th>
                                <th style="text-align:left;" class="align-middle">KEYWORDS</th>
                                <th style="text-align:center;" class="align-middle">UPDATED</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $f)
                            <tr>
                                <!-- document -->
                                <td style="text-align:center; width: 5%;" class="align-middle">
                                    @if($f->source == 2)
                                        <a href="{{ url($f->resource) }}" target="_blank"><i class="fas fa-link fa-lg"></i></a>
                                    @elseif($f->source == 1)
                                        <a href="{{ url('uploads/products/'.$book->reference_id.'/'.$f->resource) }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-download fa-lg"></i></a>
                                    @endif
                                </td>
                                <td style="text-align:left; width: 28%;" class="align-middle">{!!$f->activity!!}</td>
                                <td style="text-align:center; width: 10%;" class="align-middle">{{$f->time}}</td>
                                <td style="text-align:left; width: 30%;" class="align-middle">{{$f->keywords}}</td>
                                <td style="text-align:center; width: 12%;" class="align-middle">{{date('m-d-Y', strtotime($f->updated_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-12 mb-5">&nbsp;</div>
            </div>

        <style type="text/css" media="all">
            body {
                background: #f0f3fa;
                color: #265a8e;
            }
            .blue{
                background-color: #265a8e;
                color: white;
                font-size: small;
            }
            .card{
                font-size: 14px;
            }
        </style>
    </section>
@endsection
