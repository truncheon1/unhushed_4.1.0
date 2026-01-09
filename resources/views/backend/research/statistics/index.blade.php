@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="row">
                <div class="backendcrumbs">
                    <a href="{{ url($path.'/backend') }}"> Admin Backend</a> |
                    Statistical Research
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">STATISTICAL RESEARCH CITATION</div>
                                <div class="col text-right">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-dictionaries'))
                                    <a href="{{ url('backend/statistics/create') }}">
                                        <button class="btn btn-secondary btn-sm pull-right">ADD</button>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row m-2">
                                <table class="table table-striped" id="rstatTable">
                                    <thead>
                                        <tr>
                                            <th id="title" width="18%" style="text-alignleft">Title</th>
                                            <th id="author" width="9%" style="text-alignleft">Author</th>
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
                                        @foreach(\App\Models\ResearchStatistics::orderBy('year', 'DESC')->get() as $entry)
                                        <tr id="row_{{$entry->id}}">
                                            <td width="18%" class="clamp">{{$entry->title}} </td>
                                            <td width="9%" class="clamp">{{ $entry->author }}</td>
                                            <td width="9%" class="clamp">{{ $entry->journal }}</td>
                                            <td width="7%">{{ $entry->year }}</td>
                                            <td width="10%">{{ $entry->month }}</td>
                                            <td width="11%" class="clamp">{{ $entry->keywords }}</td>
                                            <td width="8%" class="clamp">{{ $entry->url }}</td>
                                            <td width="18%" class="clamp">{{ $entry->abstract }}</td>
                                            <td width="9%" style="text-align:center">
                                                <div class="btn-group">
                                                    <a rel="{{$entry->id}}" href="{{ url($path.'/backend/statistics/'.$entry->id.'/edit') }}">
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm mr-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </a>
                                                    <a class="entry-delete" rel="{{$entry->id}}" href="#">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </a>
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
    </section>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

        <script type="text/javascript">
            var t;
            $(document).ready(function() {
                t = $('#rstatTable').DataTable({
                    lengthMenu: [[5, 10, -1], [5, 10, "All"]],
                    pagingType: 'full_numbers',
                    "aaSorting": []
                });
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
            $("#rstatTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });
            $('body').on('click', '.entry-delete', function(e){
                e.preventDefault();
                if(!confirm(`Are you sure you want to delete this citation?`)){
                    return false;
                }
                let _id = $(this).attr('rel');
                _url = '{{url($path.'/backend/statistics/')}}/'+_id;
                $.ajax({
                url: _url,
                type: 'delete',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);

                    }else{
                        alert('Citation deleted');
                        location.reload();

                    }

                },
                fail: function(){
                    alert("Error");
                }
                });
            })
        </script>

        <style>
            table {
                table-layout:fixed;
                width:100%;
            }
            .clamp {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis
            }
        </style>
    </section>
@endsection
