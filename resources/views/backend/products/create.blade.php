<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/products')}}" method="POST" id="add_product" role="form" onsubmit="return addProduct()">
        <input type="hidden" name="file" class="file-name" value=""/>
        @csrf
        <div class="row pb-2">
            <label for="name" class="col-form-label col-md-2 text-md-right pr-1">Product Name</label>
            <div class="col-md-8">
                <input type="text" class="form-control" id="name" name="name" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="slug" class="col-form-label col-md-2 text-md-right pr-1">Store Slug/URL</label>
            <div class="col-md-8">
                <input id="slug" type="text" class="form-control" name="slug" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="cat" class="col-form-label col-md-2 text-md-right pr-1">Product Category</label>
            <div class="col-md-3">
            <select class="form-select" id="cat" name="category">
                <option value="">select one</option>
                <option value="1">curriculum</option>
                <option value="2">activity</option>
                <option value="3">book</option>
                <option value="4">game</option>
                <option value="5">swag</option>
                <option value="6">teaching tool</option>
                <option value="7">training</option>
            </select>
            </div>
            <label for="sort" class="col-form-label col-md-1 text-md-right pr-2">Sort</label>
            <div class="col-md-1">
                <input type="number" class="form-control" id="sort" name="sort" value="" placeholder="1" min="0" max="99">
            </div>
            <label for="tax" class="col-form-label col-md-1 text-md-right pr-2">Taxable</label>
            <div class="col-md-2">
                <select class="form-select" name="taxable" id="tax">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
        <div class="row pb-2">
            <label for="tags" class="col-form-label col-md-2 text-md-right pr-1">Active Campaign Tag(s)</label>
            <div class="col-md-8">
                <input id="tags" type="text" class="form-control" name="ac_tags" value="">
                <small class="form-text" style="color: #696969">Examples[Products: SESL T, Curriculum: ES]</small>
            
            </div>
        </div>
        <div class="row pb-2">
            <label for="categories" class="col-form-label col-md-2 text-md-right pr-2">Store Tags</label>
            <div class="col-md-8">
                <select class="form-select" data-live-search="true" id='categories' multiple name='tags[]' size="8" style="height: auto;">
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', 0)->get() as $tag_option)
                    <optgroup label="{{$tag_option->name}}">
                        @foreach(\App\Models\ProductStoreTags::where('parent_id', $tag_option->id)->get() as $tag)
                        <option value='{{$tag->id}}'>{{$tag->root_tag()}}/{{$tag->parent_tag_name()}}/{{$tag->name}}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                <small class="form-text" style="color: #696969">Hold Ctrl (Windows) or Cmd (Mac) to select multiple tags</small>
            </div>
        </div>
        <div class="row pb-2">
            <label for="author" class="col-form-label col-md-2 text-md-right pr-2" id="authorLabel">
                Author(s)/Trainer(s)</label>
            <div class="col-md-8">
                <input id="author" type="text" class="form-control" name="author" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="facilitator" class="col-form-label col-md-2 text-md-right pr-2" id="facilitatorLabel">Intended Facilitator</label>
            <div class="col-md-8">
                <input id="facilitator" type="text" class="form-control" name="facilitator" value="">
            </div>
        </div>
        <div class="row pb-2" id="populationRow">
            <label for="population" class="col-form-label col-md-2 text-md-right pr-2" id="populationLabel">Participant Population</label>
            <div class="col-md-8">
            <input id="population" type="text" class="form-control" name="population" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="age" class="col-form-label col-md-2 text-md-right pr-2">Age Appropriate For</label>
            <div class="col-md-8">
                <input id="age" type="text" class="form-control" name="age" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="duration" class="col-form-label col-md-2 text-md-right pr-2" id="durationLabel">Duration</label>
            <div class="col-md-8">
                <input id="duration" type="text" class="form-control" name="duration" value="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-md-right">Product Image(s)</label>
            <div class="col-md-8">
                <input type="hidden" name="file" class="file-name" />
                <div id='imageUploadWide'>
                    <div class="preview-container">
                    </div>
                </div>
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-8 text-center">
                <span class='note needsclick'>Drop a new image above or click here to upload.</span>
            </div>
        </div>
        <hr>
        <div class="row pb-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">SAVE</button>
            </div>
        </div>
    </form>
    <!-- IMAGE PREVIEW BUILDER -->
    <div class="img-template">
        <div class="dz-preview dz-file-preview">
            <div class="dz-details">
                <img data-dz-thumbnail class="data-dz-thumbnail" />
                <div class="dz-span">
                    <img src="{{url('img/products/move-alt.png')}}" style="max-width: 19px;" class="drag-handler" /><br/>
                    <img src="{{url('img/products/delete.png')}}" class="delete-img" style="max-width: 19px;" />
                </div>
                <div class="dz-filename"><span data-dz-name></span></div>
            </div>
        </div>
    </div>
