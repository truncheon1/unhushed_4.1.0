@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="row">
                <div class="backendcrumbs">
                    <a href="{{ url($section.'backend') }}">Admin Backend</a> |
                    <a href="{{ url($section.'backend/effective') }}">Effectiveness Data</a> |
                    Past Participants
                </div>
            </div>

            <!-- TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">EFFECTIVENESS DATA - PAST PARTICIPANTS</div>
                                <div class="col-md-2 text-right">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                                    <a href="{{url($section.'effective/participant/add')}}"><button class="btn btn-secondary btn-sm">ADD</button></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" style="width: 100%" id="participantTable">
                                <thead>
                                    <tr>
                                        <th id="family" colspan="1" style="text-align:center">Family</th>
                                        <th id="name" colspan="2">Participant Name</th>
                                        <th id="email" colspan="1">Email</th>
                                        <th id="phone" colspan="1">Phone</th>
                                        <th id="address" colspan="1">Address</th>
                                        <th id="interviewed" colspan="1" style="text-align:center">Interviewed</th>
                                        <th id="options" colspan="1" style="text-align:center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8"></td>
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
            var t = $('#participantTable').DataTable({
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
            $("#participantTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });
        </script>
    </section>
@endsection
