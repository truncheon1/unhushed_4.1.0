@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Backend</a> |
                <a href="{{ url($path.'/backend/products') }}">Products</a> |
                <span style="font-weight: bold;color:#9acd57">Curriculum Licence Pricing</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row mt-2 mb-5">
            <div class="col-12">
                <div class="card" style="width: 50rem">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-10">
                                <h4>{{$package->name}}</h4>
                            </div>
                            <div class="col-md-2 text-right">
                                @if(auth()->user()->can('access-master') || auth()->user()->can('modify-products'))
                                <button name="add" id="add" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addPackage">
                                    ADD
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" style="width: 100%" id="packagesTable">
                            <thead>
                                <tr>
                                    <th id="name" colspan="1" style="text-align:left;">Caption</th>
                                    <th>Min.</th>
                                    <th>Max.</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                    <th>Yearly Fee</th>
                                    <th id="options" colspan="1">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($package->options() as $option)
                                <tr id="row_{{$option->id}}">
                                    <td>{{$option->package_caption}} </td>
                                    <td>{{$option->min_users}}</td>
                                    <td>{{$option->max_users}}</td>
                                    <td>{{$option->standard_price}}</td>
                                    <td>{{$option->discount_price}}</td>
                                    <td>{{$option->recurring_price}}</td>
                                    <td colspan="1" style="text-align:center;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-success package-edit-link" rel="{{$option->id}}" href="#">Edit</a>
                                                <a class="dropdown-item text-danger package-delete" rel="{{$option->id}}" href="#">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">&nbsp;</div>
        </div>

        <div class="modal fade" id="editPackage" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4>Edit Package Option</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" style="text-align: center">
                                Warning. Verify min and max users for each option do not overlap or leave out numbers.<br>
                            </div>
                        </div>
                        <form action="{{ url($path.'/backend/packages/'.$package->id.'/options') }}" method="POST" id="edit_package" role="form" onsubmit="return editPackage()" aria-label="edit_package">
                            <input type="hidden" name="id" id="eid" value="" />
                            @csrf
                            <div class="form-group row">
                                <label for="ecaption" class="col-md-4 col-form-label text-md-right">Caption</label>
                                <div class="col-md-6">
                                    <input id="ecaption" type="text" class="form-control" name="caption" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emin_users" class="col-md-4 col-form-label text-md-right">Min. Users</label>
                                <div class="col-md-6">
                                    <input id="emin_users" type="text" class="form-control" name="min_users" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emax_users" class="col-md-4 col-form-label text-md-right">Max. Users</label>
                                <div class="col-md-6">
                                    <input id="emax_users" type="text" class="form-control" name="max_users" value="1264 max value possible">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="estandard" class="col-md-4 col-form-label text-md-right">Standard Price</label>
                                <div class="col-md-6">
                                    <input id="estandard" type="text" class="form-control" name="standard" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ediscount" class="col-md-4 col-form-label text-md-right">Discount Price</label>
                                <div class="col-md-6">
                                    <input id="ediscount" type="text" class="form-control" name="discount" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="erecurring" class="col-md-4 col-form-label text-md-right">Yearly Fee</label>
                                <div class="col-md-6">
                                    <input id="erecurring" type="text" class="form-control" name="recurring" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary">Update Package Option</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD PACKAGE OPTIONS
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{ url($path.'/backend/packages/'.$package->id.'/options') }}" method="POST" id="add_package" role="form" onsubmit="return addPackage()" aria-label="add_package">
                        @csrf
                        <div class="form-group row">
                            <label for="caption" class="col-md-4 col-form-label text-md-right">Caption</label>
                            <div class="col-md-6">
                                <input id="caption" type="text" class="form-control" name="caption" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="min_users" class="col-md-4 col-form-label text-md-right">Min. Users</label>
                            <div class="col-md-6">
                                <input id="min_users" type="text" class="form-control" name="min_users" value="{{$package->min_users()}}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="max_users" class="col-md-4 col-form-label text-md-right">Max. Users</label>
                            <div class="col-md-6">
                                <input id="max_users" type="text" class="form-control" name="max_users" value="1264 max value possible">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="standard" class="col-md-4 col-form-label text-md-right">Standard Price</label>
                            <div class="col-md-6">
                                <input id="standard" type="text" class="form-control" name="standard" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="discount" class="col-md-4 col-form-label text-md-right">Discount Price</label>
                            <div class="col-md-6">
                                <input id="discount" type="text" class="form-control" name="discount" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="recurring" class="col-md-4 col-form-label text-md-right">Yearly Fee</label>
                            <div class="col-md-6">
                                <input id="recurring" type="text" class="form-control" name="recurring" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-auto mx-auto">
                                <button type="submit" class="btn btn-secondary">Add Package Option</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-header {
            background-color:#f7f7f7;
        }
        .blue{
            background-color: #265a8e;
            color: white;
            font-size: small;
        }
    </style>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        var t = $('#packagesTable').DataTable({
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
        $("#packagesTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
        function addPackage(){
            let _url = $("#add_package").attr('action');
            fd = $("#add_package").serialize();
            $.ajax({
                url: _url,
                type: 'post',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Option added!");
                        location.reload();
                        $("#addPackage").modal('hide');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
            return false;
        }

        function editPackage(){
            let _url = $("#edit_package").attr('action');
            fd = $("#edit_package").serialize();
            $.ajax({
                url: _url,
                type: 'patch',
                data: fd,
            //    contentType: false,
            //    processData: false,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);

                    }else{
                        alert("Option Updated!");
                        location.reload();
                        $("#editPackage").modal('hide');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
            return false;
        }

        $('body').on('click', '.package-delete', function(e){
            e.preventDefault();
            if(!confirm('Are you sure you want to delete this option?')){
                return false;
            }
            let _id = $(this).attr('rel');
            _url = '{{ url($path.'/backend/packages/'.$package->id.'/options/') }}/'+_id;
            $.ajax({
                url: _url,
                type: 'delete',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert('Option deleted');
                        location.reload();
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
        });

        $(document).ready(function(){
            $('.package-edit-link').on('click', function(e){
                if(!confirm(`When editing an option, make sure there is continuity betweeen packages for min and max users. \n
                            The system can't check for that.`)){
                    return;
                }
                e.preventDefault();
                id = $(this).attr('rel');
            _url = '{{ url($path.'/backend/packages/'.$package->id.'/options') }}/'+id+'/view';
            console.log(_url);
            $.ajax({
                url: _url,
                type: 'get',
                success: function(response){
                    /** fill form **/
                    $("#eid").val(response.option.id);
                    $("#ecaption").val(response.option.package_caption);
                    $("#emin_users").val(response.option.min_users);
                    $("#emax_users").val(response.option.max_users);
                    $("#estandard").val(response.option.standard_price);
                    $("#ediscount").val(response.option.discount_price);
                    $("#erecurring").val(response.option.recurring_price);
                    $("#editPackage").modal('show');
                },
                fail: function(){
                    alert("Error");
                }
            });
            })
        })
        //Moveable Modals
        $("#addPackage").draggable({
            handle: ".modal-header"
        });
        $("#editPackage").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection





