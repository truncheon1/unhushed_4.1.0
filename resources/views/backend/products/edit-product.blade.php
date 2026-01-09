<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/products/update')}}" method="POST" id="edit_product" role="form" onsubmit="return editProduct()">
        <input type='hidden' name='id' class="id" value=''/>
        <input type="hidden" name="file" class="file-name1" value=""/>
        @csrf
        <div class="row pb-2">
            <label for="ename" class="col-form-label col-md-2 text-md-right pr-1">Product Name</label>
            <div class="col-md-8">
                <input id="ename" type="text" class="form-control" name="name" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="eslug" class="col-form-label col-md-2 text-md-right pr-1">Store Slug/URL</label>
            <div class="col-md-4">
                <input id="eslug" type="text" class="form-control" name="slug" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="ecat" class="col-form-label col-md-2 text-md-right pr-1">Product Category</label>
            <div class="col-md-3">
            <select class="form-select" id="ecat" name="category">
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
            <label for="esort" class="col-form-label col-md-1 text-md-right pr-2">Sort</label>
            <div class="col-md-1">
                <input id="esort" type="number" class="form-control" name="sort" value="" placeholder="1" min="0" max="99">
            </div>
            <label for="etax" class="col-form-label col-md-1 text-md-right pr-2">Taxable</label>
            <div class="col-md-2">
                <select class="form-select" name="taxable" id="etax">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
        <div class="row pb-2">
            <label for="etags" class="col-form-label col-md-2 text-md-right pr-2">AC Tag(s)</label>
            <div class="col-md-8">
                <input id="etags" type="text" class="form-control" name="ac_tags" value="" placeholder="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="ecategories" class="col-form-label col-md-2 text-md-right pr-2">Store Tags</label>
            <div class="col-md-8">
                <select class="form-select" data-live-search="true" id='ecategories' multiple name='tags[]' size="8" style="height: auto;">
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
            <label for="eauthor" class="col-form-label col-md-2 text-md-right pr-2" id="eauthorLabel">Author(s)</label>
            <div class="col-md-8">
                <input id="eauthor" type="text" class="form-control" name="eauthor" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="efacilitator" class="col-form-label col-md-2 text-md-right pr-2" id="efacilitatorLabel">Intended Facilitator</label>
            <div class="col-md-8">
                <input id="efacilitator" type="text" class="form-control" name="efacilitator" value="" placeholder="">
            </div>
        </div>
        <div class="row pb-2" id="epopulationRow">
            <label for="epopulation" class="col-form-label col-md-2 text-md-right pr-2" id="epopulationLabel">Participant Population</label>
            <div class="col-md-8">
                <input id="epopulation" type="text" class="form-control" name="epopulation" value="" placeholder="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="eage" class="col-form-label col-md-2 text-md-right pr-2">Age Appropriate For</label>
            <div class="col-md-8">
                <input id="eage" type="text" class="form-control" name="eage" value="" placeholder="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="eduration" class="col-form-label col-md-2 text-md-right pr-2" id="edurationLabel">Duration</label>
            <div class="col-md-8">
                <input id="eduration" type="text" class="form-control" name="eduration" value="" placeholder="">
            </div>
        </div>
        <div class="row pb-2">
            <label class="col-form-label col-md-2 text-md-right pr-2">Description</label>
            <div class="col-md-8 pt-2">
                <span style="font-size:10pt; color: #696969">
                    This can be edited in the description system.
                </span>
            </div>
        </div>
        <hr>
         <div class="form-group row">
            <label class="col-md-2 col-form-label text-md-right">Product Image(s)</label>
            <div class="col-md-8">
                <input type="hidden" name="file" class="file-name" />
                <div id='imageUploadWideEdit'>
                    <div class="preview-container-edit">
                    </div>
                </div>
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-8 text-center">
                <span class='note needsclick1' style="color: #696969">Drop a new image above or click here to upload.</span>
            </div>
        </div>
        <hr>
        <div class="row pb-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">Save Product</button>
            </div>
        </div>
    </form>
    <!-- IMAGE PREVIEW BUILDER -->
    <div class="img-template-edit">
        <div class="dz-preview dz-file-preview">
            <div class="dz-details">
                <img data-dz-thumbnail class="data-dz-thumbnail" />
                <div class="dz-span">
                    <img src="{{url('img/products/move-alt.png')}}" class="drag-handler" /><br/>
                    <img src="{{url('img/products/delete.png')}}" class="delete-img"/>
                </div>
                <div class="dz-filename" style="display: none;"><span data-dz-name></span></div>
            </div>
        </div>
    </div>
    <!-- END -->
