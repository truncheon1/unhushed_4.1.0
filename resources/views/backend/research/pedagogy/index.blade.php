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
                <a href="{{ url($path.'/backend') }}">Backend</a> |
                <span style="font-weight: bold;color:#9acd57">Pedagogical Research</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-6"><b>PEDAGOGICAL RESEARCH CITATION</b></div>
                                <div class="col text-right">
                                    <div class="col-6 float-end">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-dictionaries'))
                                        <a class="btn btn-secondary btn-sm add-entry">ADD</a>
                                    @endif
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="rpedTable">
                                <thead>
                                    <tr>
                                        <th id="title" width="18%" style="text-align:left">Title</th>
                                        <th id="author" width="9%" style="text-align:left">Author</th>
                                        <th id="journal" width="9%" style="text-align:left">Journal</th>
                                        <th id="year" width="7%" style="text-align:left">Year</th>
                                        <th id="month" width="10%" style="text-align:left">Month</th>
                                        <th id="keywords" width="11%" style="text-align:left">Keywords</th>
                                        <th id="url" width="8%" style="text-align:left">Url</th>
                                        <th id="abstract" width="18%" style="text-align:left">Abstract</th>
                                        <th id="options" width="9%" style="text-align:left">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\ResearchPedagogy::orderBy('year', 'DESC')->get() as $entry)
                                    <tr id="row_{{$entry->id}}">
                                        <td colspan="1" class="clamp">{{$entry->title}} </td>
                                        <td colspan="1" class="clamp">{{ $entry->author }}</td>
                                        <td colspan="1" class="clamp">{{ $entry->journal }}</td>
                                        <td colspan="1" >{{ $entry->year }}</td>
                                        <td colspan="1" >{{ $entry->month }}</td>
                                        <td colspan="1" class="clamp">{{ $entry->keywords }}</td>
                                        <td colspan="1" class="clamp">{{ $entry->url }}</td>
                                        <td colspan="1" class="clamp">{{ $entry->abstract }}</td>
                                        <td colspan="1" style="text-align:center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <div class="dropdown-menu mt-1">
                                                    <a class="dropdown-item text-dark entry-edit-link"     rel="{{$entry->id}}"  href="#">Edit entry</a>
                                                    <a class="dropdown-item text-danger entry-delete-link" rel="{{$entry->id}}"  href="{{ url($path.'/backend/pedagogy/delete/'.$entry->id) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                        </tbody>
                                        <tfoot>
                                            <td colspan="9"></td>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD ENTRY MODAL CALL -->
    <div class="modal fade" id="addEntry" tabindex="0" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD ENTRY
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/research/pedagogy/create')
            </div>
        </div>
    </div>
    <!-- EDIT ENTRY MODAL CALL -->
    <div class="modal fade" id="editEntry" tabindex="0" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    EDIT ENTRY
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/research/pedagogy/edit')
            </div>
        </div>
    </div>

    <style type="text/css" media="all">
        .clamp {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }
        .dropdown-menu::before{
            display: none;
        }
        .modal-header {
            background-color:#f7f7f7;
        }
        .modal-dialog{
            overflow-y: initial !important
        }
        .modal-body{
            height: 80vh;
            overflow-y: auto;
        }
        table {
            table-layout:fixed;
            width:100%;
        }
    </style>

    <script type="text/javascript">
        //Document ready script
        $(document).ready(function() {
            //Research Pedagogy Table
        console.log($('#rpedTable'))
            t = $('#rpedTable').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            pagingType: 'full_numbers',
            "aaSorting": []
            });
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
        $("#rpedTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });

        //DELETE ENTRY
        $("body").on('click', '.entry-delete-link', function(e){
            e.preventDefault();
            if(!confirm('Are you sure you want to delete this term?'))
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
                        alert("Entry Deleted!");
                        location.reload();
                    }
                },
                fail: function(){ alert("Error"); }
            });
            return false;
        });
    </script>
</section>
@endsection
