@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- Organization Creation Success Alert -->
    @if(session('org_created_success'))
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="font-size: 1.1rem; padding: 1.5rem;">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <br>
                    <strong>{{ session('org_created_success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <span style="font-weight: bold;color:#9acd57">Users</span>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-6" style="text-transform: uppercase; font-weight: bold;">{{ \App\Models\Organizations::find(Auth::user()->org_id)->name }} USERS</div>
                            <div class="col text-right">
                                <button name="add" id="add" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#userProfile">ADD USER</button> |
                                <a href="{{ url($path.'/dashboard/assign-access') }}">
                                <button name="assign" id="assign" class="btn btn-secondary btn-sm">ASSIGN ACCESS</button>
                                </a>
                                | <button name="import" id="import" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#importData">IMPORT</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col mx-auto">
                                <table class="table" style="width: 100%" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th id="checkbox" colspan="1"><input type="checkbox" id="check-all"></th>
                                            <th id="avatar" colspan="1" style="text-align:left"></th>
                                            <th id="name" colspan="1" style="text-align:left">Name</th>
                                            <th id="email" colspan="1" style="text-align:left">Email</th>
                                            <th id="role" colspan="1" style="text-align:center">Role</th>
                                            <th id="licenses" colspan="1" style="text-align:center">Licenses</th>
                                            <th id="Last Login" colspan="1">Last Login</th>
                                            <th id="options" colspan="1">Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr id="row_{{$user->id}}">
                                            <td colspan="1"><input type="checkbox" name="user[]" class="uid" value="{{$user->id}}"></td>
                                            <td colspan="1"><img class="avatar" style="max-width:50px; max-height:50px;" src="/uploads/avatars/{{$user->avatar}}"></td>
                                            <td colspan="1">{{$user->name}} </td>
                                            <td colspan="1">{{$user->email}} </td>
                                            <td colspan="1">{{$user->roles_string()}}</td>
                                            <td colspan="1" class="licenses-cell" data-user-id="{{$user->id}}">
                                                @php $pws = $user->packages_with_names(); @endphp
                                                @if(!empty($pws))
                                                    @foreach($pws as $p)
                                                        @php $title = implode(', ', $p['names']); @endphp
                                                        <span class="badge bg-secondary me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $title }}">{{ $p['acronym'] }}</span>
                                                    @endforeach
                                                @else
                                                    &nbsp;
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->last_login !== null) 
                                                    {{\Carbon\Carbon::parse($user->last_login)->diffForHumans()}} 
                                                    <br>{{\Carbon\Carbon::parse($user->last_login)->format('m-d-Y') }}
                                                @else 
                                                    never 
                                                @endif
                                            </td>
                                            <td colspan="1" style="text-align:center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-success user-edit-link" rel="{{$user->id}}" href="#">Edit</a>
                                                        @if($user->needs_update || $user->email_verified_at == null)
                                                        <a class="dropdown-item text-warning resend-link" href="{{url($path.'/backend/master-users/resend/'.$user->id)}}">Re-Send Activation</a>
                                                        @endif
                                                        <a class="dropdown-item text-danger user-delete" rel="{{$user->id}}" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle btn-multiple" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Bulk action (0) users 
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-danger delete-multiple" href="#" rel="">Delete</a>
                                                        <a class="dropdown-item text-default assignCurriculaBtn" href="#" >Assign to Curricula</a>
                                                        <a class="dropdown-item text-default assignTrainingBtn" href="#" >Assign to Training</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD USER MODAL CALL -->
    <div class="modal fade" id="userProfile" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ADD USER
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/users/user-add')
            </div>
        </div>
    </div>

    <!-- UPDATE PROFILE -->
    <div class="modal fade" id="userProfileEdit" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    UPDATE PROFILE
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body user_update">
                </div>
            </div>
        </div>
    </div>

    <!-- ASSIGN CURRICULA -->
    <div class="modal fade" id="assignCurricula" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ASSIGN TO CURRICULA
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body assign_curricula px-5">
                    @foreach($packages as $s)
                    @php
                        $activeSub = \App\Models\ActiveSubscriptions::where('org_id', auth()->user()->org_id)
                            ->where('product_id', $s->id)
                            ->where('category', 1)
                            ->first();
                        $available = $activeSub ? ($activeSub->total - $activeSub->used) : 0;
                    @endphp
                    @if($available > 0)
                    <div class="row">
                        <div class="col-md-1 text-right"><input type="checkbox" class="curricula-input" rel="{{$s->id}}"/></div>
                        <div class="col-md-9">{{$s->name}}</div>
                        <div class="col-md-2 text-end text-muted small">({{ $available }})</div>
                    </div>
                    @endif
                    @endforeach
                    <div class="row">
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-1">&nbsp;</div>
                        <br><div class="col-md-11"><button class="btn btn-sm btn-secondary save-selection">save</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ASSIGN TRAINING -->
    <div class="modal fade" id="assignSeries" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    ASSIGN TO TRAINING
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body assign_curricula px-5">
                    @foreach($trainings as $s)
                    @php
                        $activeSub = \App\Models\ActiveSubscriptions::where('org_id', auth()->user()->org_id)
                            ->where('product_id', $s->reference_id)
                            ->where('category', 7)
                            ->first();
                        $available = $activeSub ? ($activeSub->total - $activeSub->used) : 0;
                    @endphp
                    @if($available > 0)
                    <div class="row">
                        <div class="col-md-1 text-right"><input type="checkbox" class="training-input" rel="{{$s->reference_id}}"/></div>
                        <div class="col-md-9">{{$s->name}}</div>
                        <div class="col-md-2 text-end text-muted small">({{ $available }})</div>
                    </div>
                    @endif
                    @endforeach
                    <div class="row my-2">
                        <div class="col-md-1">&nbsp;</div>
                        <div class="col-md-11"><button class="btn btn-sm btn-primary save-selection-t">save</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSV IMPORT -->
        <div class="modal fade" id="importData" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        IMPORT
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body import">
                        <form method="post" action="" enctype="multipart/form-data"  id="upload-file-form">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control choose-option">
                                    <option value="-1">-- CHOOSE AN OPTION --</option>
                                    <option value="0">Import CSV File</option>
                                    <option value="1">Paste CSV Text</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 file-option mt-4" style="display: none">
                                <label>Upload CSV file:</label> <input type="file" name="csv" id="csv-file" />
                            </div>
                            <div class="col-md-12 text-option mt-4" style="display: none">
                                <label>Paste your CSV text here:</label>
                                <textarea class="form-control csv_text" name="csv_text" style="height: 300px" onkeydown="if(event.keyCode===9){var v=this.value,s=this.selectionStart,e=this.selectionEnd;this.value=v.substring(0, s)+'\t'+v.substring(e);this.selectionStart=this.selectionEnd=s+1;return false;}"></textarea>
                            </div>
                        </div>
                        </form>
                        <div class="row sample-data mt-4" style="display: none">
                            <div class="col-md-12">
                                SAMPLE DATA <span class="rows-snippet">(first 5 rows)</span>
                            </div>
                            <div class="col-md-12 result-table" style="overflow-y: auto">
                                <table class="table table-striped" style="width: 100%" id="resTable">
                                    <thead>
                                        <tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <input type="checkbox" name="ignore" class="ignore" /> Ignore first row
                            </div>
                        </div>
                        <div class="row sample-options mt-4" style="display: none">
                            <div class="col-md-12">
                                <h6>Choose action</h6>
                            </div>
                            <div class="col-md-12">
                                <select name="action" class="form-control option-select">
                                    <option value="0">--SELECT--</option>
                                    <option value="import">Import New Users</option>
                                    <option value="delete">Delete Existing Users</option>
                                    <option value="add">Add Existing Users to Curricula/Training</option>
                                    <option value="rem">Remove Existing Users from Curricula</option>
                                    <option value="org">Remove Existing Users from Organization</option>
                                </select>
                            </div>
                            <div class="col-md-12 curricula-container mt-4" style="display: none">
                                <h6>Select Option</h6>
                                <select name="curricula" class="form-control curricula">
                                    <option value="hinted">As hinted by column selection</option>
                                    @foreach(\App\Models\Products::where('category', 7)->get() as $t)
                                    <option value="t-{{$t->id}}">Training: {{$t->name}}</option>
                                    @endforeach
                                    @foreach(\App\Models\Products::where('category', 1)->get() as $p)
                                    <option value="p-{{$p->id}}">Curricula: {{$p->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 btn-continue-container mt-4" style="display: none">
                                <button name="continue" class="btn btn-secondary btn-sm btn-continue">Continue</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 btn-sample-container mt-4" style="display: none">
                                <button name="continue" class="btn btn-danger btn-sm btn-reset">Reset</button>
                                <button name="continue" class="btn btn-secondary btn-sm btn-sample float-right">Continue</button>

                            </div>
                        </div>
                        <div class="row processing" style="display:none">
                            <div class="col-md-12 text-center">
                                Please wait while the action is being processed.
                                <br>This can take several minutes.
                            </div>
                            <div class="col-md-12 text-center">
                                <div class="lds-dual-ring"></div>
                            </div>
                        </div>
                        <div class="row result" style="display:none">
                            <div class="col-md-12 text-center"> </div>
                            <div class="col-md-12 text-center"><button class="btn btn-secondary btn-sm btn-done">OK</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        .dz-details, .dz-success-mark, .dz-error-mark, .dz-filename {
            display:none;
        }
        .dz-image img {
            max-width: 200px;
        }
        .needsclick {
            cursor: pointer;
        }
        .blue{
            background-color: #265a8e;
            color: white;
            font-size: small;
        }
        .tinyImg{
            width: 45px;
            height: 45px;
            overflow: hidden;
        }
        .lds-dual-ring {
            display: inline-block;
            width: 80px;
            height: 80px;
        }
        .lds-dual-ring:after {
            content: " ";
            display: block;
            width: 64px;
            height: 64px;
            margin: 8px;
            border-radius: 50%;
            border: 6px solid #cef;
            border-color: #cef transparent #cef transparent;
            animation: lds-dual-ring 1.2s linear infinite;
        }
        @keyframes lds-dual-ring {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        .modal-header {
            background-color:#f7f7f7;
        }
    </style>

    <script src="{{url("js/dropzone/dist/dropzone.js")}}"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            var t = $('#usersTable').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pagingType: 'full_numbers',
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']]
            });
            var currentPage = 0;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if (urlParams.has('page')){
                currentPage = urlParams.get('page') * 1; //force number
            }
            $("#import").on("click", function(e){
                e.preventDefault();
                $("#importData").modal("show");
            });
            $(".btn-done").on('click', function(e){
                e.preventDefault();
                location.reload();
            });

            //FILE IMPORT OPTIONS
            var _action = "{{url('/backend/users/file_upload')}}"; //we start with upload
            $(".choose-option").on("change", function(){
                $('.file-option').css({'display': 'none'});
                $('.text-option').css({'display': 'none'});
                $('.sample-options').css({'display': 'none'});
                console.log($(this).val());
                if($(this).val() == 0){
                    $('.file-option').css({'display': 'block'});
                }
                if($(this).val() == 1){
                    $('.text-option').css({'display': 'block'});
                }
                if($(this).val() != -1){
                    $('.btn-continue-container').css({'display': 'block'});
                }
            });

            $('.ignore').on('change', function(){
                if($(this).prop('checked')){
                    $("#resTable tbody tr").first().fadeOut();
                }else{
                    $("#resTable tbody tr").first().fadeIn();
                }
            })

            $('.option-select').on('change', function(){

                if($(this).val() == 'add' || $(this).val() == 'rem'){
                    $('.curricula-container').css({'display': 'block'});
                }else{
                    $('.curricula-container').css({'display': 'none'});
                }
            })

            $('.btn-sample').on('click', function(e){
                e.preventDefault();
                $(".sample-data").css({'display': 'none'});
                $(".sample-options").css({'display': 'none'});
                $(".btn-sample-container").css({'display': 'none'});
                $(".choose-option").css({'display': 'none'});
                col_selection = [];
                $("#resTable th select").each(function(){
                    col_selection.push($(this).val());
                });
                $(".processing").css({'display': 'flex'});
                $.ajax({
                    url:  "{{url('/backend/users/action_org')}}",
                    type: 'post',
                    data: {
                        action: $('.option-select').val(),
                        ignore: $('.ignore').prop('checked'),
                        curricula: $(".curricula").val(),
                        columns: col_selection,
                        source: imported_resource,
                        organization: <?=auth()->user()->org_id?>
                    },
                    success: function(rsp){
                        if(!rsp.success){
                            alert(rsp.message);
                            //return
                            $(".processing").css({'display': 'none'});
                            $(".sample-data").css({'display': 'block'});
                            $(".sample-options").css({'display': 'block'});
                            $(".btn-sample-container").css({'display': 'block'});
                            $(".choose-option").css({'display': 'block'});
                            return;
                        }
                        let ignored = '';
                        let errors = '';
                        if(rsp.ignored.length){
                            for(i of rsp.ignored){
                                ignored += `Row: ${i[0]}, Reason: ${i[1]}<br/>`
                            }
                        }
                        if(rsp.errors.length){
                            for(i of rsp.errors){
                                errors += `Row: ${i[0]}, Reason: ${i[1]}<br/>`
                            }
                        }
                            $(".processing").css({'display': 'none'});
                            $(".result div").first().html(`
                            <h5>Processed: ${rsp.processed}</h5>
                            <h5>Ignored: ${rsp.ignored.length}</h5>
                            <div class="text-info">${ignored}</div>
                            <h5>Errors: ${rsp.errors.length}</h5>
                            <div class="text-danger">${errors}</div>
                            `);
                            $(".result").css({'display': 'flex'})
                    },
                    fail: function(rsp){
                        alert("Request Failed!");
                    }
                });
            });

            $('.btn-reset').on('click', function(e){
                e.preventDefault();
                $("#upload-file-form").trigger('reset');
                $('.file-option').css({'display': 'none'});
                $('.text-option').css({'display': 'none'});
                $(".sample-data").css({'display': 'none'});
                $(".sample-options").css({'display': 'none'});
                $(".btn-sample-container").css({'display': 'none'});
                $("#resTable thead tr").find('th').remove();
                $("#resTable tbody tr").remove();
                for(x = 0; x < options.length; x++){
                    options[x].used = 0;
                }
            });

            var imported_resource = null;
            let options = [
                    {value: -1, text: '--SELECT--', count: 1000, used: 0},
                    {value: 0, text: 'Ignore This Column', count: 1000, used: 0},
                    {value: 1, text: 'First Name', count: 1, used: 0},
                    {value: 2, text: 'Last Name', count: 1, used: 0},
                    {value: 3, text: 'Email', count: 1, used: 0},
                    {value: 4, text: 'Curricula/Training', count: 1, used: 0},
                ];
        
            $('.btn-continue').on("click", function(e){
                e.preventDefault();
                var fd = new FormData();
                if($("#csv-file")[0].files[0]){
                    var files = $("#csv-file")[0].files[0];
                    fd.append('file', files);
                }
                fd.append('text', $('.csv_text').val());
                const col_header = `<th>
                                        <select class="form-control col-select text-danger" name="col[]">
                                        </select>
                                    </th>`;
                $.ajax({
                    url: _action,
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(rsp){
                        if(!rsp.success){
                            if($(".choose-option").val() == 0){
                                alert("The file uploaded could not be read/converted. Please try again!");
                            }else{
                                alert("The text could not be read/converted. Please try again!");
                            }
                            return;
                        }
                        imported_resource = rsp.res;
                        $('.rows-snippet').text(`(showing first ${rsp.sample.length} out of ${rsp.total} rows parsed)`);
                        //all good, populate the table with sample data
                        let rowsCount = 1;
                        let hasHeader = false;
                        $('.file-option').css({'display': 'none'});
                        $('.text-option').css({'display': 'none'});
                        $("#resTable thead tr").append(` <th>#</th>`);
                        for(let row of rsp.sample){
                            let tdRow = `<tr><td>${rowsCount}</td>`;
                            for(col of row){
                                if(!hasHeader){
                                    $("#resTable thead tr").append(col_header);
                                }
                                tdRow += `<td>${col}</td>`;
                            }
                            tdRow += `</tr>`;
                            $("#resTable tbody").append(tdRow);
                            rowsCount++;
                            hasHeader = true;
                        }
                        for(option of options){
                            $("#resTable thead tr th select").append(`<option value="${option.value}">${option.text}</option>`);
                        }
                        //do some auto hinting to find out the email column
                        sample_row = rsp.sample[0];
                        if(rsp.sample.length > 1){
                            sample_row = rsp.sample[1];
                        }
                        for(x = 0; x < sample_row.length; x++){
                            if(isEmail(sample_row[x].trim())){
                                $("#resTable th select").eq(x).val(3);
                                $("#resTable th select").eq(x).removeClass('text-danger');
                                options[4].used++;
                                refactorSelects();
                            }
                        }
                        $(".sample-data").css({'display': 'block'});
                        $(".sample-options").css({'display': 'block'});
                        $(".btn-continue-container").css({'display': 'none'});
                        $(".btn-sample-container").css({'display': 'block'});
                    },
                    fail: function(){
                        alert("Failed!")
                    }
                })
            })

            var previous;
            $('body').on('focus', '.col-select', function(){
                previous = $(this).val();
            });

            $('body').on('change', '.col-select', function(){
                if($(this).val() == -1){
                    $(this).addClass('text-danger');
                }else{
                    $(this).removeClass('text-danger');
                }
                //modifiy the used property
                for(x = 0; x < options.length; x++){
                    if(options[x].value == $(this).val()){
                        options[x].used++;
                    }
                    if(options[x].value == previous){
                        options[x].used--;
                    }
                }
                previous = $(this).val();
                refactorSelects();
            });

            function refactorSelects(){
                used = 0;
                available = 0;
                for(option of options){
                    if(option.value > 0 ){
                        used += option.used;
                        available += option.count;
                    }
                    if(option.used >= option.count){
                        //we need to remove this option from the selects if it's not already selected
                        $('.col-select').each(function(){
                            if($(this).val() != option.value){
                                $(this).find("option[value='"+option.value+"']").first().remove();
                            }
                        })
                    }else{
                        //we need to add the option back if it was removed previously
                        $('.col-select').each(function(){
                            is_option = $(this).find("option[value='"+option.value+"']").length;
                            if(!is_option){
                                $(this).append(`<option value="${option.value}">${option.text}</option>`);
                            }
                        })
                    }
                }
                //if all options are used, switch to ignore field all unselected
                if(used >= available){
                    $('.col-select').each(function(){
                        if($(this).val() == -1){
                            $(this).val(0);
                            $(this).removeClass('text-danger');
                        }
                    });
                }
            }

            t.page(currentPage).draw(false);
            $(".assignCurriculaBtn").on('click', function (e) {
                e.preventDefault();
                if (_count == 0) {
                    alert("Please select user(s).");
                    return;
                }
                $("#assignCurricula").modal('show');
            })

            $(".assignTrainingBtn").on('click', function (e) {
                e.preventDefault();
                if (_count == 0) {
                    alert("Please select user(s).");
                    return;
                }
                $("#assignSeries").modal('show');
            })

            $(".save-selection").on('click', function (e) {
                e.preventDefault();
                //collect user ids
                users = [];
                $('.uid').each(function () {
                    if ($(this).prop('checked')) {
                        users.push($(this).val());
                    }
                });
                //collect selection
                curriculas = [];
                $('.curricula-input').each(function () {
                    if ($(this).prop('checked')) {
                        curriculas.push($(this).attr('rel'));
                    }
                })
                if (!users.length) {
                    alert("No users selected!");
                    return;
                }
                if (!curriculas.length) {
                    alert("No curriculas selected!");
                    return;
                }
                $.ajax({
                    url: "{{url($path.'/add_multiple_packages')}}",
                    type: 'post',
                    data: {
                        users: users,
                        curriculas: curriculas,
                        type: 0
                    },
                    success: function (result) {
                        if (result.success) {
                            let messageShown = false;
                            if (result.added && result.added > 0) {
                                alert("Users added!");
                                messageShown = true;
                            }
                            if (result.skipped && result.skipped.length) {
                                let _txt = 'The following subscriptions were not added to users because you have less licenses left than users selected: \n';
                                for (item of result.skipped) {
                                    _txt += `${item} \n`;
                                }
                                alert(_txt);
                                messageShown = true;
                            }
                            if ((!result.added || result.added === 0) && result.already_added && result.already_added.length) {
                                alert('Selected users already have this package assigned.');
                                messageShown = true;
                            }
                            if (!messageShown) {
                                alert('No changes made.');
                            }
                            // Refresh licenses for affected users
                            refreshLicenses(users);
                            // Clear selections and update count
                            $('.curricula-input').prop('checked', false);
                            $('.uid').prop('checked', false);
                            reset_count();
                            $("#assignCurricula").modal('hide');
                        }
                    },
                    fail: function () {
                        alert('Failed to send email.');
                    }
                });
            })

            $(".save-selection-t").on('click', function (e) {
                e.preventDefault();
                //collect user ids
                users = [];
                $('.uid').each(function () {
                    if ($(this).prop('checked')) {
                        users.push($(this).val());
                    }
                });
                //collect selection
                curriculas = [];
                $('.training-input').each(function () {
                    if ($(this).prop('checked')) {
                        curriculas.push($(this).attr('rel'));
                    }
                })
                if (!users.length) {
                    alert("No users selected!");
                    return;
                }
                if (!curriculas.length) {
                    alert("No trainings selected!");
                    return;
                }
                $.ajax({
                    url: "{{url($path.'/add_multiple_packages')}}",
                    type: 'post',
                    data: {
                        users: users,
                        curriculas: curriculas,
                        type: 4
                    },
                    success: function (result) {
                        if (result.success) {
                            let messageShown = false;
                            if (result.added && result.added > 0) {
                                alert("Users added!");
                                messageShown = true;
                            }
                            if (result.skipped && result.skipped.length) {
                                let _txt = 'The following subscriptions were not added to users because you have less licenses left than users selected: \n';
                                for (item of result.skipped) {
                                    _txt += `${item} \n`;
                                }
                                alert(_txt);
                                messageShown = true;
                            }
                            if ((!result.added || result.added === 0) && result.already_added && result.already_added.length) {
                                alert('Selected users already have this training assigned.');
                                messageShown = true;
                            }
                            if (!messageShown) {
                                alert('No changes made.');
                            }
                            // Refresh licenses for affected users
                            refreshLicenses(users);
                            // Clear selections and update count
                            $('.training-input').prop('checked', false);
                            $('.uid').prop('checked', false);
                            reset_count();
                            $("#assignSeries").modal('hide');
                        }
                    },
                    fail: function () {
                        alert('Failed to send email.');
                    }
                });
            })

            $("#usersTable").on('page.dt', function () {
                var info = t.page.info();
                currentPage = info;
                console.log(currentPage.page);
                urlParams.set('page', currentPage.page);
                history.pushState(null, null, "?" + urlParams.toString());
            });

            //Moveable Modals
            $("#userProfile").draggable({
                handle: ".modal-header"
            });
            $("#userProfileEdit").draggable({
                handle: ".modal-header"
            });
            $("#assignCurricula").draggable({
                handle: ".modal-header"
            });
            $("#assignSeries").draggable({
                handle: ".modal-header"
            });
            $("#importData").draggable({
                handle: ".modal-header"
            });

            //resend activation email
            $("body").on('click', '.resend-link', function (e) {
                $(this).blur();
                e.preventDefault();
                url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (result) {
                        if (result.success == false) {
                            alert(show_result(result.message));
                            return;
                        } else {
                            alert("Resent activation email.");
                        }
                    },
                    fail: function () {
                        alert('Failed to send email.');
                    }
                });
                return false;
            });

            //user delete
            $("body").on("click", '.user-delete', function (e) {
                e.preventDefault();
                id = $(this).attr('rel');
                delete_users(id);
            });

            //delete multiple
            $("body").on("click", '.delete-multiple', function (e) {
                e.preventDefault();
                let _users_s = [];
                $('.uid').each(function () {
                    if ($(this).prop("checked")) {
                        _count++;
                        _users_s.push($(this).val());
                    }
                });
                if (!_users_s.length) {
                    alert("No users selected!");
                    return;
                }
                delete_users(_users_s.join(","));
            });

            //user edit
            $('body').on('click', '.user-edit-link', function (e) {
                e.preventDefault();
                $.get("{{url($path.'/user')}}/" + $(this).attr('rel'), function (data) {
                    $(".user_update").html(data);
                    $("#userProfileEdit").modal('show');
                });
            });

            //user roles switch
            switch_roles();
            $('.organization-user').on('change', function () {
                switch_roles();
            });

            // todo @adiscoarta there is an error in this code somewhere, I think it is missing a closing } but I can't find where.
            var myDropzone = new Dropzone("div#imageUploadWide", {
                url: base_url + '/upload', // Set the url
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                uploadMultiple: false,
                maxFiles: 100,
                thumbnailWidth: $("div#imageUploadWide").width(),
                thumbnailHeight: $("div#imageUploadWide").height(),
                autoProcessQueue: true,
                acceptedFile: "image/*",
                autoQueue: true, // Make sure the files aren't queued until manually added
                previewsContainer: ".preview-container", // Define the container to display the previews
                clickable: ".needsclick", // Define the element that should be used as click trigger to select files.
                complete: function (file) {
                    console.log(file);
                    $('.upload-img').hide();
                    files = $('.preview-container').find('.dz-preview');
                    console.log(files.length);
                    if (files.length > 1) {
                        $('.preview-container').find('.dz-preview').first().remove();
                    }
                    $('.preview-container').find('.dz-preview').first().find('img').attr('src', file.dataURL);
                    console.log("COMPLETE");
                },
                    init: function () {
                        console.log("INIT");
                        this.on("success", function (file, serverResponse) {
                            if (serverResponse.success) {
                                $('.actions-image').show();
                                $('.file-name').val(serverResponse.file);
                            } else {
                                alert(serverResponse.reason);
                            }
                        });
                    }
                });

                //switch organization (correct URL construction)
                $("body").on("change", '.view-users', function (e) {
                    e.preventDefault();
                    var orgId = $(this).val();
                    // Build URL safely; assumes this page already under /{path}/users
                    window.location.href = "{{ url($path.'/dashboard/users') }}?org_id=" + encodeURIComponent(orgId);
                });

                //Check all function from header
                //Adi we need to add a safety feature where the logged in user can't be deleted.
                $("body").on("change", "#check-all", function (e) {
                    e.preventDefault();
                    $('.uid').prop("checked", $(this).prop("checked"));
                    reset_count();
                });
                $("body").on("change", ".uid", function () {
                    reset_count();
                });
            });

            var _count = 0;
            function reset_count() {
                _count = 0;
                let _users = [];
                $('.uid').each(function () {
                    if ($(this).prop("checked")) {
                        _count++;
                        _users.push($(this).val());
                    }
                });
                $(".btn-multiple").text("Bulk action (" + _count + ") users");
                $('.user_list').val(_users.join("|"));
            }

            function checkAssign() {
                if (_count == 0) {
                    alert('No users selected!');
                    return false;
                }
                if (confirm('Are you sure you want to move this user to the selected organization?')) {
                    return true;
                }
                return false;
            }

            // initialize Bootstrap tooltips for package acronym badges
            try {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            } catch (e) {
                // bootstrap not available or JS error; silently ignore
                console.warn('Tooltip initialization failed', e);
            }

            //show roles depending on organization. Us(internal) or School
            function switch_roles() {
                let _oid = $('.organization-user option:selected').attr('rel');
                let _current = $('.user-roles').val();
                let switch_type = false;
                $('.user-roles option').each(function () {
                    if ($(this).attr('rel') != _oid) {
                        if ($(this).attr('value') == _current)
                            switch_type = true;
                        $(this).css({display: 'none'});
                    } else {
                        if (switch_type) {
                            $('.user-roles').val($(this).attr('value'));
                            switch_type = false;
                        }
                        $(this).css({display: 'inline-block'});
                    }
                });
                //if the loop is over with switch_type = true we flip the value to the first we find
                if (switch_type == true) {
                    $('.user-roles option').each(function () {
                        if ($(this).attr('rel') == _oid) {
                            $('.user-roles').val($(this).attr('value'));
                            switch_type = false;
                        }

                    });
                }
            }

            function addUser() {
                url = $("#headUserAdd").attr('action');
                data = $("#headUserAdd").serialize();
                console.log(data);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function (result) {
                        if (result.success == false) {
                            let text = [];
                            for (msg in result.message) {
                                for (err in result.message[msg]) {
                                    text.push(result.message[msg][err]);
                                }
                            }
                            alert(text.join('\n'));
                        } else {
                            alert('User Added');
                            location.reload();
                        }

                    }
                });
                return false;
            }

            function checkUserUpdate() {
                url = $("#userFormEdit").attr('action');
                data = $("#userFormEdit").serialize();
                console.log(data);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function (result) {
                        if (result.success == false) {
                            let text = [];
                            for (msg in result.message) {
                                for (err in result.message[msg]) {
                                    text.push(result.message[msg][err]);
                                }
                            }
                            alert(text.join('\n'));
                        } else {
                            alert('User Updated');
                            location.reload();
                        }
                    }
                });
                return false;
            }

            function checkUserEdit() {
                return false;
            }

            function delete_users(user_list) {
                if (!confirm("Are you sure you want to delete this user(s)?"))
                    return false;
                $.ajax({
                    url: '{{url($path.'/users/delete_user/')}}/' + user_list,
                    type: 'DELETE',
                    success: function (result) {
                        alert(result.reason);
                        for (x = 0; x < result.deleted.length; x++) {
                            $("#row_" + result.deleted[x]).remove();
                        }
                        document.location = document.location;
                    }
                });
            }

            function refreshLicenses(userIds) {
                if (!userIds || !userIds.length) return;
                $.ajax({
                    url: "{{ url($path.'/users/licenses') }}",
                    type: 'post',
                    data: { users: userIds },
                    success: function(resp){
                        if(resp && resp.success && resp.licenses){
                            Object.keys(resp.licenses).forEach(function(uid){
                                var html = resp.licenses[uid];
                                var $cell = $(".licenses-cell[data-user-id='"+uid+"']");
                                if($cell.length){
                                    $cell.html(html);
                                }
                            });
                            // re-init tooltips for new badges
                            try {
                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl);
                                });
                            } catch (e) { console.warn('Tooltip re-init failed', e); }
                        }
                    }
                });
            }

            function isEmail(email) {
                const re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                return re.test(email);
            }
    </script>
</section>
@endsection




