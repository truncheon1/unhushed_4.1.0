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
                <a href="{{ url($path.'/backend/master-users?org_id='.$user->org_id) }}"> Master Users</a> |
                <a href="{{ url($path.'/backend/master-orgs') }}"> Master Orgs</a> |
                <span style="font-weight: bold;color:#9acd57">{{$user->name}} Profile</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row">
            <div class="col-10">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-6"><b>USER PROFILE</b></div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary btn-sm"  data-bs-toggle="modal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#orgUpdate" title="Edit">
                                    EDIT
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-5 m-2">
                                <!-- update profile pic -->
                                <div class="photoUpdateBox">
                                    <img class="avatar" src="/uploads/avatars/{{ $user->avatar }}">
                                    <!-- Modal trigger -->
                                    <button type="submit"  class="btn" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#updateProfile">
                                        <img class="photoUpdate" src="/img/icons/camera.png">
                                    </button>
                                </div>
                                <br>
                                <!-- dates -->
                                <p><span style="color:#acbdcd; font-variant: small-caps;">member since:</span> {{ $user->created_at->format('M d, Y') }}</p>
                                <p><span style="color:#acbdcd; font-variant: small-caps;">last login:</span>
                                    @if($user->last_login !== null)
                                        {{\Carbon\Carbon::parse($user->last_login)->format('M d, Y') }}
                                    @else 
                                        never 
                                    @endif
                                </p>
                            </div>
                            <div class="col-6 p-2">
                                <!-- name -->
                                <p><span style="color:#acbdcd; font-variant: small-caps;">name:</span> {{$user->name}}</p>
                                <!-- email -->
                                <p><span style="color:#acbdcd; font-variant: small-caps;">email:</span> {{$user->email}}</p>
                                <!-- org -->
                                <p><span style="color:#acbdcd; font-variant: small-caps;">organisation:</span>
                                    @if($user->org_id >0)
                                     {{ \App\Models\Organizations::find($user->org_id)->name }} 
                                    [ <a href="{{ url($path.'/backend/org-profile/'.$user->org_id) }}"> {{$user->org_id}}</a> ]
                                    @else n/a
                                    @endif
                                </p>                        
                                <!-- roles -->
                                <p><span style="color:#acbdcd; font-variant: small-caps;">roles:</span> {{$user->roles_string()}}</p>
                                <!-- address -->
                                <div style="color:#acbdcd; font-variant: small-caps;">address:</div>
                                <div style="padding-left:20px">
                                    @php
                                        $addr = \App\Models\UserAddress::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                                    @endphp
                                    @if($addr)
                                        {{ $addr->street ?? $addr->api_address1 ?? ' ' }}
                                        <br>{{ $addr->city ?? $addr->api_city ?? '' }}, {{ $addr->api_state ?? '' }} {{ $addr->zip ?? $addr->api_zip5 ?? '' }}
                                        <br>{{ $addr->name ?? '' }}
                                    @else
                                        No address on file
                                    @endif
                                </div>
                            </div>
                            <div class="col-10 m-2">
                                <!-- licenses -->
                                <div style="color:#acbdcd; font-variant: small-caps;">licenses:</div>
                                <div style="padding-left:20px">
                                    @if(isset($packages))
                                    <table class="table table-borderless" style="width: 100%" id="licTable">
                                        <tbody>
                                            @foreach($packages->where('category', 1)->sortBy('id') as $p)
                                                <tr id="row_{{$p->id}}">
                                                    <td class="text-primary">{{ \App\Models\Products::find($p->product_id)->name ?? ''}} </td>
                                                </tr>
                                            @endforeach
                                            @foreach($packages->where('category', 7)->sortBy('id') as $t)
                                                <tr id="row_{{$p->id}}">
                                                    <td class="text-primary">{{ \App\Models\Products::find($t->product_id)->name ?? ''}} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Profile-->