</div>
<!-- END -->

<style>
    .dropdown-menu {
        text-align: left !important;
    }
</style>

<script>
    //Auto generate slugs from name
    $('body').on("change", "#name", function(){
        $('#slug').val(string_to_slug($(this).val()));
    })
    $('body').on("change", "#name", function(){
        $('#slug2').val(string_to_slug($(this).val()));
    })

    // Update detail field labels based on category selection
    function updateDetailLabels(categoryId){
        const cat = parseInt(categoryId);
        const authorLabel = $('#authorLabel');
        const facilitatorLabel = $('#facilitatorLabel');
        const durationLabel = $('#durationLabel');
        const populationRow = $('#populationRow');
        const populationLabel = $('#populationLabel');
        
        // Hide detail fields if no category selected or swag (category 5)
        if(!categoryId || categoryId === '' || cat === 5){
            $('#author, #facilitator, #population, #age, #duration').closest('.row').hide();
            return;
        } else {
            $('#author, #facilitator, #population, #age, #duration').closest('.row').show();
        }
        
        // Author field labels
        if(cat === 1 || cat === 2){ // curriculum or activity
            authorLabel.text('Writer(s)');
        } else if(cat === 3){ // books
            authorLabel.text('Author(s)');
        } else if(cat === 4){ // games
            authorLabel.text('Creator(s)');
        } else if(cat === 7){ // trainings
            authorLabel.text('Trainer(s)');
        } else {
            authorLabel.text('Author(s)/Trainer(s)');
        }
        
        // Facilitator field labels
        if(cat === 3){ // books
            facilitatorLabel.text('Intended Reader');
        } else if(cat === 7){ // trainings
            facilitatorLabel.text('Intended Trainee');
        } else { // curricula, activities, games, tools
            facilitatorLabel.text('Intended Facilitator');
        }
        
        // Duration field labels
        if(cat === 1){ // curriculum
            durationLabel.text('Length');
        } else if(cat === 3){ // books
            durationLabel.text('Page Count');
        } else if(cat === 4){ // games
            durationLabel.text('Participants');
        } else if(cat === 7){ // trainings
            durationLabel.text('Duration');
        } else {
            durationLabel.text('Duration');
        }
        
        // Population field visibility - hide for books (category 3)
        if(cat === 3){ // books
            populationRow.hide();
        } else {
            populationRow.show();
            populationLabel.text('Participant Population').css('color', '');
        }
    }

    //erase modals on close
    $(".modal").on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })

    //prevent modal open on page load
    $(".add-product").on('click', function(e){
        e.preventDefault();
        uploaded_images = [];
        sorted = [];
        $("#addProduct").modal('show');
        // Initialize detail labels on modal open
        updateDetailLabels($('#cat').val());
    });

    // Update detail field labels when category changes
    $('#cat').on('change', function(){
        updateDetailLabels($(this).val());
    });

    //modal draggable
    $("#addProduct").draggable({
        handle: ".modal-header"
    });

    //description widget
    tinymce.init({
        selector: 'textarea#description',
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
    /***********************************************
     * ADD PRODUCT
     **********************************************/
        $("#type").on('change', function(){
        if(physical_products.includes($(this).val())){
            $('.qty-block').css({'display': 'flex'});
            $('.shipping-block').css({'display': 'flex'});
            $('.format').css({'display': 'none'});
            $('.grade_level').css({'display': 'none'});
            $('.student_population').css({'display': 'none'});
            $('.duration').css({'display': 'none'});
        }else{
            $('.qty-block').css({'display': 'none'});
            $('.shipping-block').css({'display': 'none'});
            $('.format').css({'display': 'none'});
            $('.grade_level').css({'display': 'none'});
            $('.student_population').css({'display': 'none'});
            $('.duration').css({'display': 'none'});
        }
        console.log($("#sort").val());
        const sort_string = $("#sort_string").val();
        let last_sort = '01';
        if(sort_string.length > 2 && sort_string.includes("-")){
            parts = sort_string.split("-");
            last_sort = parts[1];
        }
        //prefill sort
    index = (types.indexOf($(this).val()) * 1);//needs the multiplication because sometimes indexOf() value is treated as string
        $("#sort_string").val(index+"-"+last_sort);
        console.log("INDEX:" + index+"-"+last_sort);
    });

    $( ".preview-container" ).sortable({
        handle: '.drag-handler',
        items: '.dz-preview',
        stop: function( event, ui ) {
        console.log(event, ui);
        console.log(uploaded_images);
        resort_array();
        }
    }).disableSelection();

    //Image uploader
    var myDropzone = new Dropzone("div#imageUploadWide", {
        url: base_url + '/upload', // Set the url
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        uploadMultiple: false,
        maxFiles: 100,
        thumbnailWidth: 100,
        thumbnailHeight: 100,
        previewTemplate: document.querySelector('.img-template').innerHTML,
        autoProcessQueue: true,
        acceptedFile: "image/*",
        autoQueue: true, // Make sure the files aren't queued until manually added
        previewsContainer: ".preview-container", // Define the container to display the previews
        clickable: ".needsclick", // Define the element that should be used as click trigger to select files.
        complete: function (file){
        },
        init: function() {
            console.log("INIT");
            this.on("success", function(file, serverResponse) {
                console.log(file);
                if(serverResponse.success){
                    uploaded_images.push({
                        file : serverResponse.file,
                        name : file.name
                    });
                    sorted.push(file.name);
                }else{ alert(serverResponse.reason); }
            });
        }
    });

    //Add product
    function addProduct(){
        let _url = $("#add_product").attr('action');
        fd = $("#add_product").serialize();
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
                if(response.error === true){
                    if(response.errors){
                        alert(get_text_error(response.errors));
                    }else{
                        alert(response.message);
                    }
                }else{
                    alert("Product added.");
                    location.reload();
                }
            },
            fail: function(){ alert("Error");}
        });
        return false;
    }
    //Auto generate and clean slug from name
    function string_to_slug (str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();
        // remove accents, swap ñ for n, etc
        var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to   = "aaaaaeeeeiiiioooouuuunc------";
        for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace with -
            //@Adi the above doesn't seem to be working?
            .replace(/-+/g, '-'); // collapse dashes
        return str;
    }
    //Show divs based on selection
    function ctypeR(nameSelect){
        if(nameSelect){
            recurringValue = document.getElementById("recurring").value;
            if(recurringValue == nameSelect.value){
                document.getElementById("ctypeR").style.display = "block";
            }
            else{
                document.getElementById("ctypeR").style.display = "none";
            }
        }
        else{
            document.getElementById("ctypeR").style.display = "none";
        }
    }
    function finite(nameSelect){
        if(nameSelect){
            limitValue = document.getElementById("limit").value;
            if(limitValue == nameSelect.value){
                document.getElementById("finite").style.display = "block";
            }
            else{
                document.getElementById("finite").style.display = "none";
            }
        }
        else{
            document.getElementById("finite").style.display = "none";
        }
    }
    function get_text_error(errors){
        let _txt = '';
        for(e in errors){
            for(i in errors[e]){
                _txt += errors[e][i]+" \n";
            }
        }
        return _txt;
    }
    /**END**/
</script>






