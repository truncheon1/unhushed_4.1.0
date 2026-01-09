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
                    <a href="{{ url($path.'/backend') }}"> Admin Backend</a> |
                    <a href="{{ url($section.'backend/biologies') }}">Biologies</a>
                    <span style="font-weight: bold;color:#9acd57">Biological Sex Glossary</span>
                </div>
            </div>
                    
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card" style="width: 44rem">
                        <div class="card-header">EDIT BIOLOGICAL SEX ENTRIES</div>
                        <div class="card-body">
                            <form action="{{ route('biologies.update',$biology->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group row">
                                    <label for="term" class="col-md-3 col-form-label text-md-right">Term</label>
                                    <div class="col-md-9">
                                        <input id="term" type="text" class="form-control" name="term" value="{{ $biology->term }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="chromosomes" class="col-md-3 col-form-label text-md-right">Chromosomes</label>
                                    <div class="col-md-9">
                                        <input id="chromosomes" type="text" class="form-control" name="chromosomes" value="{{ $biology->chromosomes }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="hormones" class="col-md-3 col-form-label text-md-right">Hormones</label>
                                    <div class="col-md-9">
                                        <input id="hormones" type="text" class="form-control" name="hormones" value="{{ $biology->hormones }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="gonads" class="col-md-3 col-form-label text-md-right">Gonads</label>
                                    <div class="col-md-9">
                                        <input id="gonads" type="text" class="form-control" name="gonads" value="{{ $biology->gonads }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="external" class="col-md-3 col-form-label text-md-right">External</label>
                                    <div class="col-md-9">
                                        <input id="external" type="text" class="form-control" name="external" value="{{ $biology->external }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="internal" class="col-md-3 col-form-label text-md-right">Internal</label>
                                    <div class="col-md-9">
                                        <input id="internal" type="text" class="form-control" name="internal" value="{{ $biology->internal }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-5 mt-5">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">&nbsp;</div>
            </div>
        </div>
    </section>
@endsection
