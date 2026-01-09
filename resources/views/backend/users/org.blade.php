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
                <a href="{{ url($path.'/backend/master-orgs') }}"> Master Orgs</a> |
                <a href="{{ url($path.'/backend/master-users?org_id='.$org->id) }}"> Master Users</a> |
                <span style="font-weight: bold;color:#9acd57">{{$org->name}} Profile</span>
            </div>
        </div>

        <!-- CARDS ROW  -->
        <div class="row">
            <!--CLIENT CARD -->
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-9"><b>CLIENT</b></div>
                            <div class="col-2 text-right">
                                <button type="button" class="btn btn-secondary btn-sm"  data-bs-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#orgUpdate" title="Edit">
                                    EDIT
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- name -->
                        <div class="row no-gutters align-items-center">
                            <div class="col-10"><b>{{$org->name}}</b></div>
                        </div>
                        <!-- address -->
                        <div class="row no-gutters align-items-center pb-2">
                            <div class="col-10">{{$org->address}} 
                                <br>
                                @if(empty($org->city))@else{{$org->city}},@endif {{$org->state}} {{$org->zip}}
                                @if(!empty($org->country))<br>{{ get_country_name($org->country) }}@endif
                            </div>
                        </div>
                        <!-- member since -->
                        <div class="row no-gutters">
                            <div class="col" style="color:#acbdcd; font-variant: small-caps;">member since</div>
                        </div>
                        <div class="row no-gutters align-items-center pb-2">
                            <div class="col-10">{{ $org->created_at->format('M. d, Y') }}</div>
                        </div>
                        <!-- URL -->
                        <div class="row no-gutters">
                            <div class="col" style="color:#acbdcd; font-variant: small-caps;">website url</div>
                        </div>
                        <div class="row no-gutters align-items-center pb-2">
                            <div class="col-10">
                                <a href="{{$org->url}}" target="_blank">{{$org->url}}</a>
                                @if(empty($org->email_match))@else<br>@ {{$org->email_match}}@endif
                            </div>
                        </div>
                        <!-- PEDAGOGY -->
                        <div class="row no-gutters">
                            <div class="col" style="color:#acbdcd; font-variant: small-caps;">Pedagogy</div>
                        </div>
                        <div class="row no-gutters align-items-center pb-2">
                            <div class="col-10">{{$org->pedagogy}}</div>
                        </div>
                        <!-- GRADES -->
                        <div class="row no-gutters">
                            <div class="col" style="color:#acbdcd; font-variant: small-caps;">Grades</div>
                        </div>
                        <div class="row no-gutters align-items-center pb-2">
                            <div class="col-10">{{$org->grades}}</div>
                        </div>
                        <!-- STUDENT COUNT -->
                        <div class="row no-gutters">
                            <div class="col" style="color:#acbdcd; font-variant: small-caps;">Student Count</div>
                        </div>
                        <div class="row no-gutters align-items-center pb-2">
                            <div class="col-10">{{$org->size}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--PAID CARDS -->
            <div class="col-lg-7 col-sm-12">
                <!--PAID CURRICULUM -->
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-9"><b>PAID CURRICULA</b></div>
                            <div class="col-2 text-right">
                                <button name="add_license" id="add_license" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addLicense">
                                    ADD
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- PAID -->
                        <div class="row no-gutters">
                            <div class="col-12">
                                <table class="table table-striped" style="width:100%" id="cTable">
                                    <thead>
                                        <tr>
                                            <th id="checkbox"   colspan="1"><input type="checkbox" id="check-all-lic"></th>
                                            <th id="licenses"   colspan="1" style="color:#acbdcd;">LICENSES</th>
                                            <th id="total"      colspan="1" style="color:#acbdcd; text-align:center;">TOTAL</th>
                                            <th id="used"       colspan="1" style="color:#acbdcd; text-align:center;">USED</th>
                                            <th id="status"     colspan="1" style="color:#acbdcd; text-align:center;">STATUS</th>
                                            <th id="edit"       colspan="1" style="color:#acbdcd; text-align:center;">EDIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($ac_subs))
                                            @foreach($ac_subs->sortBy('id') as $s)
                                            <tr id="row_{{$s->id}}">
                                                <td colspan="1"><input type="checkbox" name="license[]" class="lid" value="{{$s->id}}"></td>
                                                <td>{{ \App\Models\Products::where('category', 1)->find($s->product_id)->name ?? 'error!'}}</td>
                                                <td style="text-align:center;">{{$s->total}}</td>
                                                <td style="text-align:center;">{{$s->used}}</td>
                                                <td style="text-align:center;">
                                                    @if($s->status == 2)
                                                        active
                                                    @elseif($s->status == 3)
                                                        canceled
                                                    @elseif($s->status == 4)
                                                        reviewing
                                                    @elseif($s->status == 5)
                                                        reviewed
                                                    @else
                                                        n/a
                                                    @endif
                                                </td>
                                                <td colspan="1" style="text-align:center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                        <a class="dropdown-item text-success lic-edit-link" rel="{{$s->id}}" href="#">Edit</a>
                                                        <a class="dropdown-item text-danger lic-delete" rel="{{$s->id}}" href="#">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-multiple" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Selected (0)
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-danger lic-delete-multiple" href="#" rel="">Delete</a>
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

                <!--PAID TRAININGS -->
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row no-gutters">
                            <div class="col-9"><b>PAID TRAININGS</b></div>
                            <div class="col-2 text-right">
                                <button name="add_training" id="add_training" class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addTraining">
                                    ADD
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- PAID -->
                        <div class="row no-gutters">
                            <div class="col-12">
                                <table class="table table-striped" style="width:100%" id="tTable">
                                    <thead>
                                        <tr>
                                            <th id="checkbox"   colspan="1"><input type="checkbox" id="check-all-train"></th>
                                            <th id="email"      colspan="1" style="color:#acbdcd;">TRAININGS</th>
                                            <th id="name"       colspan="1" style="color:#acbdcd; text-align:center;">TOTAL</th>
                                            <th id="options"    colspan="1" style="color:#acbdcd; text-align:center;">USED</th>
                                            <th id="edit"       colspan="1" style="color:#acbdcd; text-align:center;">EDIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($ac_trainings))
                                        @foreach($ac_trainings->sortBy('id') as $t)
                                            <tr id="row_{{$t->id}}">
                                                <td colspan="1"><input type="checkbox" name="training[]" class="tid" value="{{$t->id}}"></td>
                                                <td>{{ \App\Models\Products::find($t->product_id)->name }} </td>
                                                <td style="text-align:center;">{{$t->total}}</td>
                                                <td style="text-align:center;">{{$t->used}}</td>
                                                <td colspan="1" style="text-align:center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                        <a class="dropdown-item text-success tra-edit-link" rel="{{$t->id}}" href="#">Edit</a>
                                                        <a class="dropdown-item text-danger tra-delete" rel="{{$t->id}}" href="#">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-multiple" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Selected (0)
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-danger train-delete-multiple" href="#" rel="">Delete</a>
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

    <!-- UPDATE ORG -->
    <div class="modal fade" id="orgUpdate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <b>MASTER:&nbsp;</b> ORG UPDATE
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/users/org-update')
            </div>
        </div>
    </div>
    <!-- ADD LICENSES -->
    <div class="modal fade" id="addLicense" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('backend/users/org-lic-add')
            </div>
        </div>
    </div>
    <!-- ADD TRAINING -->
    <div class="modal fade" id="addTraining" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <b>MASTER:&nbsp;</b> ADD TRAINING
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    @include('backend/users/org-train-add')
                </div>
            </div>
        </div>
    </div>
    <!-- UPDATE LICENSES -->
    <div class="modal fade" id="licUpdate" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @if(isset($ac_subs))
                    @include('backend/users/org-lic-update')
                @endif
            </div>
        </div>
    </div>
    <!-- UPDATE TRAININGS -->
    <div class="modal fade" id="trainingUpdate" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('backend/users/org-train-update')
            </div>
        </div>
    </div>


    <style>
        .modal-header {
            background-color:#f7f7f7;
        }
        .table{
            table-layout:fixed;
        }
    </style>

    <script>
        $(document).ready(function(){
            //lic delete
            $("body").on("click", '.lic-delete', function(e){
                e.preventDefault();
                id = $(this).attr('rel');
                lic_delete(id);
            });
            function lic_delete(id){
                if(!confirm("Are you sure you want to delete this license?"))
                    return false;
                $.ajax({
                    url: '{{url($path.'/backend/lic_delete/')}}/'+id,
                    type: 'DELETE',
                    success: function(result) {
                        alert(result.reason);
                        for(x = 0; x < result.deleted.length; x++){
                            $("#row_"+result.deleted[x]).remove();
                        }
                    }
                });
            }
            //training delete
            $("body").on("click", '.training-delete', function(e){
                e.preventDefault();
                id = $(this).attr('rel');
                training_delete(id);
            });
            function training_delete(){
                if(!confirm("Are you sure you want to delete this training?"))
                    return false;
                $.ajax({
                    url: '{{url($path.'/backend/training_delete/')}}/',
                    type: 'DELETE',
                    success: function(result) {
                        alert(result.reason);
                        for(x = 0; x < result.deleted.length; x++){
                            $("#row_"+result.deleted[x]).remove();
                        }
                    }
                });
            }

            //UPDATE LICENSES
            //call update modal
            $('body').on('click', '.lic-edit-link', function(e){
                e.preventDefault();
                $.get( "{{url($path.'/backend/get_lic')}}?s_id="+$(this).attr('rel'), function( data ) {
                    $(".qty_update").val( data.lic.total );
                    $(".lic_update").val( data.lic.id );
                    $(".status_update").val( data.lic.status );
                    $("#licUpdate").modal('show');
                });
            });
            //pass data
            function checkLicUpdate(){
                console.log('something');
                return false;
                frmData = $("#licUpdate").serialize();
                url = $("#licUpdate").attr('action');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: frmData,
                    success: function(result) {
                    if(result.success == false){
                        alert(show_result(result.message));
                        return;
                    }else{
                        alert("License Updated!");
                        location.reload();
                    }
                    },
                    fail: function(){
                        alert('Failed submitting this info!');
                    }
                });
                return false;
            }

            //UPDATE TRAINING
            //call update modal
            $('body').on('click', '.tra-edit-link', function(e){
                e.preventDefault();
                $.get( "{{url($path.'/backend/get_training')}}?t_id="+$(this).attr('rel'), function( data ) {
                    $(".qty_update").val( data.training.total );
                    $(".training_update").val( data.training.id );
                    $(".status_update").val( data.training.status );
                    $("#trainingUpdate").modal('show');
                });
            });
            //pass data
            function checkTrainingUpdate(){
                console.log('something');
                return false;
                frmData = $("#trainingUpdate").serialize();
                url = $("#trainingUpdate").attr('action');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: frmData,
                    success: function(result) {
                    if(result.success == false){
                        alert(show_result(result.message));
                        return;
                    }else{
                        alert("Training Updated!");
                        location.reload();
                    }
                    },
                    fail: function(){
                        alert('Failed submitting this info!');
                    }
                });
                return false;
            }

            //Moveable Modals
            $("#licUpdate").draggable({
                handle: ".modal-header"
            });
            $("#trainingUpdate").draggable({
                handle: ".modal-header"
            });
        });

        var c = $('#cTable').DataTable({
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            pagingType: 'full_numbers'
        });
        var t = $('#tTable').DataTable({
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            pagingType: 'full_numbers'
        });
    </script>
</section>
@endsection