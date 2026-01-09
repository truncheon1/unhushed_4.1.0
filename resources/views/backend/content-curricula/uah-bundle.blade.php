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
                    <a href="{{ url($path.'/dashboard/curricula') }}"> Curricula, Games, & Activities</a> |
                    <span style="font-weight: bold;color:#9acd57">{{$product->name}}</span>
                </div>
            </div>

            <!-- Product HEADER -->
            <div class="row my-3">
                <!-- Image -->
                <div class="col-2 center">
                    @if($bundledCoursesVariant && $bundledCoursesVariant->firstImage)
                        <img class="med" src="{{ asset('uploads/products/'.$bundledCoursesVariant->firstImage->image) }}" alt="{{$product->name}}">
                    @else
                        <img class="med" src="{{ asset('uploads/products/'.$product->image) }}" alt="{{$product->name}}">
                    @endif
                </div>
                <div class="col-6 pt-1">
                    <p class="sketchnote-square">{{$product->name}}</p>
                    <hr>
                    @if($bundledCoursesVariant && $bundledCoursesVariant->description)
                        <p>{!!$bundledCoursesVariant->description->description!!}</p>
                    @else
                        <p>{!!$product->description!!}</p>
                    @endif
                </div>
            </div>

            <!-- Variants/Courses Section -->
            @foreach($variants as $variant)
            <div class="row my-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header blue">
                            <h5 class="mb-0">
                                <i class="fa-light fa-book-open-lines"></i> {{$variant->name}}
                                @if(stripos($variant->name, 'Getting Started') !== false)
                                    <span class="badge badge-success ml-2">FREE</span>
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($variant->files->count() > 0)
                            <table class="table table-hover" id="variant-{{$variant->var_id}}-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:center; width: 8%;" class="align-middle">IMAGE</th>
                                        <th style="text-align:center; width: 6%;" class="align-middle"><i class="fas fa-download"></i></th>
                                        <th style="text-align:left; width: 30%;" class="align-middle">RESOURCE</th>
                                        <th style="text-align:left; width: 36%;" class="align-middle">DESCRIPTION</th>
                                        <th style="text-align:center; width: 20%;" class="align-middle">UPDATED</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($variant->files as $f)
                                    <tr>
                                        <!-- Variant Image -->
                                        <td style="text-align:center;" class="align-middle">
                                            @if($variant->firstImage)
                                                <img src="{{ asset('uploads/products/'.$variant->firstImage->image) }}" alt="{{$variant->name}}" class="variant-thumbnail">
                                            @elseif($product->image)
                                                <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{$product->name}}" class="variant-thumbnail">
                                            @else
                                                <i class="fas fa-file-alt fa-2x text-muted"></i>
                                            @endif
                                        </td>
                                        <!-- Download/Link -->
                                        <td style="text-align:center;" class="align-middle">
                                            @if($f->source == 2)
                                                <a href="{{ url($f->resource) }}" target="_blank"><i class="fas fa-link fa-lg"></i></a>
                                            @elseif($f->source == 1)
                                                <a href="{{ url('uploads/products/'.$product->reference_id.'/'.$f->resource) }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-download fa-lg"></i></a>
                                            @endif
                                        </td>
                                        <!-- Resource Name -->
                                        <td style="text-align:left;" class="align-middle">
                                            @if($f->source == 2)
                                                <a href="{{ url($f->resource) }}" target="_blank">{!!$f->activity!!}</a>
                                            @elseif($f->source == 1)
                                                <a href="{{ url('uploads/products/'.$product->reference_id.'/'.$f->resource) }}" target="_blank" rel="noopener noreferrer">{!!$f->activity!!}</a>
                                            @else
                                                {!!$f->activity!!}
                                            @endif
                                        </td>
                                        <!-- Description -->
                                        <td style="text-align:left;" class="align-middle">
                                            <small class="text-muted">{!!$variant->description->description ?? ''!!}</small>
                                        </td>
                                        <!-- Updated Date -->
                                        <td style="text-align:center;" class="align-middle">{{date('m-d-Y', strtotime($f->updated_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="text-muted mt-1 text-center">No resources available for this course yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Purchase More Section -->
            @if(!$hasPurchasedVariants)
            <div class="row my-4">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <h5><i class="fas fa-shopping-cart"></i> Want More Courses?</h5>
                        <p class="mb-2">You currently have access to the free Getting Started course. Purchase additional courses to unlock more content and activities!</p>
                        <a href="{{ url($path.'/store/unhushed-at-home-ages-12-15') }}" class="btn btn-primary">
                            Purchase More Courses
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-12 mb-5">&nbsp;</div>

        </div>

        <style type="text/css" media="all">
            body {
                background: #f0f3fa;
                color: #265a8e;
            }
            .blue {
                background-color: #265a8e;
                color: white;
                font-size: 16px;
                font-weight: 600;
            }
            .card {
                font-size: 14px;
                border: 1px solid #265a8e;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .card-header {
                padding: 15px 20px;
            }
            .card-body {
                padding: 0;
            }
            .table {
                margin-bottom: 0;
            }
            .table thead th {
                background-color: #f8f9fa;
                border-bottom: 2px solid #265a8e;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
            }
            .table tbody tr:hover {
                background-color: #f8f9fa;
            }
            .badge-success {
                background-color: #9acd57;
                padding: 5px 10px;
                font-size: 11px;
            }
            .alert-info {
                background-color: #e8f4fd;
                border-color: #bee5eb;
                color: #265a8e;
                padding: 20px;
                border-radius: 8px;
            }
            .alert-info h5 {
                color: #265a8e;
                font-weight: 600;
                margin-bottom: 10px;
            }
            .btn-primary {
                background-color: #265a8e;
                border-color: #265a8e;
                font-weight: 600;
                padding: 10px 20px;
            }
            .btn-primary:hover {
                background-color: #1d4a73;
                border-color: #1d4a73;
            }
            .variant-thumbnail {
                max-width: 80px;
                max-height: 60px;
                object-fit: cover;
                border-radius: 4px;
            }
        </style>
    </section>
@endsection
