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
                <a href="{{ url($path.'/backend') }}"> Backend</a> |
                <span style="font-weight: bold;color:#9acd57"> Fulfillment</span>
            </div>
        </div>

        <!-- BILLING TABLE -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5" style="text-transform: uppercase; font-weight: bold;"> ORDER FULFILLMENT</div>
                            <div class="col text-right">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table" id="ordersTable" class="table table-borderless" style="font-size: 11px">
                        <thead>
                            <th class="d-none d-lg-table-cell" colspan="1" style="text-align:center">   Order#</th>
                            <th class="d-none d-lg-table-cell" colspan="1" style="text-align:center">   Customer</th>
                            <th class="d-none d-lg-table-cell" colspan="1">                             Organization</th>
                            <th                                colspan="1">                             Ship To</th>
                            <th                                colspan="1" style="text-align:center">
                                <table class="table m-0 table-borderless">
                                    <tbody>
                                        <td style="text-align:left">Item</td>
                                        <td style="text-align:right">Qty</td>
                                        <td class="d-none d-lg-table-cell"style="text-align:right">Price</td>
                                    </tbody>
                                </table>
                            </th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:right">   Tax</th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:right">   Shipping</th>
                            <th class="d-none d-lg-table-cell"  colspan="1">                            Total</th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:center">  Ordered</th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:center">  Status</th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:center">  Shipped</th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:center">  Tracking</th>
                            <th class="d-none d-lg-table-cell"  colspan="1" style="text-align:center">  Edit</th>
                        </thead>
                        <tbody>
                            @foreach($details as $detail)
                            <tr id="row_{{$detail['order']->id}}">
                                <td class="d-none d-lg-table-cell"   style="text-align:center">{{$detail['order']->id}}</td>
                                <td class="d-none d-lg-table-cell"  >{{$detail['by']}} <br/>id: {{$detail['id']}}</td>
                                <td class="d-none d-lg-table-cell"  >{{$detail['org']}}</td>
                                <td style="text-align:left">
                                    {{$detail['name']}}
                                    <br>{{$detail['address']}}
                                    <br>{{$detail['city']}} {{$detail['state']}} {{$detail['zip']}}
                                </td>
                                <td>
                                    @foreach($detail['items'] as $item)
                                    <table class="table m-0 table-borderless">
                                        <tbody>
                                            <tr>
                                                <td style="text-align:left">
                                                {{$item['name']}}
                                                </td>
                                                <td style="text-align:right">
                                                {{$item['qty']}}
                                                </td>
                                                <td class="d-none d-lg-table-cell" style="text-align:right">
                                                ${{$item['cost']}}
                                                </td>
                                            </tr>
                                        </tb>
                                    </table>
                                    @endforeach
                                </td>
                                <td class="d-none d-lg-table-cell">@if(empty($detail['tax']))@else $ {{$detail['tax']}}@endif</td>
                                <td class="d-none d-lg-table-cell">@if(empty($detail['shipping']))@else $ {{$detail['shipping']}}@endif</td>
                                <td class="d-none d-lg-table-cell">$ {{$detail['total']}}</td>
                                <td class="d-none d-lg-table-cell" style="text-align:center" class="align-middle">{{ date('m/d/Y', strtotime($detail['order']->created_at)) }}</td>
                                <td class="d-none d-lg-table-cell">{{$detail['status']}}</td>
                                <td class="d-none d-lg-table-cell" style="text-align:center">
                                    @if(empty($detail['shipped'])) - @else {{$detail['shipped']}} @endif
                                </td>
                                <td class="d-none d-lg-table-cell" style="text-align:center">
                                    {{$detail['tracking']}}
                                </td>
                                <td class="d-none d-lg-table-cell" style="text-align:center">
                                    <a rel="{{$detail['order']->id}}" rel-status="{{$detail['status']}}" rel-shipped="{{ date('Y-m-d', strtotime($detail['shipped'])) }}" rel-tracking="{{$detail['tracking']}}">
                                        <button type="button" class="btn btn-primary btn-sm edit-order" title="Edit Order">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td colspan="13"></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    UDPATE ORDER
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{ url($path.'/backend/fulfillment') }}" method="POST" id="update" role="form" aria-label="update">
                        <input type="hidden" name="cart_id" id="cart_id" value="0"/>
                        @csrf
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control">
                                    <option value="0" >ORDERED</option>
                                    <option value="1">SENT</option>
                                    <option value="2">CANCELLED</option>
                                    <option value="3">BACKORDER</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shipped" class="col-md-4 col-form-label text-md-right">Shipped</label>
                            <div class="col-md-6">
                                <input id="shipped" type="text" class="form-control shipped" name="shipped" placeholder="MM/DD/YYY">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tracking" class="col-md-4 col-form-label text-md-right">Tracking</label>
                            <div class="col-md-6">
                                <input id="tracking" type="text" class="form-control" name="tracking">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-auto mx-auto">
                                <button type="submit" class="btn btn-secondary">UPDATE</button>
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
    <script type="text/javascript">
        var t = $('#ordersTable').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pagingType: 'full_numbers',
            order: [[ 0, "desc" ]],
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
        $("#ordersTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });

        //Moveable Modals
        $("#update").draggable({
            handle: ".modal-header"
        });
        //
        function get_status(status){
        s = "0";
        if(status == 'SENT')
            s = "1";
        if(status == 'CANCELED')
            s = 2;
        if(status == 'BACKORDER')
            s = 3;
        return s;
    }
        //Edit order status
        $('body').on('click', '.edit-order', function(){
            $("#cart_id").val($(this).parent().attr('rel'));
            $("#status").val(get_status($(this).parent().attr('rel-status')));
            $("#shipped").val($(this).parent().attr('rel-shipped'));
            $("#tracking").val($(this).parent().attr('rel-tracking'));
            $("#update").modal('show');
            $(".shipped").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                defaultDate: [$(this).parent().attr('rel-shipped')],      
            });
        })
        $(".shipped").flatpickr({ 
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            defaultDate: ["{{ date('Y-m-d', strtotime($detail['shipped'])) }}"],      
        });

    </script>
</section>
@endsection
