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
                <span style="font-weight: bold;color:#9acd57">Biological Sex Glossary</span>
            </div>
        </div>
        <!--  TABLE -->
        <div class="row mb-5">
            <div class="col-11">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-6"><b>BIOLOGICAL SEX ENTRIES</b></div>
                            <div class="col-6 text-right">
                                @if(auth()->user() && (auth()->user()->can('access-master') || auth()->user()->can('modify-dictionaries')))
                                <a class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addTerm">ADD</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="bioTable">
                            <thead>
                                <tr>
                                    <th id="bulk">Bulk</th>
                                    <th id="term"         colspan="1" style="text-alignleft">Term</th>
                                    <th id="chromosomes"  colspan="1" style="text-alignleft">Chromosomes</th>
                                    <th id="hormones"     colspan="1" style="text-align:left">Hormones</th>
                                    <th id="gonads"       colspan="1" style="text-align:left">Gonads</th>
                                    <th id="external"     colspan="1" style="text-align:left">External</th>
                                    <th id="internal"     colspan="1" style="text-align:left">Internal</th>
                                    <th id="options"      colspan="1">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Biology::orderBy('term', 'ASC')->get() as $b)
                                <tr id="row_{{$b->id}}">
                                    <td style="text-align:center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="bulkSelect"/>
                                        </div>
                                    </td>
                                    <td >{{$b->term}}</td>
                                    <td colspan="1">{{ $b->chromosomes }}</td>
                                    <td colspan="1">{{ $b->hormones }}</td>
                                    <td colspan="1">{{ $b->gonads }}</td>
                                    <td colspan="1">{{ $b->external }}</td>
                                    <td colspan="1">{{ $b->internal }}</td>
                                    <td colspan="1" style="text-align:center">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a href="#" class="dropdown-item text-dark term-edit" rel="{{$b->id}}" data-bs-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#editTerm">Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger term-delete" rel="{{$b->id}}" href="{{url($path.'/backend/Terms/'.$b->id)}}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align:right"><i class="fas fa-trash"></i></td>
                                    <td colspan="2" style="text-align:left"> Bulk Delete</td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 my-1">&nbsp;</div>
        </div>
    </div>

    <!-- ADD TERM MODAL -->
    <div class="modal fade" id="addTerm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD TERM
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{url($path.'/backend/biological')}}" method="POST" id="add_term" role="form" onsubmit="return addTerm()" aria-label="add_term">
                        @csrf
                        <div class="form-group row">
                            <label for="term" class="col-md-3 col-form-label text-md-right">Term</label>
                            <div class="col-md-9">
                                <input id="term" type="text" class="form-control" name="term" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="chromosomes" class="col-md-3 col-form-label text-md-right">Chromosomes</label>
                            <div class="col-md-9">
                                <input id="chromosomes" type="text"  class="form-control" name="chromosomes" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hormones" class="col-md-3 col-form-label text-md-right">Hormones</label>
                            <div class="col-md-9">
                                <input id="hormones" type="text" class="form-control" name="hormones" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gonads" class="col-md-3 col-form-label text-md-right">Gonads</label>
                            <div class="col-md-9">
                                <input id="gonads" type="text" class="form-control" name="gonads" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="external" class="col-md-3 col-form-label text-md-right">External</label>
                            <div class="col-md-9">
                                <input id="external" type="text" class="form-control" name="external" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="internal" class="col-md-3 col-form-label text-md-right">Internal</label>
                            <div class="col-md-9">
                                <input id="internal" type="text" class="form-control" name="internal" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row mb-5 mt-5">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-secondary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTerm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    EDIT TERM
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{url($path.'/backend/biological')}}" method="POST" id="edit_Term" role="form" onsubmit="return editTerm()" aria-label="edit_Term">
                        <input type="hidden" name="eid" id="eid" value=''/>
                        @csrf
                        <div class="form-group row">
                            <label for="eterm" class="col-md-3 col-form-label text-md-right">Term</label>
                            <div class="col-md-9">
                                <input id="eterm" type="text" class="form-control" name="eterm" value="{{$b->term}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="echromosomes" class="col-md-3 col-form-label text-md-right">Chromosomes</label>
                            <div class="col-md-9">
                                <input id="echromosomes" type="text" class="form-control" name="echromosomes" value="{{$b->chromosomes}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ehormones" class="col-md-3 col-form-label text-md-right">Hormones</label>
                            <div class="col-md-9">
                                <input id="ehormones" type="text" class="form-control" name="ehormones" value="{{$b->hormones}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="egonads" class="col-md-3 col-form-label text-md-right">Gonads</label>
                            <div class="col-md-9">
                                <input id="egonads" type="text" class="form-control" name="egonads" value="{{$b->gonads}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="eexternal" class="col-md-3 col-form-label text-md-right">External</label>
                            <div class="col-md-9">
                                <input id="eexternal" type="text" class="form-control" name="eexternal" value="{{$b->external}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="einternal" class="col-md-3 col-form-label text-md-right">Internal</label>
                            <div class="col-md-9">
                                <input id="einternal" type="text" class="form-control" name="einternal" value="{{$b->internal}}">
                            </div>
                        </div>
                        <div class="form-group row mb-5 mt-5">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-secondary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type="checkbox"] {
            line-height: normal;
            margin: 0;
        }
        .modal-header {
            background-color:#f7f7f7;
        }
    </style>

    <!-- Bootstrap Table -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        //TABLE
        var t = $('#bioTable').DataTable({
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            pagingType: 'full_numbers'
        });
        var currentPage = 0;
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        if(urlParams.has('page')){
            currentPage = urlParams.get('page') * 1; //force number
        }
        $('document').ready(function(){
            t.page(currentPage).draw(false);
        })
        $("#bioTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
        //ADD TERM
        function addTerm(){
            let _url = $("#add_term").attr('action');
            fd = $("#add_term").serialize();
            $.ajax({
                url: _url,
                type: 'post',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Term added!");
                        location.reload();
                        $("#addTerm").modal('hide');
                    }
                },
                fail: function(){ alert("Error"); }
            });
            return false;
        };

        //EDIT TERM
        function editTerm(){
            let _url = $("#edit_Term").attr('action');
            fd = $("#edit_Term").serialize();
            $.ajax({
                url: _url,
                type: 'patch',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Term updated!");
                        location.reload();
                        $("#editTerm").modal('hide');
                    }
                },
                fail: function(){ alert("Error"); }
            });
            return false;
        };
        $('.term-edit').on('click', function(e){
            e.preventDefault();
            _url = $(this).attr('href');
            $.ajax({
                url: _url,
                type: 'get',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        $("#eid").val(response.biological.id);
                        $("#eterm").val(response.biological.term);
                        $("#echromosomes").val(response.biological.chromosomes);
                        $("#ehormones").val(response.biological.hormones);
                        $("#egonads").val(response.biological.gonads);
                        $("#eexternal").val(response.biological.external);
                        $("#einternal").val(response.biological.internal);
                        $("#editTerm").modal('show');
                    }
                },
                fail: function(){ alert("Error"); }
            });
        });
        //DELETE TERM
        $('body').on('click', '.term-delete', function(e){
            e.preventDefault();
            if(!confirm(`Are you sure you want to delete this Term?`)){
                return false;
            }
            let _id = $(this).attr('rel');
            _url = '{{ url($path.'/backend/biological/') }}/'+_id;
            $.ajax({
                url: _url,
                type: 'delete',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert('Term deleted');
                        location.reload();
                    }
                },
                fail: function(){ alert("Error"); }
            });
        });
        //Moveable Modals
        $("#addTerm").draggable({
            handle: ".modal-header"
        });
        $("#editTerm").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection





