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
                @if(isset($org->id))
                    <a href="{{ url($path.'/backend/master-users?org_id='.$org->id) }}"> Master Users</a> |
                @else
                    <a href="{{ url($path.'/backend/master-users') }}"> Master Users</a> |
                @endif
                <span style="font-weight: bold;color:#9acd57">Master Orgs</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-6"><b>MASTER ORGS</b></div>
                            <div class="col-6 text-right">
                                @if(auth()->user()->can('access-master'))
                                    <button name="org" id="org" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addOrg">ADD ORGANIZATION</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="orgTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th id="name"       colspan="2" style="text-align:left"  >Organization</th>
                                    <th id="created_at" colspan="1" style="text-align:center">Since</th>
                                    <th id="pedagogy"   colspan="1" style="text-align:center">Pedagogy</th>
                                    <th id="grades"     colspan="1" style="text-align:center">Grades</th>
                                    <th id="size"       colspan="1" style="text-align:center">Students</th>
                                    <th id="status"     colspan="1" style="text-align:center">Status</th>
                                    <th id="city"       colspan="1" style="text-align:center">City</th>
                                    <th id="state"      colspan="1" style="text-align:center">State/Province</th>
                                    <th id="country"    colspan="1" style="text-align:center">Country</th>
                                    <th id="edit"       colspan="1" style="text-align:center">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $org)
                                <tr id="row_{{$org->id}}">
                                    <td style="text-align:right" >{{$org->id}}</td>
                                    <td style="text-align:left"  ><a href="{{url($path.'/backend/org-profile/'.$org->id)}}">{{$org->name}}</a></td>
                                    <td style="text-align:center">@if(empty($org->created_at))@else{{ date('Y', strtotime($org->created_at)) }}@endif</td>
                                    <td style="text-align:left"  >{{$org->pedagogy}}</td>
                                    <td style="text-align:center">{{$org->grades}}</td>
                                    <td style="text-align:center">{{$org->size}}</td>
                                    <td style="text-align:center">
                                        @php
                                            $s = $statuses[$org->id] ?? ['label' => '', 'active_count' => 0, 'reviewing_count' => 0, 'canceled_count' => 0, 'reviewed_count' => 0];
                                        @endphp
                                        @if(!empty($s['active_count']) || !empty($s['reviewing_count']) || !empty($s['canceled_count']) || !empty($s['reviewed_count']))
                                            <small>
                                                @if(!empty($s['active_count'])) Active: {{ $s['active_count'] }} @endif
                                                @if(!empty($s['reviewing_count'])) Reviewing: {{ $s['reviewing_count'] }} @endif
                                                @if(!empty($s['canceled_count'])) Canceled: {{ $s['canceled_count'] }} @endif
                                                @if(!empty($s['reviewed_count'])) Reviewed: {{ $s['reviewed_count'] }} @endif
                                            </small>
                                        @else
                                            &nbsp;
                                        @endif
                                    <td style="text-align:center">{{$org->city}}</td>
                                    <td style="text-align:center">{{$org->state}}</td>
                                    <td style="text-align:left"  >{{$org->country}}</td>
                                    <td colspan="1" style="text-align:center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-success" href="{{url($path.'/backend/org-profile/'.$org->id)}}">Edit</a>
                                                <a class="dropdown-item text-danger user-delete" rel="{{$org->id}}" href="#">Delete</a>
                                            </div>
                                        </div>
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
    <!-- ORG ADD-->
    <div class="modal fade" id="addOrg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <b>MASTER:&nbsp;</b> ADD ORGANIZATION
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/users/org-add')
            </div>
        </div>
    </div>

    <style type="text/css">
        .table{
            font-size: 10px;
        }
        .tinyImg{
            width: 45px;
            height: 45px;
            overflow: hidden;
        }
        .modal-header {
            background-color:#f7f7f7;
        }
    </style>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function(){
            //ORG TABLE
            var t = $('#orgTable').DataTable({
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
            $("#orgTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });
            //BUTTON DONE PREVENTS RELOAD
            $('.btn-done').on('click', function(e){
                console.log("got here");
                e.preventDefault();
                location.reload();
            })
            //ADD ORGANIZATION
            $('.add-org-btn').on('click', function(e){
                e.preventDefault();
                _data = $("#addOrgForm").serialize();
                $.ajax({
                    url: $("#addOrgForm").attr('action'),
                    data: _data,
                    type: 'post',
                    success: function(rsp){
                        if(!rsp.success){
                            alert(show_result(rsp.message));
                            return;
                        }
                        alert("Organization added");
                        location.reload();
                    },
                    fail: function(rsp){
                        alert(rsp);
                    }
                })
            })
        });
    </script>
</section>
@endsection