<div class="modal fade" id="updateProfile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <b>PROFILE:&nbsp;</b> UPDATE
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="{{url($path.'/dashboard')}}" method="POST" role="form">
                    <input type="hidden" name="file" class="file-name" value="{{auth()->user()->avatar}}"/>
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                        <div class="col-md-6">
                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="profile_image" class="col-md-4 col-form-label text-md-right">Profile Image</label>
                        <div class="col-md-6">
                            <div id='imageUploadWide' class='needsclick'>
                                <div class='upload-img needsclick'>
                                    @if(strlen(auth()->user()->avatar))
                                    <div class="dz-image">
                                        <img src="{{url('/uploads/avatars/'.auth()->user()->avatar)}}"/>
                                    </div>
                                    @else
                                    <i class="fa-solid fa-cloud-arrow-up"></i><br/>
                                    @endif
                                </div>
                                <div class="preview-container needsclick">
                                </div>
                                <span class='note'>
                                    Drop a new image here or click to upload.
                                </span>
                            </div>
                            <div class="actions-image" @if(auth()->user()->avatar == 'default.jpg') style="display:none" @endif>
                                <a href="#" class="remove-image">remove image</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-auto mx-auto">
                            <button type="submit" class="btn btn-secondary">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updatePassword" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4>Update Password</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="{{url($path.'/update_password')}}" method="POST" role="form" onsubmit="return checkPasswords()">
                    @csrf
                    <div class="form-group row">
                        <label for="current_password" class="col-md-4 col-form-label text-md-right">Current Password</label>
                        <div class="col-md-6">
                            <input id="current_password" type="password" class="form-control" name="current_password" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new_password" class="col-md-4 col-form-label text-md-right">New Password</label>
                        <div class="col-md-6">
                            <input id="new_password" type="password" class="form-control" name="new_password" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirm_password" class="col-md-4 col-form-label text-md-right">Confirm New Password</label>
                        <div class="col-md-6">
                            <input id="confirm_password" type="password" class="form-control" name="confirm_password" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-auto mx-auto">
                            <button type="submit" class="btn btn-secondary">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style type="text/css">
        .dz-details, .dz-success-mark, .dz-error-mark, .dz-filename{
            display:none;
        }
        .dz-image img{
            max-width: 200px;
        }
        .needsclick{
            cursor: pointer;
        }
        .modal-header {
            background-color:#f7f7f7;
        }
    </style>

    <script src="{{url('js/dropzone/dist/dropzone.js')}}"></script>
    <script type="text/javascript">
        function checkPasswords(){
            cp = $("#current_password").val();
            np = $("#new_password").val();
            cnp = $("#confirm_password").val();
            if(cp.length < 6){
                alert("Current password must be atleast 6 characters long!");
                return false;
            }
            if(np.length < 6){
                alert("New password must be atleast 6 characters long!");
                return false;
            }
            if(np !== cnp){
                alert("New password and confirm new password must match!");
                return false;
            }
            return true;
        }

        const default_image = 'default.jpg';
        $(document).ready(function(){
            $('.remove-image').on("click", function(e){
                e.preventDefault();
                if(!confirm("Are you sure you want to remove your profile image?")){
                    return;
                }
                //remove from view
                $('.actions-image').hide();
                $(".dz-image").find('img').attr('src', base_url+'/uploads/avatars/'+default_image);
                $('.file-name').val(default_image);
                //@Adi we need to delete the old image from the database
            });

            var myDropzone = new Dropzone("div#imageUploadWide", {
                url: base_url + '/upload', // Set the url
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                uploadMultiple: false,
                maxFiles: 100,
                thumbnailWidth: $("div#imageUploadWide").width(),
                thumbnailHeight: $("div#imageUploadWide").height(),
                //previewTemplate: '.preview-container',
                autoProcessQueue: true,
                acceptedFile: "image/*",
                autoQueue: true, // Make sure the files aren't queued until manually added
                previewsContainer: ".preview-container", // Define the container to display the previews
                clickable: ".needsclick", // Define the element that should be used as click trigger to select files.

                complete: function (file){
                        console.log(file);
                        $('.upload-img').hide();
                        files = $('.preview-container').find('.dz-preview');
                        console.log(files.length);
                        if(files.length > 1){
                            $('.preview-container').find('.dz-preview').first().remove();
                        }
                        $('.preview-container').find('.dz-preview').first().find('img').attr('src', file.dataURL);
                        console.log("COMPLETE");

                },
                init: function() {
                        console.log("INIT");
                        this.on("success", function(file, serverResponse) {
                            if(serverResponse.success){
                                $('.actions-image').show();
                                $('.file-name').val(serverResponse.file);
                            }else{
                                alert(serverResponse.reason);
                        }
                });
                }
            });
        });
        //Moveable Modals
        $("#updateProfile").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection