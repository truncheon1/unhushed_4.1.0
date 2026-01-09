
<!-- STUFF -->
<div class="bday-txt pt-4">
    <p class="sketchnote-square" >Submit Your Belly</p>
        <hr>
        <form action="{{url($path.'/thebellyproject/thank-you')}}" method="POST" id="add_belly" role="form" onsubmit="return addBelly()">
            <input type="hidden" name="file" class="file-name" value=""/>
            @csrf
            <p>This info will <b>NOT</b> be displayed publicly</p>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name" class="sr-only">Name</label>
                    <input type="text" name="name" id="name" class="form-control" @if(auth()->user()) value="{{auth()->user()->name}}" @endif placeholder="Full name or just first name, your choice">
                </div>
                <div class="form-group col-md-6">
                    <label for="email" class="sr-only">Email</label>
                    <input type="text" name="email" id="email" class="form-control" @if(auth()->user()) value="{{auth()->user()->email}}" @endif placeholder="Email address">
                </div>
                <div class="col-12 text-center">
                @if(auth()->user())<span style="font-size:12px; color:#ccc;">The above section prefills for you if you're logged in to your UN|HUSHED account.</span> @endif
                </div>
            </div>
            <hr>
            <p>This info <b>WILL</b> be displayed publicly</p>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="title">Post Title </label>
                    <span style="font-size:14px; color:#ccc;">(No cuss words, no slurs, speak to your own experience and not anyone else’s.)</span>
                    <input id="title" type="text" class="form-control" name="title" placeholder="Title" class="@error('title') is-invalid @enderror">
                </div>
                <div class="form-group col-md-2">
                    <label for="age">Current Age</label>
                    <input id="age" type="number" class="form-control" name="age" placeholder="18" min="0" max="100">
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="gender">Gender Identity</label>
                    <select class="form-control" name="gender" id="gender">
                        <option value="" selected>Gender Identity    </option>
                        <option value="girl">Girl           </option>
                        <option value="boy">Boy             </option>
                        <option value="nonbinary">Nonbinary </option>
                        <option value="trans">Trans         </option>
                    </select>
                </div>
                <div class="form-group col-md-5">
                    <label for="country">Country you live in</label>
                    <input id="country" type="text" class="form-control" name="country" placeholder="Country you live in">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="pregnancies">Pregnancies </label>
                    <input id="pregnancies" type="number" class="form-control" name="pregnancies" placeholder="" min="0" max="100">
                </div>
                <div class="form-group col-md-2">
                    <label for="miscarriages">Miscarriages</label>
                    <input id="miscarriages" type="number" class="form-control" name="miscarriages" placeholder="" min="0" max="100">
                </div>
                <div class="form-group col-md-2">
                    <label for="vbirth">Vaginal birth</label>
                    <input id="vbirth" type="number" class="form-control" name="vbirth" placeholder="" min="0" max="100">
                </div>
                <div class="form-group col-md-2">
                    <label for="cbirth">C-sec births</label>
                    <input id="cbirth" type="number" class="form-control" name="cbirth" placeholder="" min="0" max="100">
                </div>
                <div class="form-group col-md-2">
                    <label for="abortions">Abortions</label>
                    <input id="abortions" type="number" class="form-control" name="abortions" placeholder="" min="0" max="100">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                <label for="bio">Up to 250 words on your experience with your body. 
                    <br/><span style="font-size:14px; color:#ccc;">(No cuss words, no slurs, speak to your own experience and not anyone else’s.)</span>
                </label>
                    <textarea class="form-control teditor" name="bio" id="bio"></textarea>
                </div>
            </div>
            <!-- need to fix image upload for min of 1 image max of 2-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">Belly Image(s)</label>
                <div class="col-md-10">
                    <input type="hidden" name="file" class="file-name" />
                    <div id='imageUploadWide'>
                        <div class="preview-container">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">&nbsp;</div>
                <div class="col-md-10" ><span class='note needsclick'>
                    Drop a new image above or click here to upload.
                </span> </div>
            </div>
            <hr>
            <div class="row pb-2">
                <div class="col-auto mx-auto">
                    <button type="submit" class="btn btn-secondary">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- IMAGE PREVIEW BUILDER -->
<div class="img-template">
    <div class="dz-preview dz-file-preview">
        <div class="dz-details">
            <img data-bs-dz-thumbnail class="data-bs-dz-thumbnail" />
            <div class="dz-span">
                <img src="{{url('img/products/move-alt.png')}}" style="max-width: 19px;" class="drag-handler" /><br/>
                <img src="{{url('img/products/delete.png')}}" class="delete-img" style="max-width: 19px;" />
            </div>
            <div class="dz-filename"><span data-bs-dz-name></span></div>
        </div>
    </div>
</div>
</div>
<!-- END -->

<!-- dropzone -->
<style type="text/css">
    .data-bs-dz-thumbnail {
        /* opacity: 0.5; */
    }
    .dz-details .dz-span {
        width: 110px;
        position: relative;
        top: -100px;
        text-align: center;
        display: inline-block;
    }
    .dz-image img{
        max-width: 200px;
    }
    .dz-overlayer {
        width: 109px;
        height: 109px;
        position: relative;
        top: -109px;
        background-color: #000;
        opacity: 0.1;
    }
    .dz-preview{
        margin: 10px;
    }
    .dz-span img {
        cursor: pointer;
        border: 1px solid #000;
        border-radius: 50%;
        padding: 2px;
        margin-bottom: 5px;
        background-color: #000;
    }
    .dz-success-mark, .dz-error-mark, .dz-filename,
    .img-template-edit .dz-details,
    .img-template-edit .dz-success-mark,
    .img-template-edit .dz-error-mark,
    .img-template-edit .dz-filename,
    .img-template .dz-details,
    .img-template .dz-success-mark,
    .img-template .dz-error-mark,
    .img-template .dz-filename
    {
        display:none;
    }
    .preview-container, .preview-container-edit{
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }
    #imageUploadWide, #imageUploadWideEdit{
        display: flex;
        align-items: center;
        background-color: #f7f7f7;
        border-bottom: 1px solid #0000001A;
        text-align: center;
        color: #787878;
        font-size: 12px;
        min-height: 100px;
    }
    .preview-container .dz-details,
    .preview-container-edit .dz-details {
        order: 1;
        width: 110px;
        height: 110px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .preview-container .dz-details .data-bs-dz-thumbnail,
    .preview-container-edit .dz-details .data-bs-dz-thumbnail {
        margin-right: 10px;
        margin-bottom: 10px;
        width: 100px;
        height: 100px;
        object-fit: fill;
    }
    .needsclick{
        cursor: pointer;
    }
</style>

<script src='{{url("js/dropzone/dist/dropzone.js")}}'></script>
<script src='{{url("js/tinymce/tinymce.min.js")}}'></script>
<script type="text/javascript">
    //Bio editor
    $(document).ready(function(){
        tinymce.init({
            selector: 'textarea#bio',
            license_key: 'gpl',
            height: 300,
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
    //Wordcount for Bio
    function getStats(id) {
        var body = tinymce.get(id).getBody(), text = tinymce.trim(body.innerText || body.textContent);
        return {
            chars: text.length,
            words: text.split(/[\w\u2019\'-]+/).length
        };
    }

    //Image uploader
    var uploaded_images = []; //keep a record of uploaded images name and file response name
    var sorted = [];

    var myDropzone = new Dropzone("div#imageUploadWide", {
        url: base_url + '/upload', // Set the url
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        uploadMultiple: false,
        method: "post",
        maxFiles: 2,
        thumbnailWidth: 100,
        thumbnailHeight: 100,
        previewTemplate: document.querySelector('.img-template').innerHTML,
        autoProcessQueue: true,
        acceptedFile: "image/*",
        autoQueue: true, // Make sure the files aren't queued until manually added
        previewsContainer: ".preview-container", // Define the container to display the previews
        clickable: ".needsclick", // Define the element that should be used as click trigger to select files.
        complete: function (file){
            console.log("LOCAL", file);
            if(file.accepted  == false){
                alert("Two images max. Refresh the page if you are unable to upload after deleting image(s).");
                $('.preview-container').find('.dz-preview').last().remove();
                return;
            }
        },
        init: function() {
            console.log("INIT");
            this.on("success", function(file, serverResponse) {
                console.log("SERVER", file);
                if(serverResponse.success && file.accepted){
                    uploaded_images.push({
                        file : serverResponse.file,
                        name : file.name
                    });
                    sorted.push(file.name);
                }else{
                    alert(serverResponse.reason);
                    $('.preview-container').find('.dz-preview').last().remove();
                }
            });
        }
    });
    $('body').on('click', '.delete-img', function(e){
        e.stopPropagation();
        if(!confirm('Are you sure you want to remove this image?')){
            return;
        }
        console.log(myDropzone.files[1].name);
        el = $(this).parent().parent().parent();
        text = el.find('.dz-filename span').first().text();
        for(x = 0; x < myDropzone.files.length; x++){
            if(myDropzone.files[x].name == text){
                myDropzone.removeFile(myDropzone.files[x]);
            }
        }
        //myDropzone.removeFile();
        $(this).parent().parent().parent().remove();
        resort_array();
    })
    $( ".preview-container" ).sortable({
        handle: '.drag-handler',
        items: '.dz-preview',
        stop: function( event, ui ) {
            console.log(event, ui);
            console.log(uploaded_images);
            resort_array();
        }
    }).disableSelection();

    function resort_array(){
         let new_sort = [];
         $('.dz-preview').each(function(i){
             if($(this).find('.dz-filename span').first().text() != "")
                 new_sort.push($(this).find('.dz-filename span').first().text());
         });
         sorted = new_sort;
     }

    function addBelly(){
        stats = getStats('bio');
        if(stats.words > 250){
            alert(`Please limit your text to 250 words (words written: ${stats.words})`);
            return false;
        }
        let _url = $("#add_belly").attr('action');
        fd = $("#add_belly").serialize();
        for(x = 0; x < sorted.length; x++){
            for(i in uploaded_images){
                if(uploaded_images[i].name == sorted[x]){
                    fd += "&image[]="+uploaded_images[i].file;
                    break;
                }
            }
        }
        $.ajax({
            url: _url,
            type: 'post',
            data: fd,
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert("Your belly has been added.");
                    document.location = '{{ url($path.'/thebellyproject/thank-you') }}';
                }
            },
            fail: function(){ alert("Error"); }
        });
        return false;
    }
</script>
