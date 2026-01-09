
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/products/images') }}" method="POST" id="save_images" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" class="image_product_id" value=""/>
        <input type="hidden" name="rem" class="rem-images" value="" />
        @csrf
        <div class="row">
            <div class="col-12">
                <table class="table table-striped" style="width: 100%" id="imagesTable">
                    <thead>
                        <tr>
                            <th style="width:30px;">&nbsp;</th>
                            <th id="preview" colspan="1" style="text-align:left; width:150px;">Preview</th>
                            <th id="imageFile" colspan="1" style="text-align:left">Image File</th>
                            <th id="variantsAssigned" colspan="1" style="text-align:left; width:250px;">Assigned To</th>
                            <th id="options" colspan="1" style="text-align:center; width:60px;">Options</th>
                        </tr>
                    </thead>
                    <tbody class="sortImages">
                        <!-- Images populated via AJAX -->
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <a href="#" class="add-image btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Image
                            </a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-group row m-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary save-images">Save Images</button>
            </div>
        </div>
    </form>
</div>

<script>
    $("#editProductImages").draggable({
        handle: ".modal-header"
    });

    $(".sortImages").sortable({
        handle: 'td:first'
    });

    $("body").on("click", ".product-images-link", function(e){
        e.preventDefault();
        // Get product name from the table row
        const productId = $(this).attr('rel');
        const row = $(`tr[data-product-id="${productId}"]`);
        const productName = row.find('td:nth-child(3)').text() || '';
        
        $.ajax({
            url: $(this).attr('href'),
            type: 'get',
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    rem_images = [];
                    // Store variants globally for use in fillImages and addImage
                    window.productVariants = response.variants || [];
                    window.imagePath = response.image_path || '';
                    fillImages(response.images);
                    $(".image_product_id").val(response.id);
                    // Set the product name in the modal title
                    $("#imagesProductName").text(productName);
                    $("#editProductImages").modal('show');
                }
            },
            fail: function(res){
                alert('Failed to fetch images!');
            }
        })
    });

    $(".add-image").on('click', function(e){
        e.preventDefault();
        addImage();
    })

    $("body").on("click", '.rem-existing-image', function(e){
        e.preventDefault();
        id = $(this).attr('rel');
        rem_images.push(id);
        if(confirm("Are you sure you want to delete this image?")){
            $(this).parent().parent().remove();
        }
    })

    $("body").on("click", '.rem-new-image', function(e){
        e.preventDefault();
        if(confirm("Are you sure you want to delete this image?")){
            $(this).parent().parent().remove();
        }
    })

    function saveImages(){
        var _url = $("#save_images").attr('action');
        $(".rem-images").val(rem_images.join("|"));
        _form = $("#save_images");
        
        // Check file sizes before submitting
        var maxFileSize = 5 * 1024 * 1024; // 5MB in bytes for images
        var totalSize = 0;
        var oversizedFiles = [];
        
        $('input[type="file"]', _form).each(function(){
            if(this.files && this.files.length > 0){
                for(var i = 0; i < this.files.length; i++){
                    var fileSize = this.files[i].size;
                    totalSize += fileSize;
                    if(fileSize > maxFileSize){
                        oversizedFiles.push(this.files[i].name + ' (' + (fileSize / 1024 / 1024).toFixed(2) + 'MB)');
                    }
                }
            }
        });
        
        if(oversizedFiles.length > 0){
            alert('The following images exceed the 5MB limit:\n\n' + oversizedFiles.join('\n') + '\n\nPlease use smaller images or upload them separately.');
            return false;
        }
        
        if(totalSize > 20 * 1024 * 1024){ // 20MB total
            alert('Total upload size (' + (totalSize / 1024 / 1024).toFixed(2) + 'MB) exceeds the 20MB limit.\n\nPlease upload fewer images at once or use smaller images.');
            return false;
        }
        
        var formData = new FormData(_form[0]);
        $.ajax({
            url: _url,
            type: 'POST',
            data: formData,
            success: function (response) {
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert("Images saved!");
                    // Refresh the modal content without closing
                    rem_images = [];
                    const productId = $(".image_product_id").val();
                    
                    // Re-fetch images for this product
                    $.ajax({
                        url: '{{ url($path."/backend/products") }}/' + productId + '/images',
                        type: 'get',
                        success: function(refreshResponse){
                            if(refreshResponse.error !== true){
                                window.productVariants = refreshResponse.variants || [];
                                window.imagePath = refreshResponse.image_path || '';
                                fillImages(refreshResponse.images);
                            }
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Save failed:', xhr.responseText);
                if(xhr.responseJSON && xhr.responseJSON.message){
                    alert('Error: ' + xhr.responseJSON.message);
                } else if(xhr.status === 413 || xhr.statusText === 'Request Entity Too Large'){
                    alert('The upload is too large. Please use smaller images or upload fewer images at once.');
                } else {
                    alert('Failed to save images. Please check all fields are filled in correctly.');
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    }

    // Handle "All Variants" checkbox for existing images
    $("body").on("change", '.variant-all-checkbox', function(e){
        var imageId = $(this).data('image-id');
        var checkboxes = $(this).closest('td').find('.variant-checkbox[data-image-id="' + imageId + '"]');
        if($(this).is(':checked')){
            // Uncheck all individual variants when "All" is checked
            checkboxes.prop('checked', false);
        }
    });

    // Handle individual variant checkboxes for existing images
    $("body").on("change", '.variant-checkbox', function(e){
        var imageId = $(this).data('image-id');
        var allCheckbox = $(this).closest('td').find('.variant-all-checkbox[data-image-id="' + imageId + '"]');
        // If any individual variant is checked, uncheck "All"
        if($(this).is(':checked')){
            allCheckbox.prop('checked', false);
        }
    });

    // Handle "All Variants" checkbox for new images
    $("body").on("change", '.variant-all-checkbox-new', function(e){
        var row = $(this).closest('tr');
        var checkboxes = row.find('.variant-checkbox-new');
        if($(this).is(':checked')){
            // Uncheck all individual variants when "All" is checked
            checkboxes.prop('checked', false);
        }
    });

    // Handle individual variant checkboxes for new images
    $("body").on("change", '.variant-checkbox-new', function(e){
        var row = $(this).closest('tr');
        var allCheckbox = row.find('.variant-all-checkbox-new');
        // If any individual variant is checked, uncheck "All"
        if($(this).is(':checked')){
            allCheckbox.prop('checked', false);
        }
    });

    function fillImages(images){
        var _table = $("#imagesTable tbody");
        _table.html("");
        
        if(!images || images.length === 0){
            _table.append('<tr><td colspan="5" class="text-center text-muted">No images found. Click "Add Image" to upload.</td></tr>');
            return;
        }
        
        for(x = 0; x < images.length; x++){
            var variantCheckboxes = '';
            if(window.productVariants && window.productVariants.length > 0){
                variantCheckboxes += '<div style="font-size: 12px;">';
                // Add "All Variants" checkbox
                var allChecked = (!images[x].variant_ids || images[x].variant_ids.length === 0) ? 'checked' : '';
                variantCheckboxes += '<label style="display:block; margin-bottom: 5px;"><input type="checkbox" class="variant-all-checkbox" data-image-id="' + images[x].id + '" ' + allChecked + '> <strong>All Variants</strong></label>';
                
                // Add individual variant checkboxes
                for(var v = 0; v < window.productVariants.length; v++){
                    var variant = window.productVariants[v];
                    var isChecked = '';
                    if(images[x].variant_ids && images[x].variant_ids.includes(variant.var_id)){
                        isChecked = 'checked';
                    }
                    variantCheckboxes += '<label style="display:block; margin-bottom: 3px;"><input type="checkbox" name="variants[' + images[x].id + '][]" value="' + variant.var_id + '" class="variant-checkbox" data-image-id="' + images[x].id + '" ' + isChecked + '> ' + (variant.name || 'Variant #' + variant.var_id) + '</label>';
                }
                variantCheckboxes += '</div>';
            } else {
                variantCheckboxes = '<small class="text-muted">No variants</small>';
            }
            
            var imagePreview = '';
            if(images[x].image){
                imagePreview = '<img src="' + window.imagePath + images[x].image + '" alt="Product image" style="max-width: 120px; max-height: 120px; object-fit: contain;" />';
            }
            
            _row = `<tr data-image-id="${images[x].id}">
                <td class="grip"><i class="fas fa-grip-vertical"></i></td>
                <td>${imagePreview}</td>
                <td>
                    <div class="mb-2">
                        <strong>Current:</strong> <span class="text-muted">${images[x].image || 'None'}</span>
                    </div>
                    <label class="form-label">Replace Image (optional):</label>
                    <input type="file" class="form-control" name="image_upload[${images[x].id}]" accept="image/*" />
                    <input type="hidden" name="image_id[${images[x].id}]" value="${images[x].id}" />
                </td>
                <td>${variantCheckboxes}</td>
                <td class="text-center"><a href="#" class="text-danger rem-existing-image" rel="${images[x].id}"><i class="fa fa-trash"></i></a></td>
                </tr>`;
            _table.append(_row);
        }
    }
    
    var rem_images = [];
    
    function addImage(){
        var _table = $("#imagesTable tbody");
        
        // Remove "no images" placeholder if present
        _table.find('td[colspan="5"]').parent().remove();
        
        var variantCheckboxes = '';
        if(window.productVariants && window.productVariants.length > 0){
            variantCheckboxes += '<div style="font-size: 12px;">';
            // Add "All Variants" checkbox (checked by default for new images)
            variantCheckboxes += '<label style="display:block; margin-bottom: 5px;"><input type="checkbox" class="variant-all-checkbox-new" checked> <strong>All Variants</strong></label>';
            
            // Add individual variant checkboxes
            for(var v = 0; v < window.productVariants.length; v++){
                var variant = window.productVariants[v];
                variantCheckboxes += '<label style="display:block; margin-bottom: 3px;"><input type="checkbox" name="variants_new[' + Date.now() + '_' + v + '][]" value="' + variant.var_id + '" class="variant-checkbox-new"> ' + (variant.name || 'Variant #' + variant.var_id) + '</label>';
            }
            variantCheckboxes += '</div>';
        } else {
            variantCheckboxes = '<small class="text-muted">No variants</small>';
        }
        
        var timestamp = Date.now();
        var _row = `<tr data-new-image="${timestamp}">
            <td class="grip"><i class="fas fa-grip-vertical"></i></td>
            <td><small class="text-muted">Preview after upload</small></td>
            <td>
                <input type="file" class="form-control" name="image_upload_new[]" accept="image/*" required />
            </td>
            <td>${variantCheckboxes}</td>
            <td class="text-center"><a href="#" class="text-danger rem-new-image"><i class="fa fa-trash"></i></a></td>
            </tr>`
        _table.append(_row);
    }
    
    // Bind form submit
    $("#save_images").on("submit", function(e){
        e.preventDefault();
        return saveImages();
    });
</script>
