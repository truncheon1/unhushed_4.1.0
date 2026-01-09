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
                <span style="font-weight: bold;color:#9acd57">Educational Standards</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row mt-2 mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-10">EDUCATIONAL STANDARDS</div>
                            <div class="col text-right">
                                @if(auth()->user()->can('access-master') || auth()->user()->can('modify-dictionaries'))
                                <a href="{{ url($path.'/backend/standards/create') }}">
                                    <button class="btn btn-secondary btn-sm pull-right">ADD</button>
                                </a>
                                @endif
                                <a href="{{ url($path.'/standards') }}">
                                    <button class="btn btn-secondary btn-sm pull-right">VIEW</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row m-2">
                            <table class="table table-striped" id="sTable">
                                <thead>
                                    <tr>
                                        <th id="author"    style="text-align:left;">Author</th>
                                        <th id="standard"  style="text-align:left;">Standard</th>
                                        <th id="image"     style="text-align:left;">Image</th>
                                        <th id="link"      style="text-align:left;">Link</th>
                                        <th id="aligns"    style="text-align:center;">Aligns</th>
                                        <th id="options"   style="text-align:center;">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $entry)
                                    <tr id="row_{{$entry->id}}">
                                        <td>{{$entry->author}}</td>
                                        <td>{{$entry->standard}}</td>
                                        <td colspan="1" style="text-align:center">
                                            @empty($entry->image)
                                            <a href="#" class="flag-edit-link" rel="{{$entry->id}}" ><i class="fas fa-plus"></i></a>
                                            @else
                                            <a href="#" class="flag-edit-link" rel="{{$entry->id}}" >
                                            <img class="tinyImg" src="{{ url('uploads/standards/'.$entry->image) }}" alt="{{ $entry->image}}"/></a>
                                            @endempty
                                        </td>
                                        <td>{{ $entry->link }}</td>
                                        <td style="text-align:center">{{ $entry->aligns }}</td>
                                        <td style="text-align:center">
                                            <div class="btn-group">
                                                <a rel="{{$entry->id}}" href="#" data-bs-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#edit">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm mr-2">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </a>
                                                <a class="entry-delete line-danger btn-sm" rel="{{$entry->id}}">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <td colspan="6"></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">&nbsp;</div>
        </div>
    </div>

    <!-- ADD STANDARD -->
    <div class="modal fade" id="addStandard" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD PRODUCT
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/standards/create')
            </div>
        </div>
    </div>

    <!-- EDIT FLAG MODAL CALL -->
    <div class="modal fade" id="editFlag" tabindex="0" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    FLAG
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/standards/edit-flag')
            </div>
        </div>
    </div>
    <!-- EDIT STANDARD -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    EDIT STANDARD
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{url($path.'/backend/standards')}}" method="POST" id="edit" role="form" onsubmit="return edit()" aria-label="edit">
                        <input type="hidden" name="id" id="eid" value="{{$entry->id}}" />
                        @csrf
                        <div class="form-group row">
                            <label for="ename" class="col-md-4 col-form-label text-md-right">Standard</label>
                            <div class="col-md-6">
                                <input id="ename" type="text" class="form-control" name="name" value="{{ $entry->author }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="elink" class="col-md-4 col-form-label text-md-right">Link</label>
                            <div class="col-md-6">
                                <input id="elink" type="text" class="form-control" name="link" value="{{ $entry->link }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ealigns" class="col-md-4 col-form-label text-md-right">Aligns</label>
                            <div class="col-md-6">
                                <select class="form-control" id="ealigns" name="aligns">
                                    <option value="yes">yes</option>
                                    <option value="no">no</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-auto mx-auto">
                                <button type="submit" class="btn btn-secondary">SAVE</button>
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
        $(document).ready(function() {
            $('#sTable').DataTable({
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
        $("#sTable").on('page.dt', function(){
        var info = t.page.info();
        currentPage = info;
        console.log(currentPage.page);
        urlParams.set('page', currentPage.page);
        history.pushState(null, null, "?"+urlParams.toString());
        });
        //DRAGGABLE MODALS
        $("#edit").draggable({
            handle: ".modal-header"
        });
        $("#editFlag").draggable({
            handle: ".modal-header"
        });
        //EDIT STANDARD
        function edit(){
            let _url = $("#edit").attr('action');
            fd = $("#edit").serialize();
            $.ajax({
                url: _url,
                type: 'patch',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Standard updated!");
                        location.reload();
                        $("#edit").modal('hide');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
            return false;
        };
        $('.edit').on('click', function(e){
            e.preventDefault();
            _url = $(this).attr('href');
            $.ajax({
                url: _url,
                type: 'get',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        $("#eid").val(response.standard.id);
                        $("#ename").val(response.standard.name);
                        $("#eurl").val(response.standard.url);
                        $("#ealigns").val(response.standard.aligns);
                        $("#edit").modal('show');
                    }
                },
                fail: function(){ alert("Error"); }
            });
        });
        //DELETE STANDARD
        $('body').on('click', '.entry-delete', function(e){
            e.preventDefault();
            if(!confirm(`Are you sure you want to delete this information?`)){
                return false;
            }
            let _id = $(this).attr('rel');
            _url = '{{url($path.'/backend/standards/')}}/'+_id;
            $.ajax({
            url: _url,
            type: 'delete',
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert('Information deleted');
                    location.reload();
                }
            },
            fail: function(){
                alert("Error");
                }
            });
        })
        //edit flag image
        $('body').on("click", '.flag-edit-link', function(e){
            sorted = [];
            uploaded_images = [];
            e.preventDefault();
            _id = $(this).attr('rel');
            $.ajax({
                url: '{{url($path."/backend/standards/flag")}}/'+_id,
                type: 'get',
                success: function(response){
                    console.log(response);
                    $(".id").val(response.entry.id);
                    $("#editFlag").modal('show');
                },
                fail: function(){ alert("Error"); }
            });
        });
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
