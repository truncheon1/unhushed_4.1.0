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
                <span style="font-weight: bold;color:#9acd57">Team</span>
                
            </div>
        </div>

        <!--  TABLE -->
        <div class="row mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                                <div class="col-md-10">TEAM MEMBER ENTRIES</div>
                                <div class="col-md-2 text-right">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-team'))
                                    <a href="{{ url($path.'/backend/team/create') }}"><button class="btn btn-secondary btn-sm">ADD</button></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table" id="teamTable">
                                <thead>
                                    <tr>
                                        <th id="image" colspan="1" style="text-alignleft">Headshot</th>
                                        <th id="first" colspan="1" style="text-alignleft">Name</th>
                                        <th id="title" colspan="1" style="text-align:left">Title</th>
                                        <th id="dept" colspan="1" style="text-alignleft">Department</th>
                                        <th id="slug" colspan="1" style="text-alignleft">Slug</th>
                                        <th id="ac_tags" colspan="1" style="text-alignleft">AC Tags</th>
                                        <th id="options" colspan="1" style="text-align:center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Team::orderBy('first', 'ASC')->get() as $member)
                                    <tr id="row_{{$member->id}}">
                                        <td colspan="1"><img class="tinyImg" src="{{ url('uploads/team/'.$member->image) }}" alt="{{$member->name}}"/></td>
                                        <td colspan="1" style="text-alignleft">{{$member->first}} {{$member->last}}</td>
                                        <td colspan="1" style="text-alignleft">{{$member->title}} </td>
                                        <td colspan="1" style="text-alignleft">{{$member->dept}} </td>
                                        <td colspan="1" style="text-alignleft">{{$member->slug}} </td>
                                        <td colspan="1" style="text-alignleft">{{$member->ac_tags}} </td>
                                        <td colspan="1" style="text-align:center">
                                            <div class="btn-group">
                                                <a rel="{{$member->id}}" href="{{ url($path.'/backend/team/'.$member->id.'/edit') }}">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm mr-2">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </a>
                                                <a class="team-delete" rel="{{$member->id}}" href="#">
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
                                    <tr>
                                        <td colspan="7"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-1">&nbsp;</div>
            </div>
        </div>

        <style>
            .tinyImg{
                width: 45px;
                height: 45px;
                overflow: hidden;
            }
        </style>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script>
            var t = $('#teamTable').DataTable({
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
            $("#teamTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });
            //DELETE TEAM MEMBER
            $('body').on('click', '.team-delete', function(e){
                e.preventDefault();
                if(!confirm(`Are you sure you want to delete this team member?`)){
                    return false;
                }
                let _id = $(this).attr('rel');
                _url = '{{ url($path.'/backend/team/') }}/'+_id;
                $.ajax({
                    url: _url,
                    type: 'delete',
                    success: function(response){
                        console.log(response);
                        if(response.error === true){
                            alert(response.message);
                        }else{
                            alert('Team member deleted');
                            location.reload();
                        }
                    },
                    fail: function(){ alert("Error"); }
                });
            })
        </script>
    </section>
@endsection
