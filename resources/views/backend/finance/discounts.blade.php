@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- Bootstrap Date-Picker Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/backend') }}"> Backend</a> |
                <span style="font-weight: bold;color:#9acd57">Discounts</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row mt-2 mb-5">
            <div class="col-auto">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-10">DISCOUNTS</div>
                            <div class="col-md-2 text-right">
                                @if(auth()->user()->can('access-master') || auth()->user()->can('modify-discounts'))
                                <a class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addDiscount">ADD</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="dicountsTable">
                            <thead>
                                <tr>
                                    <th id="bulk">Bulk</th>
                                    <th id="code" style="text-align:center">Code</th>
                                    <th id="options" style="text-align:center">Status</th>
                                    <th id="value" style="text-align:center">Value</th>
                                    <th id="status" style="text-align:center">Available</th>
                                    <th id="status" style="text-align:center">Used</th>
                                    <th id="status" style="text-align:center">Expires</th>
                                    <th id="options" style="text-align:center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Discounts::all() as $d)
                                <tr id="row_{{$d->id}}">
                                    <td style="text-align:center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="bulkSelect"/>
                                        </div>
                                    </td>
                                    <td>{{$d->code}}</td>
                                    <td style="text-align:center">@if($d->status == \App\Models\Discounts::DISCOUNT_AVAILABLE) available @elseif($d->status == \App\Models\Discounts::DISCOUNT_APPLIED) used @else expired @endif </td>
                                    <td style="text-align:right">@if($d->discount_type == \App\Models\Discounts::TYPE_FIXED) $ {{number_format($d->value, 2)}} @else {{$d->value}}% @endif</td>
                                    <td style="text-align:center">{{$d->available}} </td>
                                    <td style="text-align:center">{{$d->used}} </td>
                                    <td colspan="1" style="text-align:center">
                                        @isset($d->expire_date)
                                            {{ date('m-d-Y', strtotime($d->expire_date)) }}
                                        @endisset
                                        @empty($d->expire_date)
                                            n/a
                                        @endempty
                                    <td style="text-align:center">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm dropdown-toggle text-dark" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-success edit-discount" rel="{{$d->id}}" href="{{url($path.'/backend/discounts/'.$d->id)}}" >Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger discount-delete" rel="{{$d->id}}" href="{{url($path.'/backend/discounts/'.$d->id)}}">Delete</a>
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

    <!-- ADD DISCOUNT MODAL -->
    <div class="modal fade" id="addDiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD DISCOUNT CODE
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{url($path.'/backend/discounts')}}" method="POST" id="add_discount" role="form" onsubmit="return addDiscount()" aria-label="add_discount">
                        @csrf
                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">Code</label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="value" class="col-md-4 col-form-label text-md-right">Value</label>
                            <div class="col-md-6">
                                <input id="value" type="text" class="form-control" name="value" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="discount_type" class="col-md-4 col-form-label text-md-right">Type</label>
                            <div class="col-md-6">
                                <select class="form-control" id="discount_type" name="discount_type">
                                    <option value="0">Fixed Value ($)</option>
                                    <option value="1">Percent (%)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expire_date" class="col-md-4 col-form-label text-md-right control-label">Expires</label>
                            <div class="col-md-6">
                                <input class="form-control" id="expire_date" name="expire_date" placeholder="MM/DD/YYY" type="text"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="available" class="col-md-4 col-form-label text-md-right">Available</label>
                            <div class="col-md-6">
                                <input id="available" type="text" class="form-control" name="available" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="restricted" class="col-md-4 col-form-label text-md-right">Restricted</label>
                            <div class="col-md-6">
                                <select class="form-control restricted-selection" name="restricted">
                                    <option value="0">Unrestricted</option>
                                    <option value="1">For selected products:</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-row products-selection" style="display:none;">
                            <label class="col-md-4 col-form-label text-md-right">Products:</label>
                            <div class="col-md-6">
                            @foreach($products as $product)
                                <input type="checkbox" name="products[]" value="{{$product->id}}"/>
                                {{$product->name}}
                                <br/>
                            @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto mx-auto">
                                <button type="submit" class="btn btn-secondary">Save Discount</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDiscount" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    EDIT DISCOUNT CODE
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{url($path.'/backend/discounts')}}" method="POST" id="edit_discount" role="form" onsubmit="return editDiscount()" aria-label="edit_discount">
                        <input type="hidden" name="id" id="eid" value="{{$d->id}}" />
                        @csrf
                        <div class="form-group row">
                            <label for="ecode" class="col-md-4 col-form-label text-md-right">Code</label>
                            <div class="col-md-6">
                                <input id="ecode" type="text" class="form-control" name="code" value="{{ $d->code }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="evalue" class="col-md-4 col-form-label text-md-right">Value</label>
                            <div class="col-md-6">
                                <input id="evalue" type="text" class="form-control" name="value" value="{{ $d->value }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ediscount_type" class="col-md-4 col-form-label text-md-right">Type</label>
                            <div class="col-md-6">
                                <select class="form-control" id="ediscount_type" name="discount_type">
                                    <option value="0">Fixed Value ($)</option>
                                    <option value="1">Percent (%)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expire_date" class="col-md-4 col-form-label text-md-right control-label">Expires</label>
                            <div class="col-md-6">
                                <input class="form-control" id="expire_date" name="expire_date" placeholder="MM/DD/YYY" type="text" value="{{ $d->expire_date }}"/>
                        </div>
                        </div>
                        <div class="form-group row">
                            <label for="eavailable" class="col-md-4 col-form-label text-md-right">Available</label>
                            <div class="col-md-6">
                                <input id="eavailable" type="text" class="form-control" name="available" value="{{ $d->available }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="erestricted" class="col-md-4 col-form-label text-md-right">Restricted</label>
                            <div class="col-md-6">
                                <select class="form-control erestricted-selection" name="restricted" id="erestricted">
                                    <option value="0">Unrestricted</option>
                                    <option value="1">For selected products:</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-row eproducts-selection" style="display:none;">
                            <label class="col-md-4 col-form-label text-md-right">Products:</label>
                            <div class="col-md-6">
                                @foreach($products as $product)
                                    <input type="checkbox" name="products[]" class="p-check product-{{$product->id}}" value="{{$product->id}}"/>
                                    {{$product->name}}
                                    <br/>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-auto mx-auto">
                                <button type="submit" class="btn btn-secondary">SAVE</button>
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

    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap Table -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        //TABLE
        var t = $('#dicountsTable').DataTable({
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
        $("#dicountsTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
        //Add Discounts
        function addDiscount(){
            let _url = $("#add_discount").attr('action');
            fd = $("#add_discount").serialize();
            $.ajax({
                url: _url,
                type: 'post',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Discount added!");
                        location.reload();
                        $("#addPackage").modal('hide');
                    }
                },
                fail: function(){ alert("Error"); }
            });
            return false;
        };
        //Edit Discount
        function editDiscount(){
            let _url = $("#edit_discount").attr('action');
            fd = $("#edit_discount").serialize();
            $.ajax({
                url: _url,
                type: 'patch',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Discount updated!");
                        location.reload();
                        $("#editDiscount").modal('hide');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
            return false;
        };

        function fillProducts(products){
            $('.p-check').each(function (){
                $(this).attr('checked', products.includes(parseInt($(this).val())))
            });
        }

        $('body').on('click', '.edit-discount', function(e){
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
                        $("#eid").val(response.discount.id);
                        $("#ecode").val(response.discount.code);
                        $("#evalue").val(response.discount.value);
                        $("#ediscount_type").val(response.discount.discount_type);
                        $("#eavailable").val(response.discount.available);
                        $("#eexpire_date").val(response.discount.expire_date);
                        $("#erestricted").val(response.discount.restricted);
                        __display = response.discount.restricted == 0? 'none' : 'flex';
                        $('.eproducts-selection').css({'display': __display});

                        __products = [];
                        if(response.discount.restricted == 1){
                            for(p of response.restricted){
                                __products.push(parseInt(p.product_id));
                            }
                        }
                        fillProducts(__products);

                        $("#editDiscount").modal('show');
                    }
                },
                fail: function(){ alert("Error"); }
            });
        });
        //DELETE DISCOUNT
        $('body').on('click', '.discount-delete', function(e){
            e.preventDefault();
            if(!confirm(`Are you sure you want to delete this discount?`)){
                return false;
            }
            let _id = $(this).attr('rel');
            _url = '{{ url($path.'/backend/discounts/') }}/'+_id;
            $.ajax({
                url: _url,
                type: 'delete',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert('Discount deleted');
                        location.reload();
                    }
                },
                fail: function(){ alert("Error"); }
            });
        });
        //
        $('body').on('change', '.restricted-selection', function(e){
            e.preventDefault();
            __display = $(this).val() == 0? 'none' : 'flex';
            $('.products-selection').css({'display': __display});
        })
        //
        $('body').on('change', '.erestricted-selection', function(e){
            e.preventDefault();
            __display = $(this).val() == 0? 'none' : 'flex';
            $('.eproducts-selection').css({'display': __display});
        })

        //Date-Picker Plugin
        $(document).ready(function(){
            var date_input=$('input[name="expire_date"]');
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            var options={
                format: 'mm/dd/yyyy',
                container: container,
                todayHighlight: true,
                autoclose: true,
            };
            date_input.datepicker(options);
        })
        //Moveable Modals
        $("#addDiscount").draggable({
            handle: ".modal-header"
        });
        $("#editDiscount").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection
