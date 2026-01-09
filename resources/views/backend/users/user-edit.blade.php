<form action="{{url($path.'/user_update')}}" method="POST" role="form" id="userFormEdit" onsubmit="return checkUserUpdate()">
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
        <label  class="col-md-4 col-form-label text-md-right">Role(s)</label>
        <div class="col-md-6">
            @foreach(\App\Models\Roles::where('exposed', '1')->get() as $role)
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
                        <img  src='{{url('uploads/avatars/'.$user->avatar)}}'/>
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
</script>