@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <span style="font-weight: bold;color:#9acd57">Dashboard</span>
            </div>
        </div>

        <!-- USER DASHBOARD 2 COLUMN LAYOUT -->
        <div class="row">
            <!-- User Links - Desktop: Left, Mobile: First -->
            <div class="col-lg-8 col-md-12 p-5">
                <b>USEFUL LINKS</b><br/>
                <div class="d-flex justify-content-center align-items-md-center">
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/curricula') }}"><img class="smImg" src="{{ asset('img/cards-med/papers.png') }}" alt="Curricula"></a>
                        <div class="smText">Curricula</div>
                    </div>
                    <div class="smCard">
                        <a href="{{ url($path.'/sex-ed-dictionary') }}"><img class="smImg" src="{{ asset('img/cards-med/dictionary.png') }}" alt="Dictionaries"></a>
                        <div class="smText">Dictionaries</div>
                    </div>
                    <div class="smCard">
                        <a href="{{ url($path.'/research') }}"><img class="smImg" src="{{ asset('img/cards-med/science.png') }}" alt="Research"></a>
                        <div class="smText">Research</div>
                    </div>
                </div>
            </div>
            
            <!-- User Profile - Desktop: Right, Mobile: Second -->
            <div class="col-lg-4 col-md-12 p-2">
                <div class="card">
                <!-- update profile pic -->
                <div class="photoUpdateBox">
                    <img class="avatar" src="/uploads/avatars/{{ Auth::user()->avatar }}">
                    <!-- Modal trigger -->
                    <button type="submit"  class="btn edit-btn-hover" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#updateProfileImage">
                        <span class="photoUpdate"><i class="fa-solid fa-camera fa-lg fa-fw"></i></span>
                    </button>
                </div>
                <div class="row px-1" style="text-align:left;">
                    <div class="col info">MEMBER SINCE</div>
                </div>
                <div class="row px-1 align-items-center pb-2" style="text-align:left;">
                    <div class="col">{{ Auth::user()->created_at->format('M d, Y') }}</div>
                </div>
                <!-- update name -->
                <div class="row px-1" style="text-align:left;">
                    <div class="col info">NAME</div>
                </div>
                <div class="row px-1 align-items-center pb-2" style="text-align:left;">
                    <div class="col-10">{{ Auth::user()->name }}</div>
                    <div class="col-2">
                        <button type="button" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#updateProfileInfo" class="btn btn-link p-1 edit-btn-hover" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <!-- update email -->
                <div class="row px-1" style="text-align:left;">
                    <div class="col info">EMAIL</div>
                </div>
                <div class="row px-1 align-items-center pb-2" style="text-align:left;">
                    <div class="col-10">{{ Auth::user()->email }}</div>
                </div>
                <!-- update password -->
                <div class="row px-1" style="text-align:left;">
                    <div class="col info">PASSWORD</div>
                </div>
                <div class="row px-1 align-items-center pb-2" style="text-align:left;">
                    <div class="col-10">Update your password</div>
                    <div class="col-2">
                        <button type="button" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#updatePassword" class="btn btn-link p-1 edit-btn-hover" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <!-- update address -->
                <div class="row px-1" style="text-align:left;">
                    <div class="col info">ADDRESS BOOK</div>
                </div>
                <div class="row px-1 align-items-center pb-2" style="text-align:left;">
                    <div class="col-10">Update your address book</div>
                    <div class="col-2">
                        <form action="{{ url(get_path($path).'/dashboard/address-book') }}" method="GET" class="m-0 p-0">
                            <button type="submit" class="btn btn-link p-1 edit-btn-hover" title="Manage address book">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- Modal Profile Image-->
<div class="modal fade" id="updateProfileImage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <span style="text-transform: uppercase; font-weight: bold;">PROFILE IMAGE</span> 
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="{{url($path.'/dashboard')}}" method="POST" role="form">
                    <input type="hidden" name="file" class="file-name" value="{{auth()->user()->avatar}}"/>
                    @csrf
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
                                <span class='note' style="color: #696969;">
                                    Drop a new image here or click to upload.
                                </span>
                            </div>
                            <div class="actions-image" @if(auth()->user()->avatar == 'default.jpg') style="display:none" @endif>
                                <a href="#" class="remove-image">remove image</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-secondary">Update Profile Image</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Profile Info-->
<div class="modal fade" id="updateProfileInfo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <span style="text-transform: uppercase; font-weight: bold;">USER NAME</span> 
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="{{url($path.'/dashboard')}}" method="POST" role="form">
                    @csrf
                    <div class="form-group row">
                        <label for="name_info" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input id="name_info" type="text" class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email_info" class="col-md-4 col-form-label text-md-right">Email</label>
                        <div class="col-md-6">
                            <input id="email_info" type="text" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
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
                <span style="text-transform: uppercase; font-weight: bold;">PASSWORD</span> 
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
        .edit-btn-hover {
            color: #01a7d3;
            transition: color 0.2s ease;
        }
        .edit-btn-hover:hover {
            color: #9acd57;
        }
        .edit-btn-hover:focus,
        .edit-btn-hover:active,
        .edit-btn-hover:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            border-color: transparent !important;
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
        $("#updateProfileImage").draggable({
            handle: ".modal-header"
        });
        $("#updateProfileInfo").draggable({
            handle: ".modal-header"
        });
    </script>
</section>
@endsection





