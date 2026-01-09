
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/products/descriptions') }}" method="POST" id="save_descriptions" role="form">
        <input type="hidden" name="id" class="description_product_id" value=""/>
        <input type="hidden" name="rem" class="rem-descriptions" value="" />
        @csrf
        <div class="row">
            <div class="col-12">
                <div id="descriptionsContainer">
                    <!-- Descriptions populated via AJAX -->
                </div>
                <div class="mt-3">
                    <a href="#" class="add-description btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Description
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group row m-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary save-descriptions">Save Descriptions</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Initialize draggable if jQuery UI is available
        if($.fn.draggable) {
            $("#editProductDescriptions").draggable({
                handle: ".modal-header"
            });
        }

        // Initialize sortable if jQuery UI is available
        if($.fn.sortable) {
            $("#descriptionsContainer").sortable({
                handle: '.grip',
                items: '.description-item',
                update: function(event, ui) {
                    // Update sort order visually
                    updateDescriptionNumbers();
                }
            });
        }
    });
    
    // Update description numbers after sort
    function updateDescriptionNumbers(){
        $('#descriptionsContainer .description-item').each(function(index){
            $(this).find('strong').first().text('Description #' + (index + 1));
        });
    }

    $("body").on("click", ".product-descriptions-link", function(e){
        e.preventDefault();
        console.log('[DESCRIPTIONS] Link clicked');
        
        // Get product name from the table row
        const productId = $(this).attr('rel');
        const row = $(`tr[data-product-id="${productId}"]`);
        const productName = row.find('td:nth-child(3)').text() || '';
        
        console.log('[DESCRIPTIONS] Product ID:', productId, 'Name:', productName);
        
        $.ajax({
            url: $(this).attr('href'),
            type: 'get',
            success: function(response){
                console.log('[DESCRIPTIONS] Response:', response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    rem_descriptions = [];
                    // Store variants globally for use in fillDescriptions and addDescription
                    window.productVariants = response.variants || [];
                    fillDescriptions(response.descriptions);
                    $(".description_product_id").val(response.id);
                    // Set the product name in the modal title
                    $("#descriptionsProductName").text(productName);
                    $("#editProductDescriptions").modal('show');
                }
            },
            fail: function(res){
                console.error('[DESCRIPTIONS] AJAX failed:', res);
                alert('Failed to fetch descriptions!');
            }
        })
    });

    $(".add-description").on('click', function(e){
        e.preventDefault();
        addDescription();
    })

    function saveDescriptions(){
        var _url = $("#save_descriptions").attr('action');
        $(".rem-descriptions").val(rem_descriptions.join("|"));
        _form = $("#save_descriptions");
        
        // Trigger TinyMCE save to sync content to textareas
        tinymce.triggerSave();
        
        var formData = new FormData(_form[0]);
        $.ajax({
            url: _url,
            type: 'POST',
            data: formData,
            success: function (response) {
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert("Descriptions saved!");
                    // Refresh the modal content without closing
                    rem_descriptions = [];
                    const productId = $(".description_product_id").val();
                    
                    // Re-fetch descriptions for this product
                    $.ajax({
                        url: '{{ url($path."/backend/products") }}/' + productId + '/descriptions',
                        type: 'get',
                        success: function(refreshResponse){
                            if(refreshResponse.error !== true){
                                window.productVariants = refreshResponse.variants || [];
                                fillDescriptions(refreshResponse.descriptions);
                                // All descriptions will be collapsed by default after refresh
                            }
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Save failed:', xhr.responseText);
                if(xhr.responseJSON && xhr.responseJSON.message){
                    alert('Error: ' + xhr.responseJSON.message);
                } else {
                    alert('Failed to save descriptions. Please check all fields are filled in correctly.');
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    }

    // Handle "All Variants" checkbox for existing descriptions
    $("body").on("change", '.variant-all-checkbox', function(e){
        var descId = $(this).data('description-id');
        var checkboxes = $(this).closest('.variant-assignments').find('.variant-checkbox[data-description-id="' + descId + '"]');
        if($(this).is(':checked')){
            // Uncheck all individual variants when "All" is checked
            checkboxes.prop('checked', false);
        }
    });

    // Handle individual variant checkboxes for existing descriptions
    $("body").on("change", '.variant-checkbox', function(e){
        var descId = $(this).data('description-id');
        var allCheckbox = $(this).closest('.variant-assignments').find('.variant-all-checkbox[data-description-id="' + descId + '"]');
        // If any individual variant is checked, uncheck "All"
        if($(this).is(':checked')){
            allCheckbox.prop('checked', false);
        }
    });

    // Handle "All Variants" checkbox for new descriptions
    $("body").on("change", '.variant-all-checkbox-new', function(e){
        var block = $(this).closest('.description-block');
        var checkboxes = block.find('.variant-checkbox-new');
        if($(this).is(':checked')){
            // Uncheck all individual variants when "All" is checked
            checkboxes.prop('checked', false);
        }
    });

    // Handle individual variant checkboxes for new descriptions
    $("body").on("change", '.variant-checkbox-new', function(e){
        var block = $(this).closest('.description-block');
        var allCheckbox = block.find('.variant-all-checkbox-new');
        // If any individual variant is checked, uncheck "All"
        if($(this).is(':checked')){
            allCheckbox.prop('checked', false);
        }
    });

    function fillDescriptions(descriptions){
        var _container = $("#descriptionsContainer");
        _container.html("");
        
        if(!descriptions || descriptions.length === 0){
            _container.append('<div class="text-center text-muted p-3">No descriptions found. Click "Add Description" to create one.</div>');
            return;
        }
        
        for(x = 0; x < descriptions.length; x++){
            var desc = descriptions[x];
            
            // Build assigned variants text
            var assignedText = '';
            if(!desc.variant_ids || desc.variant_ids.length === 0){
                assignedText = '<span class="text-muted">All Variants</span>';
            } else if(window.productVariants && window.productVariants.length > 0){
                var assignedNames = [];
                for(var v = 0; v < window.productVariants.length; v++){
                    var variant = window.productVariants[v];
                    if(desc.variant_ids.includes(variant.var_id)){
                        assignedNames.push(variant.name || 'Variant #' + variant.var_id);
                    }
                }
                assignedText = assignedNames.length > 0 ? assignedNames.join(', ') : '<span class="text-muted">None</span>';
            }
            
            // Get preview text (strip HTML, truncate)
            var previewText = desc.description ? desc.description.replace(/<[^>]*>/g, '').substring(0, 100) : 'No content';
            if(desc.description && desc.description.length > 100) previewText += '...';
            
            var _block = `<div class="description-item card mb-2" data-description-id="${desc.id}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="grip me-2" style="cursor: move;"><i class="fas fa-grip-vertical text-muted"></i></span>
                                <strong>Description #${x + 1}</strong>
                            </div>
                            <div class="description-preview text-muted small">${previewText}</div>
                            <div class="mt-1 small">
                                <i class="fas fa-tag"></i> Assigned to: ${assignedText}
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-sm btn-primary edit-description-btn" data-description-id="${desc.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-pink delete-description-btn" data-description-id="${desc.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    
                    <!-- Expanded editor (hidden by default) -->
                    <div class="description-editor-container" data-description-id="${desc.id}" style="display:none;">
                        <hr>
                        <div class="row">
                            <div class="col-md-9">
                                <label class="form-label">Content:</label>
                                <textarea id="description_${desc.id}" name="description_content[${desc.id}]" class="form-control tinymce-editor">${desc.description || ''}</textarea>
                                <input type="hidden" name="description_id[${desc.id}]" value="${desc.id}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Assign To:</label>
                                <div class="variant-assignments" style="font-size: 12px;">
                                    ${buildVariantCheckboxes(desc.id, desc.variant_ids, false)}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-secondary collapse-description-btn" data-description-id="${desc.id}">
                                <i class="fas fa-chevron-up"></i> Collapse
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
            _container.append(_block);
        }
    }
    
    // Helper function to build variant checkboxes
    function buildVariantCheckboxes(descId, selectedVariantIds, isNew){
        var checkboxes = '';
        var checkboxClass = isNew ? 'variant-checkbox-new' : 'variant-checkbox';
        var allCheckboxClass = isNew ? 'variant-all-checkbox-new' : 'variant-all-checkbox';
        
        if(window.productVariants && window.productVariants.length > 0){
            // Add "All Variants" checkbox
            var allChecked = (!selectedVariantIds || selectedVariantIds.length === 0) ? 'checked' : '';
            checkboxes += `<label style="display:block; margin-bottom: 5px;"><input type="checkbox" class="${allCheckboxClass}" data-description-id="${descId}" ${allChecked}> <strong>All Variants</strong></label>`;
            
            // Add individual variant checkboxes
            for(var v = 0; v < window.productVariants.length; v++){
                var variant = window.productVariants[v];
                var isChecked = '';
                if(selectedVariantIds && selectedVariantIds.includes(variant.var_id)){
                    isChecked = 'checked';
                }
                var nameAttr = isNew ? `variants_new[${descId}][]` : `variants[${descId}][]`;
                checkboxes += `<label style="display:block; margin-bottom: 3px;"><input type="checkbox" name="${nameAttr}" value="${variant.var_id}" class="${checkboxClass}" data-description-id="${descId}" ${isChecked}> ${variant.name || 'Variant #' + variant.var_id}</label>`;
            }
        } else {
            checkboxes = '<small class="text-muted">No variants</small>';
        }
        
        return checkboxes;
    }
    
    // Handle Edit button click
    $("body").on("click", '.edit-description-btn', function(e){
        e.preventDefault();
        var descId = $(this).data('description-id');
        var $editorContainer = $(`.description-editor-container[data-description-id="${descId}"]`);
        
        // Show editor
        $editorContainer.slideDown(300);
        
        // Initialize TinyMCE if not already initialized
        var editorId = 'description_' + descId;
        if(!tinymce.get(editorId)){
            initTinyMCE(editorId);
        }
        
        // Hide edit button, show collapse button
        $(this).hide();
    });
    
    // Handle Collapse button click
    $("body").on("click", '.collapse-description-btn', function(e){
        e.preventDefault();
        var descId = $(this).data('description-id');
        var $editorContainer = $(`.description-editor-container[data-description-id="${descId}"]`);
        
        // Hide editor
        $editorContainer.slideUp(300);
        
        // Show edit button again
        $(`.edit-description-btn[data-description-id="${descId}"]`).show();
    });
    
    // Handle Delete button click
    $("body").on("click", '.delete-description-btn', function(e){
        e.preventDefault();
        var descId = $(this).data('description-id');
        
        if(confirm("Are you sure you want to delete this description?")){
            rem_descriptions.push(descId);
            
            // Remove TinyMCE instance if exists
            var editorId = 'description_' + descId;
            if(tinymce.get(editorId)){
                tinymce.get(editorId).remove();
            }
            
            // Remove the card
            $(`.description-item[data-description-id="${descId}"]`).remove();
        }
    });
    
    var rem_descriptions = [];
    
    function addDescription(){
        var _container = $("#descriptionsContainer");
        
        // Remove "no descriptions" placeholder if present
        _container.find('.text-center.text-muted').remove();
        
        var timestamp = Date.now();
        var descCount = _container.find('.description-item').length + 1;
        
        var _block = `<div class="description-item card mb-2" data-new-description="${timestamp}">
            <div class="card-body">
                <div class="row align-items-center" style="display:none;">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <span class="grip me-2" style="cursor: move;"><i class="fas fa-grip-vertical text-muted"></i></span>
                            <strong>New Description #${descCount}</strong>
                        </div>
                        <div class="description-preview text-muted small">Empty - click Edit to add content</div>
                        <div class="mt-1 small">
                            <i class="fas fa-tag"></i> Assigned to: <span class="text-muted">All Variants (default)</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-primary edit-new-description-btn" data-timestamp="${timestamp}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-pink delete-new-description-btn" data-timestamp="${timestamp}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                
                <!-- Expanded editor (shown by default for new) -->
                <div class="description-editor-container" data-timestamp="${timestamp}">
                    <div class="d-flex align-items-center mb-3">
                        <span class="grip me-2" style="cursor: move;"><i class="fas fa-grip-vertical text-muted"></i></span>
                        <strong>New Description #${descCount}</strong>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <label class="form-label">Content:</label>
                            <textarea id="description_new_${timestamp}" name="description_content_new[${timestamp}]" class="form-control tinymce-editor"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Assign To:</label>
                            <div class="variant-assignments" style="font-size: 12px;">
                                ${buildVariantCheckboxes(timestamp, null, true)}
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-secondary collapse-new-description-btn" data-timestamp="${timestamp}">
                            <i class="fas fa-chevron-up"></i> Collapse
                        </button>
                        <button type="button" class="btn btn-sm btn-pink delete-new-description-btn-expanded" data-timestamp="${timestamp}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        _container.append(_block);
        
        // Initialize TinyMCE immediately for new description
        var editorId = 'description_new_' + timestamp;
        initTinyMCE(editorId);
    }
    
    // Handle Edit button for new descriptions
    $("body").on("click", '.edit-new-description-btn', function(e){
        e.preventDefault();
        var timestamp = $(this).data('timestamp');
        var $editorContainer = $(`.description-editor-container[data-timestamp="${timestamp}"]`);
        
        // Show editor
        $editorContainer.slideDown(300);
        
        // Initialize TinyMCE if not already initialized
        var editorId = 'description_new_' + timestamp;
        if(!tinymce.get(editorId)){
            initTinyMCE(editorId);
        }
        
        // Hide edit button
        $(this).hide();
    });
    
    // Handle Collapse button for new descriptions
    $("body").on("click", '.collapse-new-description-btn', function(e){
        e.preventDefault();
        var timestamp = $(this).data('timestamp');
        var $editorContainer = $(`.description-editor-container[data-timestamp="${timestamp}"]`);
        
        // Hide editor
        $editorContainer.slideUp(300);
        
        // Show edit button again
        $(`.edit-new-description-btn[data-timestamp="${timestamp}"]`).show();
    });
    
    // Handle Delete button for new descriptions
    $("body").on("click", '.delete-new-description-btn, .delete-new-description-btn-expanded', function(e){
        e.preventDefault();
        var timestamp = $(this).data('timestamp');
        
        if(confirm("Are you sure you want to delete this description?")){
            // Remove TinyMCE instance if exists
            var editorId = 'description_new_' + timestamp;
            if(tinymce.get(editorId)){
                tinymce.get(editorId).remove();
            }
            
            // Remove the card
            $(`.description-item[data-new-description="${timestamp}"]`).remove();
        }
    });
    
    function initTinyMCE(editorId){
        // Wait a moment for DOM to be ready
        setTimeout(function(){
            tinymce.init({
                selector: '#' + editorId,
                license_key: 'gpl',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
                branding: false,
                promotion: false
            });
        }, 100);
    }
    
    // Bind form submit
    $("#save_descriptions").on("submit", function(e){
        e.preventDefault();
        return saveDescriptions();
    });
</script>
