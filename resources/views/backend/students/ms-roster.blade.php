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
                <span style="font-weight: bold;color:#9acd57">MS class roster</span>
            </div>
        </div>

        <!-- TABLE -->
        <div class="row mb-5">
            <div class="col-10">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-12">Middle School Roster</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="scrollB">
                            <table class="table table-striped" width="100%" id="stuTable" style="font-size:10px;">
                                <thead>
                                    <tr>
                                        <!--<th id="year"           colspan="1" style="text-align:center">Year</th>-->
                                        <th id="youth_name"       colspan="1" style="text-align:center">Student</th>
                                        <!--<th id="youth_email"       colspan="1" style="text-align:center">Email</th>-->
                                        <!--<th id="youth_phone"       colspan="1" style="text-align:center">Phone</th>-->
                                        <th id="age"            colspan="1" style="text-align:center">Age</th>
                                        <th id="grade"          colspan="1" style="text-align:center">Grade</th>
                                        <th id="gender"         colspan="1" style="text-align:center">Gender</th>
                                        <th id="pronouns"       colspan="1" style="text-align:center">Pronouns</th>
                                        <th id="paid"           colspan="1" style="text-align:center">Paid</th>
                                        <th id="legal"          colspan="1" style="text-align:center">Contract</th>
                                        <th id="parent1_name"   colspan="1" style="text-align:center">Parent 1</th>
                                        <th id="parent1_email"  colspan="1" style="text-align:center">Email</th>
                                        <th id="parent1_phone"  colspan="1" style="text-align:center">Phone</th>
                                        <th id="parent2_name"   colspan="1" style="text-align:center">Parent 2</th>
                                        <th id="parent2_email"  colspan="1" style="text-align:center">Email</th>
                                        <th id="parent2_phone"  colspan="1" style="text-align:center">Phone</th>
                                        <th id="options"        colspan="1" style="text-align:center">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($details))
                                @forelse($details as $detail)
                                    <tr id="row_{{$detail['id']}}">
                                        <!--<td style="text-align:left">{{$detail['year']}}</td>-->
                                        <td style="text-align:left">{{$detail['name']}}</td>
                                        <!--<td style="text-align:left">{{$detail['email']}}</td>-->
                                        <!--<td style="text-align:left">{{$detail['phone']}}</td>-->
                                        <td style="text-align:center">{{$detail['age']}}</td>
                                        <td style="text-align:center">{{$detail['grade']}}</td>
                                        <td style="text-align:center">{{$detail['gender']}}</td>
                                        <td style="text-align:center">{{$detail['pronouns']}}</td>
                                        <td style="text-align:center">${{$detail['paid']}}</td>
                                        <td style="text-align:center">{{$detail['legal']}}</td>
                                        <td style="text-align:left">{{$detail['p1_name']}}</td>
                                        <td style="text-align:center"><a href = "mailto: {{$detail['p1_email']}}">{{$detail['p1_email']}}</a></td>
                                        <td style="text-align:center"><a href="tel:{{$detail['p1_phone']}}">{{$detail['p1_phone']}}</a></td>
                                        <td style="text-align:left">{{$detail['p2_name']}}</td>
                                        <td style="text-align:center"><a href = "mailto: {{$detail['p2_email']}}">{{$detail['p2_email']}}</a></td>
                                        <td style="text-align:center"><a href="tel:{{$detail['p2_phone']}}">{{$detail['p2_phone']}}</a></td>
                                        <td colspan="1" style="text-align:center">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm dropdown-toggle text-dark" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <div class="dropdown-menu mt-1">
                                                    <a class="dropdown-item text-dark student-edit-link" rel="{{$detail['id']}}" href="{{url($path.'/backend/ms-roster/'.$detail['id'])}}" >Edit</a>
                                                    <a class="dropdown-item text-danger student-delete-link" rel="{{$detail['id']}}" href="{{ url($path.'/backend/ms-roster/'.$detail['id'])}}/delete">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="14"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStudent" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('backend/students/ms-roster-edit')
            </div>
        </div>
    </div>

    <style>
        .scrollB {
            overflow-x: scroll;
            margin: 0 10px 0 10px;
        }
    </style>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        //STUDENT TABLE
        var t = $('#stuTable').DataTable({
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            pagingType: 'full_numbers'
        });
        //KEEP CURRENT PAGE
        var currentPage = 0;
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        if(urlParams.has('page')){
            currentPage = urlParams.get('page') * 1; //force number
        }
        $('document').ready(function(){
            t.page(currentPage).draw(false);
        })
        $("#stuTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
        /***********************************************
         * DELETE STUDENT
         **********************************************/
        $("body").on('click', '.student-delete-link', function(e){
            e.preventDefault();
            if(!confirm('Are you sure you want to delete this student?'))
                return;
            _data = {
                'id': $(this).attr('rel')
            }
            _url = $(this).attr('href');
            $.ajax({
                url: _url,
                type: 'post',
                data: _data,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Student deleted.");
                        location.reload();
                    }
                },
                fail: function(){ alert("Error"); }
            });
            return false;
        });
        /**END**/        
    </script>
</section>
@endsection





