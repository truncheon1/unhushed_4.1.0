<form action="{{url($path.'/backend/master-user-update')}}" method="POST" role="form" id="masterUserUpdate" onsubmit="return checkMasterUserUpdate()">
<input type="hidden" name="file" class="file-name1" value="{{$user->avatar}}"/>
<input type="hidden" name="user_id" value="{{$user->id}}" />
    @csrf
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
        <div class="col-md-6">
        <input id="name" type="text" class="form-control user-name" name="name" value="{{$user->name}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
        <div class="col-md-6">
        <input id="email" type="text" class="form-control email" name="email" value="{{$user->email}}">
        </div>
    </div>
    @if(auth()->user()->can('access-master'))
    <div class="form-group row">
        <label for="organizations" class="col-md-4 col-form-label text-md-right">Organization</label>
        <div class="col-md-6">
            <select class="form-control organization-user" name="oid">
                <!-- UNASSIGNED needs to have a check added to ensure someone is not assigned to a subscription or training, if they are they must be in an org -->
                <option value="0" @if($oid = 0) selected @endif>UNASSIGNED</option>
                @foreach(\App\Models\Organizations::orderBy('name', 'ASC')->get() as $org)
                    <option value="{{$org->id}}" 
                        @if($org->id == $user->org_id)
                            selected 
                        @endif 
                        @if($org->id == 1) 
                            rel="1" 
                        @else 
                            rel="2" 
                        @endif>
                            {{$org->name}}&nbsp;|&nbsp;{{$org->type}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    @else
    <input type="hidden" name="oid" value="{{$user->org_id}}" />
    @endif
    <div class="form-group row">
        <label  class="col-md-4 col-form-label text-md-right">Role(s)</label>
        <div class="col-md-6">
            @foreach(\App\Models\Roles::all() as $role)
                <label for="role-{{$role->id}}" class="col-md-4 col-form-label text-md-right"> {{$role->role}}</label>
                <input type="checkbox" name="role[]" value="{{$role->id}}" class="role-check" 
                    @if($user->hasRole($role->role)) checked @endif 
                    @if($role->id==1) checked disabled="disabled" @endif
                    id="role-{{$role->id}}" />
            @endforeach
        </div>
    </div>
    <div class="form-group row">
        <label for="profile_image" class="col-md-4 col-form-label text-md-right">Profile Image</label>
        <div class="col-md-6">
            <div id='imageUploadWide1' class='needsclick'>
                <div class='upload-img1 needsclick'>
                    @if(strlen($user->avatar))
                    <div class="dz-image">
                        <img class="tinyImg" src='{{url('uploads/avatars/'.$user->avatar)}}'/>
                    </div>
                    @else
                        <i class="fa-regular fa-cloud-arrow-up"></i><br/>
                    @endif
                </div>
                <div class="preview-container1 needsclick">
                </div>
                <span class='note'>
                    Drop a new image here or click it to upload.
                </span>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-auto mx-auto">
            <button type="submit" class="btn btn-secondary">Update User</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    var myDropzone = new Dropzone("div#imageUploadWide1", {
    url: base_url + '/upload', // Set the url
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    uploadMultiple: false,
    maxFiles: 100,
    thumbnailWidth: $("div#imageUploadWide1").width(),
    thumbnailHeight: $("div#imageUploadWide1").height(),
    //previewTemplate: '.preview-container',
    autoProcessQueue: true,
    acceptedFile: "image/*",
    autoQueue: true, // Make sure the files aren't queued until manually added
    previewsContainer: ".preview-container1", // Define the container to display the previews
    clickable: ".needsclick", // Define the element that should be used as click trigger to select files.
    complete: function (file){
            console.log(file);
            $('.upload-img1').hide();
            files = $('.preview-container1').find('.dz-preview');
            console.log(files.length);
            if(files.length > 1){
                $('.preview-container1').find('.dz-preview').first().remove();
            }
            $('.preview-container1').find('.dz-preview').first().find('img').attr('src', file.dataURL);
            console.log("COMPLETE");
    },
    init: function() {
            console.log("INIT");
            this.on("success", function(file, serverResponse) {
                if(serverResponse.success){
                    $('.actions-image1').show();
                    $('.file-name1').val(serverResponse.file);
                }else{
                    alert(serverResponse.reason);
            }
        });
        }
    });

    
    function checkMasterUserUpdate(){
        frmData = $("#masterUserUpdate").serialize();
        url = $("#masterUserUpdate").attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: frmData,
            success: function(result) {
            if(result.success == false){
                alert(show_result(result.message));
                return;
            }else{
                alert("User Updated!");
                location.reload();
            }
            },
            fail: function(){
                alert('Failed submitting this info!');
            }
        });
        return false;
    }
</script>
