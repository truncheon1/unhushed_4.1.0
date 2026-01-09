@extends('layouts.app')
@section('content')
    @include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="crumbBar">
                <div class="breadcrumbsBack">
                    <a href="{{ url($path.'/dashboard') }}"> Backend</a> |
                    <a href="{{ url($path.'/backend/products') }}"> Products</a> |
                    <span style="font-weight: bold;color:#9acd57">{{$package->name}}</span>
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-11">
                    <table class="table unit-table table-striped table-hover table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th colspan="2" style="text-align:center" class="align-middle">
                                @if(auth()->user()->can('access-master') || auth()->user()->can('modify-products'))
                                    <a class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-backdrop="static"
                                       data-bs-keyboard="false" data-bs-target="#addUnit" aria-label="add_unit">ADD
                                        UNIT</a>
                                @endif</th>
                            <th colspan="2" style="text-align:center" class="align-middle">SESSION</th>
                            <th colspan="2" style="text-align:center" class="align-middle">DOCUMENTS</th>
                            <th colspan="1" style="text-align:center" class="align-middle">TIME (mins)</th>
                            <th colspan="1" style="text-align:center" class="align-middle">KEYWORDS</th>
                            <th colspan="1" style="text-align:center" class="align-middle">LINK</th>
                            <th colspan="1" style="text-align:center" class="align-middle">EDIT</th>
                            <th colspan="1" style="text-align:center" class="align-middle">LAST UPDATED</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($units as $u)
                            <tr>
                                <th colspan="1" style="text-align:center"
                                    class="align-middle">{{str_pad($u->number, 2, 0, STR_PAD_LEFT)}}</td>
                                <td colspan="1" style="text-align:center" class="align-middle"><b>{!! $u->name !!}</b>
                                </td>
                                <td colspan="2" style="text-align:left">
                                    <a class="add-session"
                                       href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/add_session') }}"
                                       alt="add session" title="add session"><i class="fa fa-folder-open"></i> ADD
                                        SESSION</a>
                                </td>
                                <td colspan="1" style="text-align:left"></td>
                                <td colspan="1" style="text-align:left"></td>
                                <td colspan="1" style="text-align:left"></td>
                                <td colspan="1" style="text-align:left"></td>
                                <td colspan="1" style="text-align:left"></td>
                                <td colspan="1" style="text-align:center">
                                    <a href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/edit') }}"
                                       alt="edit" title="edit" class="text-success edit-unit"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/delete') }}"
                                       alt="delete" title="delete" class="text-danger delete-unit"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                                <td colspan="1"></td>
                            </tr>
                            <tr>
                                @foreach($u->sessions() as $s)
                                    <td colspan="1"></td>
                                    <td colspan="1"></td>
                                    <td colspan="1"
                                        style="text-align:center">{{str_pad($s->number, 2, 0, STR_PAD_LEFT)}}</td>
                                    <td colspan="1" style="text-align:left">{{$s->name}}</td>
                                    <td colspan="2" style="text-align:left">
                                        <a class="add-document"
                                           href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/document/'.$s->id.'/add') }}"
                                           alt="add document" title="add document"><i class="fa fa-folder-open"></i> ADD
                                            DOCUMENTS</a>
                                    </td>
                                    <td colspan="1" style="text-align:left"></td>
                                    <td colspan="1" style="text-align:left">{{$s->keywords}}</td>
                                    <td colspan="1" style="text-align:left"></td>
                                    <td colspan="1" style="text-align:center">
                                        <a href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/session/'.$s->id.'/edit') }}"
                                           alt="edit" title="edit" class="text-success edit-session"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/session/'.$s->id.'/delete') }}"
                                           alt="delete" title="delete" class="text-danger delete-session"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                    <td colspan="1"></td>
                            </tr>
                            @if(count($s->documents()))
                                @foreach($s->documents() as $d)
                                    <tr>
                                        <td colspan="1"></td>
                                        <td colspan="1"></td>
                                        <td colspan="1"></td>
                                        <td colspan="1"></td>
                                        <td colspan="1"
                                            style="text-align:center">{{str_pad($d->number, 2, 0, STR_PAD_LEFT)}}</td>
                                        <td colspan="1" style="text-align:left">{!! $d->name !!}</td>
                                        <td colspan="1" style="text-align:center" class="align-middle">{{$d->time}}</td>
                                        <td colspan="1" style="text-align:left">{{$d->keywords}}</td>
                                        <td colspan="1" style="text-align:center">
                                            @empty($d->filenfo)
                                                <a href="{{ url($d->url) }}" target="_blank"><i
                                                        class="fas fa-link fa-lg fa-fw"></i></a>
                                            @else
                                                <a href="{{asset('uploads/subscriptions/'.$d->document)}}"
                                                   target="_blank" rel="noopener noreferrer"><i
                                                        class="fa-solid fa-file-arrow-down fa-fw"></i></a>
                                            @endempty
                                        </td>
                                        <td colspan="1" style="text-align:right">
                                            <a href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/session/'.$s->id.'/document/'.$d->id.'/edit')}}"
                                               alt="edit" title="edit" class="text-success edit-document"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{ url($path.'/backend/packages/'.$package->id.'/dashboard/'.$u->id.'/session/'.$s->id.'/document/'.$d->id.'/delete')}}"
                                               alt="delete" title="delete" class="text-danger delete-document"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                        <td colspan="1">{{date('m/d/Y', strtotime($d->updated_at))}}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endforeach {{-- end sessions loop --}}
                        @endforeach {{-- end units loop --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12 mb-5">&nbsp;</div>
            </div>
        </div>

        <!-- unit -->
        <div class="modal fade" id="addUnit" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        ADD UNIT
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{ url($path.'/backend/packages/'.$package->id.'/add_unit') }}" method="POST"
                              id="add_unit" role="form" onsubmit="return addUnit()" aria-label="add_unit_modal">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="number" class="col-md-4 col-form-label text-md-right">Number</label>
                                <div class="col-md-6">
                                    <input id="number" type="text" class="form-control" name="number" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keywords" class="col-md-4 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-6">
                                    <input id="keywords" type="text" class="form-control" name="keywords" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="editUnit" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        EDIT UNIT
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{ url($path.'/backend/packages/'.$package->id.'/edit') }}" method="POST"
                              id="edit_unit" role="form" onsubmit="return editUnit()" aria-label="edit_unit">
                            <input type="hidden" name="id" value="0" id="eid"/>
                            @csrf
                            <div class="form-group row">
                                <label for="ename" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="ename" type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="enumber" class="col-md-4 col-form-label text-md-right">Number</label>
                                <div class="col-md-6">
                                    <input id="enumber" type="text" class="form-control" name="number" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ekeywords" class="col-md-4 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-6">
                                    <input id="ekeywords" type="text" class="form-control" name="keywords" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary btn-edit-unit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- session -->
        <div class="modal fade" id="addSession" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        ADD SESSION
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{ url('/backend/packages/'.$package->id.'/add_session') }}" method="POST"
                              id="add_session" role="form" onsubmit="return addSession()" aria-label="add_session">
                            <input type="hidden" name="id" value="0" id="eid"/>
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="number" class="col-md-4 col-form-label text-md-right">Number</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="number" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keywords" class="col-md-4 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="keywords" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="editSession" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        EDIT SESSION
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{ url('/backend/packages/'.$package->id.'/add_session') }}" method="POST"
                              id="edit_session" role="form" onsubmit="return editSession()" aria-label="edit_session">
                            <input type="hidden" name="id" value="0" id="sid"/>
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="sname" type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="number" class="col-md-4 col-form-label text-md-right">Number</label>
                                <div class="col-md-6">
                                    <input id="snumber" type="text" class="form-control" name="number" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keywords" class="col-md-4 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-6">
                                    <input id="skeywords" type="text" class="form-control" name="keywords" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary btn-edit-session">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- documents -->
        <div class="modal fade" id="addDocument" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        ADD DOCUMENT
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{ url('/backend/packages/'.$package->id.'/add_session') }}"
                              enctype="multipart/form-data" method="POST" id="add_document" role="form"
                              aria-label="add_document">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row" aria-label="number">
                                <label for="number" class="col-md-4 col-form-label text-md-right">Number</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="number" name="number">
                                        @for($x = 1; $x <=20; $x++)
                                            <option
                                                value="{{str_pad($x, 2, 0, STR_PAD_LEFT)}}">{{str_pad($x, 2, 0, STR_PAD_LEFT)}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="time" class="col-md-4 col-form-label text-md-right">Time</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="time" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keywords" class="col-md-4 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="keywords" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="doc_type" class="col-md-4 col-form-label text-md-right">Document
                                    Type</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="doc_type" name="type">
                                        <option value="file">File</option>
                                        <option value="url">URL Resource</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row file-row">
                                <label for="file" class="col-md-4 col-form-label text-md-right">File</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="file" value="">
                                </div>
                            </div>
                            <div class="form-group row link-row">
                                <label for="link" class="col-md-4 col-form-label text-md-right">Link</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="url" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="editDocument" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        EDIT DOCUMENT
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{ url('/backend/packages/'.$package->id.'/add_session') }}" method="POST"
                              id="edit_document" role="form" aria-label="edit_document">
                            <input type="hidden" name="id" value="0" id="did"/>
                            @csrf
                            <div class="form-group row">
                                <label for="uid" class="col-md-4 col-form-label text-md-right">Unit</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="uid" name="uid">
                                        @foreach ($units as $data)
                                            <option value="{{$data->id}}">{{strip_tags($data->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sid1" class="col-md-4 col-form-label text-md-right">Sessions</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="sid1" name="sid1">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dname" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="dname" type="text" class="form-control" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dkeywords" class="col-md-4 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-6">
                                    <input id="dkeywords" type="text" class="form-control" name="keywords" value="">
                                </div>
                            </div>
                            <div class="form-group row" aria-label="dnumber">
                                <label for="dnumber" class="col-md-4 col-form-label text-md-right">Number</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="dnumber" name="number">
                                        @for($x = 1; $x <=20; $x++)
                                            <option
                                                value="{{str_pad($x, 2, 0, STR_PAD_LEFT)}}">{{str_pad($x, 2, 0, STR_PAD_LEFT)}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dtime" class="col-md-4 col-form-label text-md-right">Time</label>
                                <div class="col-md-6">
                                    <input id="dtime" type="text" class="form-control" name="time" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="doc_type" class="col-md-4 col-form-label text-md-right">Document
                                    Type</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="doc_type1" name="type">
                                        <option value="file">Document</option>
                                        <option value="url">URL Resource</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row link-row1">
                                <label for="link" class="col-md-4 col-form-label text-md-right">Link</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="elink" name="url" value="">
                                </div>
                            </div>
                            <div class="form-group row file-row1">
                                <label for="file" class="col-md-4 col-form-label text-md-right">Re-upload File</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="file" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="timestamp" class="col-md-4 col-form-label text-md-right">Alter Date
                                    Updated?</label>
                                <div class="col-md-6">
                                    <select class="form-select" input id="timestamp" name="timestamp">
                                        <option value="1">Yes, change to today's date.</option>
                                        <option value="2">No, leave previous updated at date.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-auto mx-auto text-center">
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style type="text/css" media="all">
            .handler {
                max-width: 10px;
                cursor: pointer;
            }

            table {
                background-color: white;
                font-size: 12px;
            }

            .modal-header {
                background-color: #f7f7f7;
            }
        </style>

        <script type="text/javascript">
            /**
             * Strips HTML tags from a string
             * @param dirtyString
             * @return {string}
             */
            function stripHtml(dirtyString) {
                const doc = new DOMParser().parseFromString(dirtyString, 'text/html');
                return doc.body.textContent || '';
            }

            // Track original session selection for edit-document modal to prevent accidental reassignment
            let originalSessionId = null;

            //UNITS
            /**
             * Sessions listing
             * On modal load, or unit change fetch the sessions for the selected unit
             * and populate the sessions dropdown
             * if the selected session is in the sessions list, select it
             */
            function fetchSessions(selectedId)
            {
                var unitId = $("#uid").val();
                $.ajax({
                    url: "{{'/'.$path.'/backend/packages/'.$package->id.'/fetch_sessions/'}}" + unitId,
                    type: "GET",
                    success: function (result) {
                        $('#sid1').html('<option value="">-- Select Session --</option>');
                        $.each(result, function (key, value) {
                            $("#sid1").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        if(selectedId){
                            $('#sid1').val(String(selectedId));
                        }
                    }
                });
            }
            fetchSessions();
            $(document).ready(function(){
                $('body').on('change', '#uid', function (e) {
                    fetchSessions();
                });
            })


            function addUnit() {
                url = $('#add_unit').attr('action');
                data = $('#add_unit').serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function (result) {
                        if (result.success == false) {
                            _text = '';
                            for (k in result.message) {
                                for (j in result.message[k]) {
                                    _text += result.message[k][j] + " \n";
                                }
                            }
                            alert(_text);
                        } else {
                            alert('Unit Added');
                            location.reload();
                        }
                    }
                });
                return false;
            }

            function addSession() {
                url = $('#add_session').attr('action');
                data = $('#add_session').serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function (result) {
                        if (result.success == false) {
                            _text = '';
                            for (k in result.message) {
                                for (j in result.message[k]) {
                                    _text += result.message[k][j] + " \n";
                                }
                            }
                            alert(_text);
                        } else {
                            alert('Session Added');
                            location.reload();
                        }
                    }
                });
                return false;
            }

            $(document).ready(function () {
                $('body').on('click', '.edit-unit', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (result) {
                            console.log(result);
                            console.log(result.data.id);
                            $("#eid").val(result.data.id);
                            $("#ekeywords").val(result.data.keywords);
                            $("#enumber").val(result.data.number);
                            $("#ename").val(stripHtml(result.data.name));
                            $("#editUnit").modal('show');
                        }
                    });
                });

                $(".btn-edit-unit").on('click', function (e) {
                    e.preventDefault();
                    editUnit();
                });
                $(".btn-edit-session").on('click', function (e) {
                    e.preventDefault();
                    editSession();
                })

                function editUnit() {
                    url = $('#edit_unit').attr('action');
                    data = $('#edit_unit').serialize();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function (result) {
                            if (result.success == false) {
                                _text = '';
                                for (k in result.message) {
                                    for (j in result.message[k]) {
                                        _text += result.message[k][j] + " \n";
                                    }
                                }
                                alert(_text);
                            } else {
                                alert('Unit updated.');
                                location.reload();
                            }
                        }
                    });
                    return false;
                }

                $('body').on('click', '.delete-unit', function (e) {
                    if (!confirm('Are you sure you want to delete this unit?'))
                        return;
                    e.preventDefault();
                    url = $(this).attr('href');
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (result) {
                            alert('Unit deleted.');
                            location.reload();
                        }
                    });
                });
                //Sessions
                $('body').on('click', '.add-session', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    $("#add_session").attr('action', url);
                    $("#addSession").modal('show');
                });


                $('body').on('click', '.edit-session', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    $("#edit_session").attr('action', url);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (result) {
                            console.log(result);
                            console.log(result.data.id);
                            $("#sid").val(result.data.id);
                            $("#snumber").val(result.data.number);
                            $("#sname").val(stripHtml(result.data.name));
                            $("#skeywords").val(result.data.keywords);
                            $("#editSession").modal('show');
                        }
                    });
                });

                function editSession() {
                    url = $('#edit_session').attr('action');
                    data = $('#edit_session').serialize();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function (result) {
                            if (result.success == false) {
                                _text = '';
                                for (k in result.message) {
                                    for (j in result.message[k]) {
                                        _text += result.message[k][j] + " \n";
                                    }
                                }
                                alert(_text);
                            } else {
                                alert('Session Updated');
                                location.reload();
                            }
                        }
                    });
                    return false;
                };
                $('body').on('click', '.delete-session', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    if (!confirm('Are you sure you want to delete this session?'))
                        return;
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (result) {
                            alert('Session deleted.');
                            location.reload();
                        }
                    });
                });

                $('body').on('click', '#doc_type', function (e) {
                    if ($(this).val() == 'file') {
                        $(".file-row").css({'display': 'flex'});
                        $(".link-row").css({'display': 'none'});
                    } else {
                        $(".file-row").css({'display': 'none'});
                        $(".link-row").css({'display': 'flex'});
                    }
                });

                $('body').on('click', '#doc_type1', function (e) {
                    if ($(this).val() == 'file') {
                        $(".file-row1").css({'display': 'flex'});
                        $(".link-row1").css({'display': 'none'});
                    } else {
                        $(".file-row1").css({'display': 'none'});
                        $(".link-row1").css({'display': 'flex'});
                    }
                });

                //DOCUMENTS
                //add
                $('body').on('click', '.add-document', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    $("#add_document").attr('action', url);
                    $("#doc_type").val('file');
                    $(".file-row").css({'display': 'flex'});
                    $(".link-row").css({'display': 'none'});
                    $("#addDocument").modal('show');
                });

                //edit
                $('body').on('click', '.edit-document', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    $("#edit_document").attr('action', url);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (result) {
                            console.log(result);
                            console.log(result.data.id);
                            $("#uid").val(result.data.unit_id);
                            $("#did").val(result.data.id);
                            $("#dnumber").val(result.data.number);
                            $("#dname").val(stripHtml(result.data.name));
                            $("#dkeywords").val(result.data.keywords);
                            $("#dtime").val(result.data.time);
                            console.log(result.data.file);
                            $("#elink").val(result.data.url);
                            $("#doc_type1").val(result.data.type);
                            console.log(result.data.type);
                            if (result.data.type == 'file') {
                                $(".file-row1").css({'display': 'flex'});
                                $(".link-row1").css({'display': 'none'});
                            } else {
                                $(".file-row1").css({'display': 'none'});
                                $(".link-row1").css({'display': 'flex'});
                            }
                            // Refresh sessions for the selected unit and preselect the current session
                            originalSessionId = result.data.session_id ? String(result.data.session_id) : null;
                            fetchSessions(originalSessionId);
                            $("#editDocument").modal('show');
                        }
                    });
                });

                // Confirmation on session reassignment inside edit-document modal
                $('body').on('change', '#sid1', function(){
                    const newVal = $(this).val();
                    if(originalSessionId && newVal !== originalSessionId){
                        const ok = confirm('Change the associated session for this document? This will reassign it.');
                        if(!ok){
                            $(this).val(originalSessionId);
                            return;
                        }
                        // Accept change: update originalSessionId so multiple changes don't spam confirms
                        originalSessionId = newVal;
                    }
                });
                $('body').on('click', '.delete-document', function (e) {
                    e.preventDefault();
                    url = $(this).attr('href');
                    if (!confirm('Are you sure you want to delete this document?'))
                        return;
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (result) {
                            alert('Document deleted!');
                            location.reload();
                        }
                    });
                });
                $("form#add_document").submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    console.log(formData);
                    url = $(this).attr('action');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        enctype: 'multipart/form-data',
                        success: function (result) {
                            if (result.success == false) {
                                _text = '';
                                for (k in result.message) {
                                    for (j in result.message[k]) {
                                        _text += result.message[k][j] + " \n";
                                    }
                                }
                                alert(_text);
                            } else {
                                alert('Document Added');
                                location.reload();
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                });
                $("form#edit_document").submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    console.log(formData);
                    url = $(this).attr('action');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        enctype: 'multipart/form-data',
                        success: function (result) {
                            if (result.success == false) {
                                _text = '';
                                for (k in result.message) {
                                    for (j in result.message[k]) {
                                        _text += result.message[k][j] + " \n";
                                    }
                                }
                                alert(_text);
                            } else {
                                alert('Document Updated');
                                location.reload();
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                })
            });

            function editDocument() {
            }

            //Moveable Modals
            $("#addUnit").draggable({
                handle: ".modal-header"
            });
            $("#editUnit").draggable({
                handle: ".modal-header"
            });
            $("#addSession").draggable({
                handle: ".modal-header"
            });
            $("#editSession").draggable({
                handle: ".modal-header"
            });
            $("#addActivity").draggable({
                handle: ".modal-header"
            });
            $("#editActivity").draggable({
                handle: ".modal-header"
            });
            $("#addDocument").draggable({
                handle: ".modal-header"
            });
            $("#editDocument").draggable({
                handle: ".modal-header"
            });
        </script>
    </section>
@endsection
