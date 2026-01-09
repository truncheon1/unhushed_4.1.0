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
                <a href="{{ url($path.'/backend/orders') }}"> Orders to Ship</a> |
                <span style="font-weight: bold;color:#9acd57">Renewals</span> |
                <a href="{{ url($path.'/subscriptons-trainings') }}"> Subscriptions/Trainings</a>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row mt-2 mb-5">
            <div class="col-auto">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-9">RENEWALS</div>
                            <div class="col-md-3 text-right">{{ date('Y-m-d') }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="renewalTable" class="table table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <th id="user"       colspan="1" style="text-align:left">User</th>
                                    <th id="org"        colspan="1" style="text-align:left">Organization</th>
                                    <th id="package"    colspan="1" style="text-align:left">Package</th>
                                    <th id="order"      colspan="1" style="text-align:center">Order</th>
                                    <th id="expires"    colspan="1" style="text-align:center">Expires</th>
                                    <th id="due"        colspan="2" style="text-align:center">Due</th>
                                    <th id="created"    colspan="1" style="text-align:center">Created</th>
                                    <th id="notes"    colspan="1" style="text-align:left">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $detail)
                                <tr id="row_{{$detail['id']}}">
                                    <td>{{$detail['name']}}</td>
                                    <td>{{$detail['org']}}</td>
                                    <td>{{$detail['packname']}}</td>
                                    <td>{{$detail['order']}}</td>
                                    <td>{{$detail['exp_date']}}</td>
                                    <td colspan="1" style="text-align:left"  class="align-middle">$</td>
                                    <td colspan="1" style="text-align:right" class="align-middle">{{number_format($detail['due'], 2)}}</td>
                                    <td>{{$detail['created']}}</td>
                                    <td>{{$detail['notes']}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2" style="text-align:right"><b>Due in the next 30 days</b></td>
                                    <td colspan="1" style="text-align:left"     class="align-middle">$</td>
                                    <td colspan="1" style="text-align:right"    class="align-middle">{{number_format($total, 2)}}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 my-1">&nbsp;</div>
        </div>
    </div>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        //TABLE
        var t = $('#renewalTable').DataTable({
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
        $("#renewalTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
    </script>
</section>
@endsection





