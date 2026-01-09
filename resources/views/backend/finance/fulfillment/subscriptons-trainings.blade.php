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
                <a href="{{ url($path.'/orders') }}"> Orders to Ship</a> |
                <a href="{{ url($path.'/renewals') }}"> Renewals</a> |
                <span style="font-weight: bold;color:#9acd57"> Subscriptions/Trainings</span>
            </div>
        </div>

        <!-- TABLE -->
        <div class="row">
            <div class="col-auto">
                <table id="stTable" class="table table-striped table-borderless" style="font-size: 10px">
                    <thead>
                        <tr>
                            <th id="order"    colspan="1" style="text-align:center">Order#</th>
                            <th id="user"       colspan="1" style="text-align:left">Customer</th>
                            <th id="org"        colspan="1" style="text-align:left">Organization</th>
                            <th id="id"         colspan="1" style="text-align:center">Subcripton #</th>
                            <th id="items"          colspan="1" style="text-align:center">
                                <table class="table m-0">
                                    <tbody>
                                        <td style="text-align:left">Item</td>
                                        <td style="text-align:right">Qty</td>
                                        <td style="text-align:right">Price</td>
                                    </tb>
                                </table>
                            </th>
                            <th id="created"    colspan="1" style="text-align:center">Created</th>
                            <th id="total"      colspan="1" style="text-align:center">Original Total</th>
                            <th id="status"     colspan="1" style="text-align:center">Status</th>
                            <th id="expires"    colspan="1" style="text-align:center">Expires</th>
                            <th id="due"        colspan="1" style="text-align:center">Due</th>
                            <th id="notes"      colspan="1" style="text-align:left">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr id="row_{{$detail['id']}}">
                            <td style="text-align:center">{{$detail['order']}}</td>
                            <td><a href="{{url($path.'/backend/user-profile/'.$detail['user'])}}">{{$detail['name']}}</a></td>
                            <td><a href="{{url($path.'/backend/org-profile/'.$detail['org_id'])}}">{{$detail['org']}}</a></td>
                            <td style="text-align:center">{{$detail['id']}}</td>
                            <td>
                                @foreach($detail['items'] as $item)
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:left">
                                            {{$item['name']}}
                                            </td>
                                            <td style="text-align:right">
                                            {{$item['qty']}}
                                            </td>
                                            <td style="text-align:right">
                                            ${{$item['cost']}}
                                            </td>
                                        </tr>
                                    </tb>
                                </table>
                                @endforeach
                            </td>
                            <td>{{$detail['created']}}</td>
                            <td style="text-align:right" class="align-middle">$ {{$detail['total']}}</td>
                            <td style="text-align:center">{{$detail['status']}}</td>
                            <td style="text-align:center">{{$detail['exp_date']}}</td>
                            <td style="text-align:right" class="align-middle">$ {{$detail['due']}}</td>
                            <td>{{$detail['notes']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="11"></td>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 my-1">&nbsp;</div>
</div>
    </div>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        //TABLE
        var t = $('#stTable').DataTable({
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
        $("#stTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
    </script>
</section>
@endsection





