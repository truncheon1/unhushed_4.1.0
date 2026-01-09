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
                <span style="font-weight: bold;color:#9acd57">Dictionaries</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-6"><b>DICTIONARY ENTRIES</b></div>
                            <div class="col-6 text-right">
                                @if(auth()->user()->can('access-master') || auth()->user()->can('modify-dictionaries'))
                                    <a class="btn btn-secondary btn-sm add-entry">ADD</a>
                                    <a class="btn btn-secondary btn-sm edit-tags">TAGS</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="dictionaryTable">
                            <thead>
                                <tr>
                                    <th id="term"       colspan="1" style="text-align:left">Term</th>
                                    <th id="phonetic"   colspan="1" style="text-align:left">Phonetic</th>
                                    <th id="audio"      colspan="1" style="text-align:center">Audio File</th>
                                    <th id="slug"       colspan="1" style="text-align:center">Slug</th>
                                    <th id="keywords"   colspan="1" style="text-align:center">Keywords</th>
                                    <th id="alt"        colspan="1" style="text-align:center">Alternates</th>
                                    <th id="edit"       colspan="1" style="text-align:center">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\DictTerms::orderBy('term', 'ASC')->get() as $entry)
                                <tr id="row_{{$entry['id']}}">
                                    <td colspan="1">{{$entry->term}} </td>
                                    <td colspan="1">{!! $entry->phonetic !!}</td>
                                    <td colspan="1">{{$entry->audio}}</td>
                                    <td colspan="1">{{$entry->slug}}</td>
                                    <td colspan="1">{{$entry->keywords}}</td>
                                    <td colspan="1">{{$entry->alt}}</td>
                                    <td colspan="1" style="text-align:center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="dropdown-menu mt-1">
                                                <a class="dropdown-item text-dark entry-edit-link"          rel="{{$entry['id']}}"  href="#">Edit Term</a>
                                                <a class="dropdown-item text-success definition-edit-link"  rel="{{$entry['id']}}"  href="{{ url($path.'/backend/dictionaries/term/'.$entry['id']) }}/defintions">Add/Edit Definitions</a>
                                                <a class="dropdown-item text-danger entry-delete-link"      rel="{{$entry['id']}}"  href="{{ url($path.'/backend/dictionaries/delete/'.$entry['id']) }}">Delete</a>
                                            </div>
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
            <div class="col-12 mt-2">&nbsp;</div>
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
                @include('backend/dictionaries/create')
            </div>
        </div>
    </div>
    <!-- DEFINITION MODAL CALL -->
    <div class="modal fade" id="editDefinitions" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD DEFINITIONS
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/dictionaries/definitions')
            </div>
        </div>
    </div>
    <!-- EDIT TAGS MODAL CALL -->
    <div class="modal fade" id="editTags" tabindex="0" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    EDIT TAGS
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/dictionaries/tags')
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
                @include('backend/dictionaries/edit')
            </div>
        </div>
    </div>

    <style type="text/css" media="all">
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
    </style>
    
     <script type="text/javascript">
        //Document ready script
        $(document).ready(function() {
            //dictionaryTable
           console.log($('#dictionaryTable'))
            t = $('#dictionaryTable').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            pagingType: 'full_numbers'
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
        $("#dictionaryTable").on('page.dt', function(){
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
