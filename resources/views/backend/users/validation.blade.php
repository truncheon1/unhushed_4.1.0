@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
        <!-- PAGE CONTENT -->
        <div class="container">
            <div class="row m-3">
                <div class="col-md-12">
                    {{$validation_string}}
                </div>
            </div>
        </div>
@endsection