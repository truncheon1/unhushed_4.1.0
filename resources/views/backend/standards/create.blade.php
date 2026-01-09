<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/standards')}}" method="POST" id="add_standard" role="form" onsubmit="return addStandard()">
        <input type="hidden" name="file" class="file-name" value=""/>
        @csrf
            <div class="row pb-2">
                <label for="name" class="col-form-label col-md-2 text-md-right pr-2">Standard</label>
                <div class="col-md-10">
                    <input id="name" type="text" class="form-control" name="name" value="">
                </div>
            </div>
            <div class="row pb-2">
                <label for="url" class="col-form-label col-md-2 text-md-right pr-2">URL</label>
                <div class="col-md-10">
                    <input id="url" type="text" class="form-control" name="url" value="">
                </div>
            </div>
            <div class="row pb-2">
                <label for="aligns" class="col-form-label col-md-2 text-md-right pr-2">Aligns</label>
                <div class="col-md-10">
                    <select class="form-select" id="aligns" name="aligns">
                        <option selected>select one</option>
                        <option value="yes">yes</option>
                        <option value="no">no</option>
                    </select>
                </div>
            <div class="form-group row mx-auto mt-5">
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src='{{url("js/dropzone/dist/dropzone.js")}}'></script>
<script src='{{url("js/tinymce/tinymce.min.js")}}'></script>
<script type="text/javascript">
    //modal draggable
    $("#addStandard").draggable({
        handle: ".modal-header"
    });

    //tinymce
    $(document).ready(function(){
        tinymce.init({
            selector: 'textarea#abstract',
            license_key: 'gpl',
            height: 500,
            plugins: 'importcss searchreplace autolink code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help charmap',
            paste_as_text: true,
            menubar: false,
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            contextmenu: 'paste | link image inserttable | cell row column deletetable'
        });
        // Prevent Bootstrap dialog from blocking focusin
        document.addEventListener('focusin', function (e) { 
            if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) { 
                e.stopImmediatePropagation();
            } 
        });
    });

    //add
    function addStandard(){
        let _url = $("#add_standard").attr('action');
        fd = $("#add_standard").serialize();
        $.ajax({
        url: _url,
        type: 'post',
        data: fd,
        success: function(response){
            console.log(response);
            if(response.error === true){
                alert(response.message);
            }else{
                alert("Standard added.");
                document.location = '{{url($path.'/backend/standards')}}';

            }
        },
        fail: function(){
            alert("Error");
        }
        });
        return false;
    }
</script>
