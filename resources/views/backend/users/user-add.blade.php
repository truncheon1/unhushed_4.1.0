<!-- ADD USER -->
<div class="modal-body">
    <form action="{{url($path.'/dashboard/add_user')}}" method="POST" role="form" onsubmit="return addUser()" id="headUserAdd" aria-label="user-add">
        <input type="hidden" name="file" class="file-name" value=""/>
        @csrf
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control user-name" name="name" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
            <div class="col-md-6">
                <input id="email" type="text" class="form-control user-email" name="email" value="" >
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
                <div id='imageUploadWide' class='needsclick'>
                    <div class='upload-img needsclick'>
                        <i class="fa-regular fa-cloud-arrow-up"></i><br/>
                    </div>
                    <div class="preview-container needsclick">
                    </div>
                    <span class='note'>
                        Drop a new image here or click it to upload.
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">Create User</button>
            </div>
        </div>
    </form>
</div>

