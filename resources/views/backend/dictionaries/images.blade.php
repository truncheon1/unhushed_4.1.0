<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/dictionaries/flag')}}" method="POST" id="edit_flag" role="form" onsubmit="return editFlag()">
        <input type='hidden' name='id' class="id" value=''/>
        <input type="hidden" name="file" class="file-name1" value=""/>
        @csrf
        <div class="row pb-2">
            <div class="col">
                <input type="hidden" name="file" class="file-name1" value="{{$entry->flag}}" />
                <div id='imageUpload' class='needsclick'>
                    <div class='upload needsclick text-center'>
                        <div class="dz-image">
                            @empty($entry->flag)
                            <i class="fa fa-upload fa-fw" aria-hidden="true"></i> click to upload
                            @else
                            <img src='{{ url('/uploads/dictionaries/'.$entry->flag) }}'/>
                            @endempty
                        </div>
                        <br/>
                    </div>
                    <div class="preview-flag needsclick">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>

<style type="text/css">
    /*dropzone*/
    .dz-details, .dz-success-mark, .dz-error-mark, .dz-filename{
        display:none;
    }
    .dz-image img{
        max-width: 200px;
    }
    .needsclick{
        cursor: pointer;
    }
</style>

<script src="{{url("js/dropzone/dist/dropzone.js")}}"></script>
<script type="text/javascript">
    //get modal
    $('body').on("click", '.flag-edit-link', function(e){
        sorted = [];
        uploaded_images = [];
        e.preventDefault();
        _id = $(this).attr('rel');
        $.ajax({
            url: '{{url($path."/backend/dictionaries/flag")}}/'+_id,
            type: 'get',
            success: function(response){
                console.log(response);
                $(".id").val(response.entry.id);
                $("#flag").val(response.entry.flag);
                $("#editFlagMaster").modal('show');
            },
            fail: function(){ alert("Error"); }
        });
    });

    var myDropzone = new Dropzone("div#imageUpload", {
        url: base_url + "/upload", // Set the url
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        uploadMultiple: false,
        method: "post",
        maxFiles: 100,
        thumbnailWidth: $("div#imageUpload").width(),
        thumbnailHeight: $("div#imageUpload").height(),
        //previewTemplate: '.preview-container',
        autoProcessQueue: true,
        acceptedFile: "image/*",
        autoQueue: true, //Make sure the files aren't queued until manually added
        previewsContainer: ".preview-flag", // Define the container to display the previews
        clickable: ".needsclick", //Define the element that should be used as click trigger to select files.
        complete: function (file){
            console.log(file);
            $('./upload').hide();
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

    function editFlag(){
        let _url = $("#edit_flag").attr('action');
        fd = $("#edit_flag").serialize();
        $.ajax({
            url: _url,
            type: 'patch',
            data: fd,
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert("Flag updated!");
                    document.location = '{{ url($path.'/backend/dictionaries') }}';
                }
            },
            fail: function(){ alert("Error"); }
            });
        return false;
    }

</script>





