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
            <div class="col">
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
                                    <th id="term" colspan="1" style="text-alignleft">Term</th>
                                    <th id="chromosomes" colspan="1" style="text-alignleft">Chromosomes</th>
                                    <th id="hormones" colspan="1" style="text-align:left">Hormones</th>
                                    <th id="gonads" colspan="1" style="text-align:left">Gonads</th>
                                    <th id="external" colspan="1" style="text-align:left">External</th>
                                    <th id="internal" colspan="1" style="text-align:left">Internal</th>
                                    <th id="options" colspan="1">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Biology::orderBy('term', 'ASC')->get() as $b)
                                <tr id="row_{{$b->id}}">
                                    <td colspan="1">{{$b->term}} </td>
                                    <td colspan="1">{{ $b->chromosomes }}</td>
                                    <td colspan="1">{{ $b->hormones }}</td>
                                    <td colspan="1">{{ $b->gonads }}</td>
                                    <td colspan="1">{{ $b->external }}</td>
                                    <td colspan="1">{{ $b->internal }}</td>
                                    <td colspan="1" style="text-align:center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-success edit-link" rel="{{$b->id}}" href="{{ url($path.'/backend/biologies/'.$b->id.'/edit')}}">Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger delete-link" href="{{ url($path.'/backend/biologies/'.$b->id) }}">Delete</a>
                                            </div>
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

    <!-- ADD PRODUCT MODAL CALL -->
    <div class="modal fade" id="addTerm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD TERM
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/biologies/create')
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="biologyEdit" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4>EDIT BIOLOGICAL SEX ENTRIES</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body biology-edit">
                    <form method="POST" id="bio-update">
                        <div class="form-group row">
                            <label for="term" class="col-md-3 col-form-label text-md-right">Term</label>
                            <div class="col-md-9">
                                <input id="term" type="text" class="form-control term" name="term" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="chromosomes" class="col-md-3 col-form-label text-md-right">Chromosomes</label>
                            <div class="col-md-9">
                                <input id="chromosomes" type="text" class="form-control chromosomes" name="chromosomes" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hormones" class="col-md-3 col-form-label text-md-right">Hormones</label>
                            <div class="col-md-9">
                                <input id="hormones" type="text" class="form-control hormones" name="hormones" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gonads" class="col-md-3 col-form-label text-md-right">Gonads</label>
                            <div class="col-md-9">
                                <input id="gonads" type="text" class="form-control gonads" name="gonads" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="external" class="col-md-3 col-form-label text-md-right">External</label>
                            <div class="col-md-9">
                                <input id="external" type="text" class="form-control external" name="external" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="internal" class="col-md-3 col-form-label text-md-right">Internal</label>
                            <div class="col-md-9">
                                <input id="internal" type="text" class="form-control internal" name="internal" >
                            </div>
                        </div>
                        <div class="form-group row mb-5 mt-5">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary save-biology">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
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
        //DELETE TERM
        $("body").on("click", '.delete-link', function(e){
            e.preventDefault();
            if(!confirm(`Are you sure you'd like to delete this item?`)){
                return false;
            }
            let _url = $(this).attr('href');
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
        })
        //EDIT TERM
        function editTerm(){
            let _url = $("#edit_term").attr('action');
            fd = $("#edit_term").serialize();
            $.ajax({
                url: _url,
                type: 'patch',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("term updated!");
                        location.reload();
                        $("#editterm").modal('hide');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
            return false;
        };
        $('.edit-term').on('click', function(e){
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
                        $("#eid").val(response.term.id);
                        $("#ecode").val(response.term.code);
                        $("#evalue").val(response.term.value);
                        $("#term_type").val(response.term.term_type);
                        $("#eavailable").val(response.term.available);
                        $("#eexpire_date").val(response.term.expire_date);
                        $("#editterm").modal('show');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
        });
        //Moveable Modals
        $("#addTerm").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection


