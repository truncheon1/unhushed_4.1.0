@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/backend') }}">Backend</a> |
                <a href="{{ url($path.'/backend/effective') }}">Effectiveness Data</a> |
                Parents
            </div>
        </div>

            <!-- TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-7">EFFECTIVENESS DATA - PARENTS</div>
                                <div class="col text-right">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                                        <a href="{{url('effective/parents/add')}}"><button class="btn btn-secondary btn-sm">ADD PARENT</button></a>
                                    @endif
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                                        | <button name="family" id="family" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addFamily">ADD FAMILY</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7 mx-auto">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <label class="input-group-text" for="view-parents">Families</label>
                                        </div>
                                        <select class="form-control view-parents">
                                            <option value="0">UNASSIGNED</option>
                                            @foreach($families as $family)
                                                <option value="{{$family->id}}" @if($family->id == $fid) selected @endif>{{$family->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-4">
                                    <table class="table table-striped" style="width: 100%" id="parentTable">
                                        <thead>
                                            <tr>
                                                <th id="family" colspan="1" style="text-align:center">Family</th>
                                                <th id="name" colspan="2">Parent Name</th>
                                                <th id="email" colspan="1">Email</th>
                                                <th id="phone" colspan="1">Phone</th>
                                                <th id="address" colspan="4">Address</th>
                                                <th id="options" colspan="1" style="text-align:center">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parents as $parent)
                                            <tr id="row_{{$parent->id}}">
                                                <td colspan="1" style="text-align:center">{{$fid}} </td>
                                                <td colspan="1">{{$parent->fname}} </td>
                                                <td colspan="1">{{$parent->lname}} </td>
                                                <td colspan="1">{{$parent->email}} </td>
                                                <td colspan="1">{{$parent->phone}} </td>
                                                <td colspan="1">{{$parent->street}} </td>
                                                <td colspan="1">{{$parent->city}} </td>
                                                <td colspan="1">{{$parent->state}} </td>
                                                <td colspan="1">{{$parent->zip}} </td>
                                                <td colspan="1" style="text-align:center">
                                                    <div class="btn-group">
                                                        <a rel="{{$parent->id}}" href="{{ url('effective/parents/'.$parent->id.'/edit') }}">
                                                            <button type="submit" class="btn btn-outline-secondary btn-sm mr-2">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </a>
                                                        <a class="parent-delete" rel="{{$parent->id}}" href="#">
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                                <!--
                                                <td colspan="1" style="text-align:center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                        <a class="dropdown-item text-success parent-interview-link" rel="{{$parent->id}}" href="#">Interview</a>
                                                        <a class="dropdown-item text-success parent-edit-link" rel="{{$parent->id}}" href="#">Edit</a>
                                                        <a class="dropdown-item text-danger parent-delete-link" rel="{{$parent->id}}" href="#">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                -->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="10"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-1">&nbsp;</div>
            </div>
        </div>
        <!-- ADD FAMILY -->
        <div class="modal fade" id="addFamily" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4>Add Family</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{url('effective/add_family')}}" method="POST" id="add_family" role="form" onsubmit="return addFamily()">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-md-4 col-form-label text-md-right">Notes</label>
                                <div class="col-md-6">
                                    <input id="notes" type="text" class="form-control" name="notes" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary">ADD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            var currentPage = 0;
            const queryString = window.location.search;

            const urlParams = new URLSearchParams(queryString);
            if(urlParams.has('page')){
                currentPage = urlParams.get('page') * 1; //force number
            }

            $('body').on('change', '.view-parents', function(e){
                console.log('CHANGED');
                e.preventDefault();
                document.location = '{{url("effective/parents/?family_id=")}}'+$(this).val();
            });

            var t = $('#parentTable').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pagingType: 'full_numbers'
            });

            $('document').ready(function(){
                    t.page(currentPage).draw(false);
            })
            $("#parentTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });

            function addFamily(){
                let _url = $("#add_family").attr('action');
                fd = $("#add_family").serialize();
                $.ajax({
                    url: _url,
                    type: 'post',
                    data: fd,
                    success: function(response){
                        console.log(response);
                        if(response.error === true){
                            alert(response.message);
                        }else{
                            alert("Family added.");
                            location.reload();
                            $("#add_family").modal('hide');
                        }
                    },
                    fail: function(){
                        alert("Error");
                    }
                });
                return false;
            }
        </script>
    </section>
@endsection
