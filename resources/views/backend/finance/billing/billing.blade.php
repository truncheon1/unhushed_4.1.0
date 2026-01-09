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
                <span style="font-weight: bold;color:#9acd57">Billing</span>
            </div>
        </div>

        <!-- BILLING TABLE -->
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5" style="text-transform: uppercase; font-weight: bold;"> BILLING HISTORY</div>
                            <div class="col text-right">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table" id="billingTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">Order #</th>
                                        <th style="text-align:center">Type</th>
                                        <th style="text-align:center">Payment</th>
                                        <th style="text-align:right">Total</th>
                                        <th style="text-align:center">Order Date</th>
                                        <th style="text-align:center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $detail)
                                        <tr id="row_{{$detail['order']->id}}">
                                            <td style="text-align:center" class="align-middle">
                                                @php
                                                    $prefix = (in_array($detail['table'], ['carts', 'purchases'])) ? 'Inv-' : 'S-';
                                                @endphp
                                                {{$prefix}}{{$detail['id']}}
                                            </td>
                                            <td style="text-align:center" class="align-middle">
                                                @if(strtolower($detail['type']) == 'subscription')
                                                    <a href="{{ url($path.'/dashboard/my-subscriptions') }}" class="text-secondary">
                                                        {{ucfirst($detail['type'])}}</a>
                                                @elseif(strtolower($detail['type']) == 'products' && !empty($detail['items']))
                                                    <a href="#" class="text-secondary" style="cursor:pointer;" data-order-id="{{$detail['id']}}" data-items='{{json_encode($detail['items'])}}' data-bs-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#itemInfo">{{ucfirst($detail['type'])}}</a>
                                                @else
                                                    {{$detail['type']}}
                                                @endif
                                            </td>
                                            <td style="text-align:center" class="align-middle">
                                                @if(isset($detail['source']) && $detail['source'] == 'stripe')
                                                    <span class="badge bg-primary">Stripe</span>
                                                @else
                                                    <span class="badge bg-secondary">PayPal</span>
                                                @endif
                                            </td>
                                            <td style="text-align:right" class="align-middle">${{number_format($detail['total'], 2)}}</td>
                                            <td style="text-align:center" class="align-middle" data-order="{{strtotime($detail['date'])}}">{{date('m-d-Y', strtotime($detail['date']))}}</td>
                                            <td style="text-align:center" class="align-middle text-uppercase">
                                                @if($detail['type'] == 'Donation')
                                                    <span class="badge bg-success">Thank you!</span>
                                                @elseif(isset($detail['fulfillments']) && is_array($detail['fulfillments']))
                                                    @foreach($detail['fulfillments'] as $index => $fulfillment)
                                                        @php
                                                            $status = strtoupper($fulfillment['status']);
                                                        @endphp
                                                        @if($status === 'DIGITAL')
                                                            <span class="badge bg-info">Digital</span>
                                                        @elseif($status === 'SENT' || $status === 'SHIPPED' || $status === 'DELIVERED')
                                                            <a class="badge bg-success text-decoration-none" style="cursor:pointer;" 
                                                                data-order-id="{{$detail['id']}}" 
                                                                data-shipping="1" 
                                                                data-order-obj='{{json_encode($fulfillment)}}' 
                                                                data-bs-toggle="modal" 
                                                                data-bs-backdrop="static" 
                                                                data-bs-keyboard="false" 
                                                                data-bs-target="#shipInfo">{{$fulfillment['status']}}</a>
                                                        @elseif($status === 'ORDERED' || $status === 'PENDING')
                                                            <span class="badge bg-info">{{$fulfillment['status']}}</span>
                                                        @elseif($status === 'CANCELED' || $status === 'CANCELLED')
                                                            <span class="badge bg-pink">{{$fulfillment['status']}}</span>
                                                        @elseif($status === 'BACK ORDERED')
                                                            <span class="badge bg-secondary">{{$fulfillment['status']}}</span>
                                                        @elseif($status === 'ACTIVE')
                                                            <span class="badge bg-success">{{$fulfillment['status']}}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{$fulfillment['status']}}</span>
                                                        @endif
                                                        @if(!$loop->last) @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMS INFO -->
        <div class="modal fade" id="itemInfo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <span>ITEMS INFO (Order #<span id="itemInfoOrderId"></span>)</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body" id="itemInfoBody">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-spinner fa-spin"></i> Loading items...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SHIPPING INFO -->
        <div class="modal fade" id="shipInfo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <span>SHIPPING INFO (Order <span id="shipInfoOrderId"></span>)</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body" id="shipInfoBody">
                        <!-- Shipping info will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css" media="all">
        .modal-header {
            background-color:#f7f7f7;
        }
        /* Prevent extra padding on pagination */
        .card-body {
            padding: 1.25rem !important;
        }
        /* Ensure table stays within container and is responsive */
        #billingTable_wrapper,
        .card,
        .row,
        .col,
        .containerLMS {
            max-width: 100% !important;
        }
        #billingTable {
            width: 100% !important;
            table-layout: fixed;
        }
        #billingTable td, #billingTable th {
            word-wrap: break-word;
            white-space: nowrap;
        }
        /* Avoid unexpected horizontal overflow */
        .dataTables_wrapper {
            overflow-x: hidden;
        }
        /* Remove unexpected margin growth on redraw */
        #billingTable_wrapper .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        /* Multiple badges spacing */
        .badge + .badge {
            margin-left: 0.25rem;
        }
        /* Custom pink badge for canceled status */
        .bg-pink {
            background-color: #fb5050 !important;
            color: white;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            console.log('Initializing DataTable...');
            console.log('Table rows:', $('#billingTable tbody tr').length);
            
            var t = $('#billingTable').DataTable({
                lengthMenu: [[5, 25, 50, -1], [5, 25, 50, "All"]],
                pagingType: 'full_numbers',
                order: [[ 4, "desc" ]],
                pageLength: 5,
                paging: true,
                info: true,
                searching: true,
                autoWidth: false,
                drawCallback: function(){
                    var api = this.api();
                    // Constrain wrapper to viewport and adjust columns
                    $('#billingTable_wrapper').css({
                        width: '100%',
                        maxWidth: '100%',
                        overflowX: 'hidden'
                    });
                    api.columns.adjust();
                }
            });
            
            console.log('DataTable initialized:', t);
            console.log('Total records:', t.page.info().recordsTotal);
            console.log('Pagination element:', $('.dataTables_paginate').length);
            console.log('Info element:', $('.dataTables_info').length);
            
            var currentPage = 0;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if(urlParams.has('page')){
                currentPage = urlParams.get('page') * 1; //force number
                t.page(currentPage).draw(false);
            }
            
            $("#billingTable").on('page.dt', function(){
                var info = t.page.info();
                currentPage = info.page;
                console.log('Page changed to:', currentPage);
                urlParams.set('page', currentPage);
                history.pushState(null, null, "?"+urlParams.toString());
                // Adjust after pagination redraw
                t.columns.adjust();
            });

            // Adjust columns on viewport resize to prevent width growth
            $(window).on('resize', function(){
                t.columns.adjust();
            });
            
            // Handle itemInfo modal - populate with clicked row's data
            $('#itemInfo').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var orderId = button.attr('data-order-id'); // Use attr instead of data for JSON strings
                var itemsJson = button.attr('data-items');
                
                console.log('Items Info Modal Opened:', {
                    orderId: orderId,
                    itemsJson: itemsJson
                });
                
                $('#itemInfoOrderId').text(orderId || 'Unknown');
                
                var items = [];
                try {
                    items = JSON.parse(itemsJson || '[]');
                } catch(e) {
                    console.error('Failed to parse items JSON:', e);
                }
                
                console.log('Parsed items:', items);
                
                var itemsHtml = '<table class="table table-hover table-borderless">' +
                                '<thead><tr><th style="text-align:left">Item</th><th style="text-align:center">Qty</th></tr></thead>' +
                                '<tbody>';
                
                if (Array.isArray(items) && items.length > 0) {
                    items.forEach(function(item) {
                        var itemDescription = item.name || 'Unknown Product';
                        if (item.variant_name) {
                            itemDescription += '<br><small class="text-muted">' + item.variant_name + '</small>';
                        }
                        if (item.variant_info) {
                            itemDescription += '<br><small class="text-muted">' + item.variant_info + '</small>';
                        }
                        itemsHtml += '<tr><td style="text-align:left">' + itemDescription + '</td>' +
                                     '<td style="text-align:center">' + item.qty + '</td></tr>';
                    });
                } else {
                    itemsHtml += '<tr><td colspan="2" class="text-center text-muted">No items found</td></tr>';
                }
                
                itemsHtml += '</tbody></table>';
                $('#itemInfoBody').html(itemsHtml);
            });
            
            // Handle shipInfo modal - populate with clicked row's data
            $('#shipInfo').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var orderId = button.data('order-id');
                var orderObj = button.data('order-obj');
                var shipping = button.data('shipping');
                
                $('#shipInfoOrderId').text('#' + orderId);
                
                var shippedDate = '';
                if (orderObj.shipped_date) {
                    var date = new Date(orderObj.shipped_date);
                    shippedDate = (date.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                                  date.getDate().toString().padStart(2, '0') + '-' + date.getFullYear();
                } else {
                    shippedDate = '<span class="text-muted">Processing</span>';
                }
                
                var tracking = '';
                if (orderObj.tracking) {
                    if (/[a-z]/i.test(orderObj.tracking)) {
                        tracking = orderObj.tracking;
                    } else {
                        tracking = '<a href="https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=' + orderObj.tracking + '" target="_blank" rel="noopener noreferrer">' + orderObj.tracking + '</a>';
                    }
                } else {
                    tracking = '<span class="text-muted">Pending</span>';
                }
                
                // Check if this is a digital order (no shipping required)
                var address = '';
                if (!shipping || shipping === 0 || orderObj.type === 'digital') {
                    address = '<span class="badge bg-info">Digital</span>';
                } else {
                    address = orderObj.name + '<br/>' + orderObj.address1;
                    if (orderObj.address2) {
                        address += '<br/>' + orderObj.address2;
                    }
                    address += '<br/>' + orderObj.city + ', ' + orderObj.state + ' ' + orderObj.zip;
                }
                
                var shipHtml = '<table class="table table-hover table-borderless">' +
                               '<thead><tr><th style="text-align:center">Date Shipped</th><th style="text-align:center">Tracking Info</th><th style="text-align:left">Shipped To</th></tr></thead>' +
                               '<tbody><tr><td style="text-align:center">' + shippedDate + '</td>' +
                               '<td style="text-align:center" class="align-middle">' + tracking + '</td>' +
                               '<td style="text-align:left">' + address + '</td></tr></tbody>' +
                               '</table>';
                
                $('#shipInfoBody').html(shipHtml);
            });
        });

        //Moveable Modals
        $("#shipInfo").draggable({
            handle: ".modal-header"
        });
        $("#itemInfo").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection





