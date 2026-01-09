<!-- ADD USER -->
<div class="modal-body">
    <form action="{{url($path.'/backend/master-user-add')}}" method="POST" role="form" onsubmit="return addUser()" id="masterUserAdd" aria-label="master-user-add">
        <input type="hidden" name="file" class="file-name" value=""/>
        @csrf
        <!-- only admin can assign an organization -->
        @if(auth()->user()->can('access-master'))
        <div class="form-group row">
            <label for="organizations" class="col-md-4 col-form-label text-md-right">Organization</label>
            <div class="col-md-6">
                <select class="form-control organization-user" name="oid">
                    <option value="0">UNASSIGNED</option>
                    @foreach($organizations as $org)
                        <option value="{{$org->id}}" @if($org->id == $oid) selected @endif @if($org->id == 1) rel="1" @else rel="2" @endif >{{$org->name}}&nbsp;|&nbsp;{{$org->type}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @else
        <input type="hidden" name="oid" value="{{auth()->user()->org_id}}" />
        @endif
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
                @foreach(\App\Models\Roles::all() as $role)
                    <label for="role-{{$role->id}}" class="col-md-4 col-form-label text-md-right"> {{$role->role}}</label>
                    <input type="checkbox" name="role[]" value="{{$role->id}}" class="role-check" 
                        @if(isset($user) && $user->hasRole($role->role)) checked @endif 
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
                        Drop image or click here to upload.
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">SAVE</button>
            </div>
        </div>
    </form>
</div>

<script>
    //ADD MASTER USER
    function addUser(){
        frmData = $("#masterUserAdd").serialize();
        url = $("#masterUserAdd").attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: frmData,
            success: function(result) {
                if(result.success == false){
                    alert(show_result(result.message));
                    return;
                }else{
                    alert("User Added!");
                    location.reload();
                }
            },
            fail: function(){
                alert('User was not added.');
            }
        });
        return false;
    };
</script>