</div>

<style>
    .needsclick1 {
        cursor:pointer;
    }
    /* Constrain product image preview sizing */
    #imageUploadWideEdit { min-height:110px; }
    .preview-container-edit { display:flex; flex-wrap:wrap; gap:10px; }
    .preview-container-edit .dz-preview { width:120px; max-width:120px; }
    .preview-container-edit .dz-details { position:relative; text-align:center; }
    .preview-container-edit img.data-dz-thumbnail { 
        width:110px; 
        height:110px; 
        object-fit:cover; 
        border:1px solid #ccc; 
        border-radius:4px; 
        background:#fff; 
        box-shadow:0 1px 3px rgba(0,0,0,0.15);
    }
    .preview-container-edit .dz-span img { 
        cursor: pointer;
        border-radius: 50%;
        object-fit: contain; 
    }
    .drag-handler {
        position:absolute; 
        top:2px; 
        left:4px; 
        opacity:0.7;
    }
    .delete-img {
        position:absolute; 
        top:2px; 
        left:24px; 
        opacity:0.7;
    }
    /* Drag highlight */
    .preview-container-edit .dz-preview.dragging { outline: 2px dashed #265a8e; }
</style>

<script type="text/javascript">
    //modal draggable
    $("#editProductMaster").draggable({
        handle: ".modal-header"
    });

    // Fix aria-hidden focus issue when modal closes
    $('#editProductMaster').on('hide.bs.modal', function (e) {
        // Remove focus from any focused element within the modal before it closes
        const focusedElement = document.activeElement;
        if (focusedElement && this.contains(focusedElement)) {
            focusedElement.blur();
        }
    });

    // Ensure backdrop and modal-open class are removed when modal closes
    $('#editProductMaster').on('hidden.bs.modal', function (e) {
        // Remove any leftover backdrops
        $('.modal-backdrop').remove();
        // Remove modal-open class from body
        $('body').removeClass('modal-open');
        // Reset body styles that might have been set
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    });

    // Prevent Bootstrap dialog from blocking focusin
    document.addEventListener('focusin', function (e) { 
        if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) { 
            e.stopImmediatePropagation();
        } 
    });

    // Initialize TinyMCE when modal is shown
    $('#editProductMaster').on('shown.bs.modal', function () {
        if (!tinymce.get('edescription')) {
            tinymce.init({
                selector: 'textarea#edescription',
                license_key: 'gpl',
                height: 300,
                plugins: 'code link image table lists fullscreen searchreplace visualblocks visualchars insertdatetime advlist charmap anchor',
                menubar: false,
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image table | align | numlist bullist indent outdent | charmap | removeformat | code',
                promotion: false,
                branding: false
            });
        }
    });

    //Auto generate slugs from name
    $('body').on("change", "#ename", function(){
        $('#eslug').val(string_to_slug($(this).val()));
    })
    $('body').on("change", "#ename", function(){
        $('#edeliver_slug').val(string_to_slug($(this).val()));
    })

    //edit script
    function editProduct(){
        // Sync TinyMCE content back to textarea before serializing
        if(tinymce.get('edescription')){
            tinymce.get('edescription').save();
        }
        
        let _url = $("#edit_product").attr('action');
        fd = $("#edit_product").serialize();
        console.log(fd);
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
                alert("Product updated.");
                location.reload();
            }
        },
        fail: function(){ alert("Error"); }
        });
        return false;
    }

    //delete image in edit
    $('body').on('click', '.delete-img', function(e){
        e.stopPropagation();
        if(!confirm('Are you sure you want to remove this image?')){
            return;
        }
        $(this).parent().parent().parent().remove();
        resort_array();
    })

    // Recompute order of images after drag/remove
    function resort_array(){
        if(typeof window.sorted === 'undefined') window.sorted = [];
        if(typeof window.uploaded_images === 'undefined') window.uploaded_images = [];
        const previews = document.querySelectorAll('.preview-container-edit .dz-preview');
        const order = [];
        previews.forEach(p => {
            const nameSpan = p.querySelector('.dz-filename span[data-dz-name]');
            let name = nameSpan ? (nameSpan.textContent || '').trim() : '';
            if(!name){
                const thumb = p.querySelector('img.data-dz-thumbnail');
                if(thumb){
                    const parts = (thumb.getAttribute('src') || '').split('/');
                    name = parts[parts.length - 1];
                }
            }
            if(name) order.push(name);
        });
        window.sorted = order;
        const map = {};
        window.uploaded_images.forEach(obj => { if(obj && obj.name) map[obj.name] = obj; });
        window.uploaded_images = order.map(n => map[n] || {file:n, name:n});
        console.log('[resort_array] new order:', window.sorted);
    }

    //PRODUCT EDIT
    $('body').on("click", '.product-edit-link', function(e){
        sorted = [];
        uploaded_images = [];
        e.preventDefault();
        _id = $(this).attr('rel');
        $.ajax({
            url: '{{url($path."/backend/products/")}}/'+_id,
            type: 'get',
            success: function(response){
                // Guard: ensure expected product payload exists before populating form
                if(!response || !response.product){
                    alert('Error: missing product data in response.');
                    return;
                }
                const variant = response.variant || {};
                $(".id").val(response.product.id);
                $("#ename").val(response.product.name);
                // Update modal title with product name
                $("#variantProductName").text(response.product.name);
                $("#eslug").val(response.product.slug);
                $("#etags").val(response.product.ac_tags);
                // Set category, default to empty if 0 or invalid
                $("#ecat").val(response.product.category > 0 ? response.product.category : '');
                $("#esort").val(response.product.sort);
                $("#edeliver_slug").val(variant.deliver_slug || '');
                $("#eprice").val(variant.price || 0);
                $("#etax").val(variant.taxable || 1);
                $("#eqty").val(variant.qty || 0);
                $("#eship_type").val(variant.ship_type || 0);
                $("#eweight").val(variant.weight || 0);
                // Set TinyMCE content
                // Product-level description now lives on product record (variants use description2)
                const description = response.product.description || '';
                if(tinymce.get('edescription')){
                    tinymce.get('edescription').setContent(description);
                } else {
                    $("#edescription").val(description);
                }
                
                $(".preview-container-edit").html("");
                if(response.details){
                    $("#eauthor").val(response.details.author);
                    $("#efacilitator").val(response.details.facilitator);
                    $("#epopulation").val(response.details.population);
                    $("#eage").val(response.details.age);
                    $("#eduration").val(response.details.duration);
                }
                
                // Update labels based on loaded category (AFTER data is loaded)
                updateEditDetailLabels(response.product.category);
                
                // Trigger category change to show/hide physical product fields
                $("#ecat").trigger("change");
                $("#ecategories option").each(function(){
                    $(this).prop('selected', false);
                })
                for(i in response.tags){
                    $("#ecategories option[value='"+response.tags[i].tag_id+"']").prop("selected", true);
                }
                // No need to refresh selectpicker since we're using native select
                for(i in response.images){
                    image = "{{url('uploads/products/')}}/"+response.images[i].image;
                    icons_url = "{{url('img/products')}}";
                    image = `<div class="dz-preview dz-file-preview">
                                            <div class="dz-details">
                                                <img data-dz-thumbnail class="data-dz-thumbnail" src="${image}" />
                                                <div class="dz-span">
                                                    <img src="${icons_url}/move-alt.png" style="max-width: 19px;" class="drag-handler" /><br/>
                                                    <img src="${icons_url}/delete.png" class="delete-img" style="max-width: 19px;" />
                                                </div>
                                                <div class="dz-filename" style="display: none;"><span data-dz-name>${response.images[i].image}</span></div>
                                            </div>
                                        </div>`;
                    $(".preview-container-edit").append(image);
                    uploaded_images.push({
                        file: response.images[i].image,
                        name: response.images[i].image
                    })
                    sorted.push(response.images[i].image);
                }
                $("#editProductMaster").modal('show');
            },
            fail: function(){ alert("Error"); }
        });
    });

    $("#ecat").on('change', function(){
        const catId = parseInt($(this).val());
        const categoryName = window.types[catId] || '';
        const isDigital = window.digital.includes(categoryName);
        
        // Show/hide qty block for physical products
        if(!isDigital && catId !== 5){ // Not digital and not swag (swag uses separate size management)
            $('.qty-block').css({'display': 'flex'});
        }else{
            $('.qty-block').css({'display': 'none'});
        }
        
        // Hide detail fields and digital slug for swag (category 5)
        if(catId === 5){
            $('#eauthor, #efacilitator, #epopulation, #eage, #eduration, #edeliver_slug').closest('.row').hide();
        }else{
            $('#eauthor, #efacilitator, #epopulation, #eage, #eduration, #edeliver_slug').closest('.row').show();
        }
        
        // Update detail field labels based on category
        updateEditDetailLabels(catId);
    });
    
    // Function to update detail labels in edit form
    function updateEditDetailLabels(catId){
        const cat = parseInt(catId);
        const authorLabel = $('#eauthorLabel');
        const facilitatorLabel = $('#efacilitatorLabel');
        const durationLabel = $('#edurationLabel');
        const populationRow = $('#epopulationRow');
        const populationLabel = $('#epopulationLabel');
        const populationInput = $('#epopulation');
        
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
            authorLabel.text('Author(s)');
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
        
        // Population field visibility - hide for books (category 3) unless data exists
        if(cat === 3){ // books
            const hasPopulation = populationInput.val() && populationInput.val().trim() !== '';
            if(hasPopulation){
                // Show with red DELETE label if data exists
                populationRow.show();
                populationLabel.text('DELETE').css('color', '#dc3545'); // Bootstrap danger red
            } else {
                // Hide if no data
                populationRow.hide();
            }
        } else {
            // Show for all other categories with normal label
            populationRow.show();
            populationLabel.text('Participant Population').css('color', '');
        }
    }

    $( ".preview-container-edit" ).sortable({
        handle: '.drag-handler',
        items: '.dz-preview',
        stop: function( event, ui ) {
        console.log(event, ui);
        console.log(uploaded_images);
        resort_array();
        }
    }).disableSelection();

    var myDropzone = new Dropzone("div#imageUploadWideEdit", {
        url: base_url + '/upload', // Set the url
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        uploadMultiple: false,
        maxFiles: 100,
        thumbnailWidth: 100,
        thumbnailHeight: 100,
        previewTemplate: document.querySelector('.img-template-edit').innerHTML,
        autoProcessQueue: true,
        acceptedFile: "image/*",
        autoQueue: true, // Make sure the files aren't queued until manually added
        previewsContainer: ".preview-container-edit", // Define the container to display the previews
        clickable: ".needsclick1", // Define the element that should be used as click trigger to select files.
        complete: function (file){
        },
        init: function() {
            console.log("INIT");
            this.on("success", function(file, serverResponse) {
                console.log(file);
                if(serverResponse.success){
                    uploaded_images.push({ file : serverResponse.file, name : file.name });
                    if(!sorted.includes(file.name)) sorted.push(file.name);
                    resort_array();
                }else{
                    alert(serverResponse.reason);
                }
            });
        }
    });

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
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
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








