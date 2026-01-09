<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/products/variants')}}" method="POST" id="edit_variants" role="form">
        <input type='hidden' name='product_id' id="product_id" value=''/>
        @csrf
        
        <div class="table-responsive">
            <table class="table table-sm table-bordered" id="variantsTable">
                <thead class="table-light">
                    <tr>
                        <th class="grip-col" style="width: 30px;">&nbsp;</th>
                        <th style="width: 30px;">&nbsp;</th>
                        <th style="width: 60px;">Var ID</th>
                        <th style="width: 150px;">Name</th>
                        <th style="width: 120px;">SKU/ISBN</th>
                        <th class="collapsible-col" style="width: 120px;">Ship Type</th>
                        <th class="collapsible-col slug-col" style="width: 200px;"><span class="slug-header">Delivery Slug</span></th>
                        <th class="collapsible-col options-header" style="width: 100px;">Options</th>
                        <th style="width: 100px;">Price</th>
                        <th class="collapsible-col" style="width: 100px;">Taxable</th>
                        <th class="collapsible-col" style="width: 80px;">Weight</th>
                        <th class="collapsible-col" style="width: 100px;">Qty</th>
                        <th style="width: 100px;">Available</th>
                        <th style="width: 40px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="variantsTableBody" class="sortVariants">
                    <!-- Variants will be populated here via JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <button type="button" class="btn btn-sm btn-secondary" id="add-variant">
                    <i class="fas fa-plus"></i> Add New Variant
                </button>
                <button type="button" class="btn btn-sm btn-info" id="autofill-slugs" title="Auto-fill empty delivery slugs with product slug">
                    <i class="fas fa-magic"></i> Auto-fill Slugs
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="expand-all-variants" title="Expand all variant rows">
                    <i class="fas fa-expand-alt"></i> Expand All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="collapse-all-variants" title="Collapse all variant rows">
                    <i class="fas fa-compress-alt"></i> Collapse All
                </button>
                <button type="button" class="btn btn-sm btn-danger" id="delete-selected-variants" disabled>
                    <i class="fas fa-trash"></i> Delete Selected (<span id="variant-selected-count">0</span>)
                </button>
            </div>
        </div>
        
        <hr>
        
        <div class="row pb-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary" id="save-variants-btn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Save All Variants
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    #variantsTable input[type="text"],
    #variantsTable input[type="number"],
    #variantsTable select {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    #variantsTable .var-id-cell {
        background-color: #f8f9fa;
        font-weight: 500;
    }
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }
    /* Style "No" option in Available dropdown with red text */
    #variantsTable select[name*="[avail]"] option[value="0"] {
        color: #dc3545;
        font-weight: 600;
    }
    /* Collapsed row styles */
    tr.collapsed > td:not(.collapsed-cell) {
        display: none;
    }
    tr.collapsed > td.collapsed-cell {
        display: table-cell !important;
    }
    .collapsed-cell {
        display: none;
    }
    .collapsed-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        flex-wrap: wrap;
    }
    .collapsed-info .info-group {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .collapsed-info .info-label {
        font-weight: 600;
        color: #666;
    }
    .collapsed-info .info-value {
        color: #333;
    }
    .collapsed-actions {
        margin-left: auto;
        display: flex;
        gap: 0.25rem;
    }
    .collapse-toggle {
        cursor: pointer;
        transition: transform 0.2s;
    }
    tr.collapsed .collapse-toggle {
        transform: none;
    }
</style>

<script type="text/javascript">
    //modal draggable
    $("#editVariants").draggable({
        handle: ".modal-header"
    });
    
    // Make variants sortable
    $(".sortVariants").sortable({
        handle: 'td:first',
        axis: 'y',
        update: function(event, ui) {
            console.log('Variant order changed');
        }
    });

    // Track variants counter for new rows
    let newVariantCounter = 0;
    
    // Global storage for option values and assignments
    // These will be synced with window.optionValues and window.variantAssignments
    let optionValues = [];
    let variantAssignments = {};
    
    // Sync local variables with window globals when modal loads
    // This ensures options are properly grouped on initial load
    function syncOptionsFromWindow() {
        if (window.optionValues && window.optionValues.length > 0) {
            optionValues = window.optionValues;
        }
        if (window.variantAssignments && Object.keys(window.variantAssignments).length > 0) {
            variantAssignments = window.variantAssignments;
        }
    }
    
    // Listen for modal show event to sync data
    const editVariantsModal = document.getElementById('editVariants');
    if (editVariantsModal) {
        editVariantsModal.addEventListener('shown.bs.modal', function() {
            syncOptionsFromWindow();
            toggleOptionsColumnVisibility();
        });
    }
    
    // Toggle options column visibility based on product category
    function toggleOptionsColumnVisibility(){
        const needsOptions = categoryNeedsOptions();
        const productCategory = window.currentProductCategory || 0;
        const isSwag = productCategory === 5; // Category 5 is swag
        
        const optionsHeader = document.querySelector('.options-header');
        const optionsCells = document.querySelectorAll('.options-column');
        const slugCols = document.querySelectorAll('.slug-col');
        const gripCols = document.querySelectorAll('.grip-col');
        
        if(optionsHeader){
            optionsHeader.style.display = needsOptions ? '' : 'none';
        }
        optionsCells.forEach(cell => {
            cell.style.display = needsOptions ? '' : 'none';
        });
        
        // Hide slug column entirely for swag
        slugCols.forEach(col => {
            col.style.display = isSwag ? 'none' : '';
        });
        
        // Hide grip/sort column for swag (no manual reordering needed)
        gripCols.forEach(col => {
            col.style.display = isSwag ? 'none' : '';
        });
    }
    
    // Helper function to generate slug from product name
    function generateSlugFromProductName() {
        const productName = window.currentProductName || '';
        const productSlug = window.currentProductSlug || '';
        
        // Prefer existing product slug, fallback to generating from name
        if (productSlug) {
            return productSlug.replace(/^\/+/, '');
        }
        
        if (!productName) {
            return 'product';
        }
        
        // Convert product name to slug format
        return productName
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special chars except spaces and hyphens
            .replace(/\s+/g, '-')          // Replace spaces with hyphens
            .replace(/-+/g, '-')           // Replace multiple hyphens with single
            .replace(/^-+|-+$/g, '');      // Trim hyphens from ends
    }

    // Auto-fill empty delivery slugs
    document.getElementById('autofill-slugs').addEventListener('click', function(){
        const generatedSlug = generateSlugFromProductName();
        if (!generatedSlug || generatedSlug === '/') {
            alert('Cannot generate slug: product name not available');
            return;
        }
        
        let filledCount = 0;
        document.querySelectorAll('.deliver-slug-input').forEach(input => {
            if (!input.value || input.value.trim() === '') {
                input.value = generatedSlug;
                filledCount++;
                // Trigger validation
                input.dispatchEvent(new Event('blur', { bubbles: true }));
            }
        });
        
        if (filledCount > 0) {
            window.showToast(`Auto-filled ${filledCount} delivery slug(s) with: ${generatedSlug}`, 'success');
        } else {
            window.showToast('All delivery slugs already have values', 'info');
        }
    });
    
    // Add new variant row
    document.getElementById('add-variant').addEventListener('click', function(){
        newVariantCounter--;
        const generatedSlug = generateSlugFromProductName();
        const productCategory = window.currentProductCategory || 0;
        
        // Set default ship type based on category
        // 2=activity(standard), 3=books(media), 4=games(standard), 5=swag(standard), 6=tools(standard), 7=trainings(digital)
        let defaultShipType = 1; // Default to standard
        if(productCategory == 3) defaultShipType = 2; // Books = media
        else if(productCategory == 7) defaultShipType = 0; // Training = digital
        
        const row = createVariantRow({
            var_id: 'NEW',
            name: '',
            sku: '',
            deliver_slug: generatedSlug,
            option: '',
            price: 0.00,
            taxable: 1,
            ship_type: defaultShipType,
            weight: 0.00,
            qty: 1,
            avail: 1,
            _isNew: true,
            _tempId: newVariantCounter
        });
        document.getElementById('variantsTableBody').appendChild(row);
        // Initialize weight, qty, and slug UI based on ship_type (1 = Standard)
        toggleSlugRequired(newVariantCounter);
        toggleWeightUI(newVariantCounter);
        toggleQtyUI(newVariantCounter);
        
        // Apply category-based visibility restrictions to the new row
        toggleOptionsColumnVisibility();
        
        // Scroll to the new row so it's visible in the table-responsive container
        setTimeout(() => {
            const tableResponsive = document.querySelector('.table-responsive');
            if(tableResponsive){
                tableResponsive.scrollTop = tableResponsive.scrollHeight;
            }
        }, 0);
        
        updateSelectedCount();
    });

    // Delete selected variants
    let deleteVariantsBatchLock = false;
    document.getElementById('delete-selected-variants').addEventListener('click', function(){
        if(deleteVariantsBatchLock) return;
        
        const checkboxes = document.querySelectorAll('.variant-checkbox:checked');
        if(checkboxes.length === 0) return;
        
        if(!confirm(`Delete ${checkboxes.length} selected variant(s)?`)) return;
        
        deleteVariantsBatchLock = true;
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Deleting...';
        btn.disabled = true;
        
        // Remove new rows immediately, mark existing for deletion
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if(row.dataset.isNew === 'true'){
                row.remove();
            } else {
                row.classList.add('table-danger');
                row.dataset.markedForDeletion = 'true';
            }
        });
        
        setTimeout(() => {
            deleteVariantsBatchLock = false;
            btn.innerHTML = originalText;
            btn.disabled = false;
            updateSelectedCount();
        }, 1500);
    });

    // Checkbox selection handlers
    document.addEventListener('change', function(e){
        if(e.target && e.target.classList.contains('variant-checkbox')){
            updateSelectedCount();
        }
    });

    document.getElementById('variantsTableBody').addEventListener('change', function(e){
        if(e.target && e.target.id === 'selectAllVariants'){
            const checkboxes = document.querySelectorAll('.variant-checkbox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
            updateSelectedCount();
        }
    });

    function updateSelectedCount(){
        const count = document.querySelectorAll('.variant-checkbox:checked').length;
        document.getElementById('variant-selected-count').textContent = count;
        document.getElementById('delete-selected-variants').disabled = count === 0;
    }

    // Update product button color in main table based on remaining variants
    function updateProductButtonColor(productId){
        if(!productId) return;
        
        // Check remaining variants in the modal (exclude marked for deletion)
        const remainingRows = Array.from(document.querySelectorAll('#variantsTableBody tr'))
            .filter(r => r.dataset.markedForDeletion !== 'true');
        
        const hasVariants = remainingRows.length > 0;
        const hasAvailableVariant = remainingRows.some(r => {
            const availInput = r.querySelector('input[name*="[avail]"]');
            return availInput && availInput.checked;
        });
        
        // Find button in products table
        const productRow = document.querySelector(`#productsTable tr[data-product-id="${productId}"]`);
        if(!productRow) return;
        
        const dropdownBtn = productRow.querySelector('.btn-group .dropdown-toggle');
        const productCategory = parseInt(productRow.dataset.category || '0');
        
        if(!dropdownBtn) return;
        
        // Update button color based on category and availability
        if(productCategory === 1){
            // Curriculum - always grey
            dropdownBtn.classList.remove('btn-danger');
            dropdownBtn.classList.add('btn-secondary');
            dropdownBtn.removeAttribute('title');
        } else {
            // Other categories - red if no available variants
            if(hasAvailableVariant){
                dropdownBtn.classList.remove('btn-danger');
                dropdownBtn.classList.add('btn-secondary');
                dropdownBtn.removeAttribute('title');
            } else {
                dropdownBtn.classList.remove('btn-secondary');
                dropdownBtn.classList.add('btn-danger');
                const msg = hasVariants ? 'No available variants' : 'No variants';
                dropdownBtn.setAttribute('title', msg);
            }
        }
    }

    function sortOptionValues(values, optionName){
        // Custom sort for size options (XS, S, M, L, XL, 2XL, etc.)
        if(optionName && optionName.toLowerCase().includes('size')){
            const sizeOrder = ['xs', 'sm', 's', 'med', 'm', 'lrg', 'l', 'xl', '2xl', '3xl', '4xl', '5xl'];
            return values.slice().sort((a, b) => {
                const aName = a.name.toLowerCase().trim();
                const bName = b.name.toLowerCase().trim();
                const aIndex = sizeOrder.indexOf(aName);
                const bIndex = sizeOrder.indexOf(bName);
                
                // If both are in the sizeOrder array, sort by position
                if(aIndex !== -1 && bIndex !== -1){
                    return aIndex - bIndex;
                }
                // If only one is in sizeOrder, prioritize it
                if(aIndex !== -1) return -1;
                if(bIndex !== -1) return 1;
                // Otherwise, sort alphabetically
                return aName.localeCompare(bName);
            });
        }
        // Default: return as-is for non-size options
        return values;
    }

    // Check if product category needs options column
    // Category 4 (games) needs options like "size"
    // Category 5 (swag) needs "color" and "size" options
    // Categories 2,3,6,7 (activity, book, toolkit, training) don't need options
    function categoryNeedsOptions(){
        const productCategory = window.currentProductCategory || 0;
        return [4, 5].includes(productCategory); // Only games(4) and swag(5) need options
    }

    function buildShipTypeOptions(selectedShipType){
        const productCategory = window.currentProductCategory || 0;
        let options = '';
        
        // Category-based ship type availability
        // 2=activity(standard only), 3=books(digital+media), 4=games(digital+standard), 
        // 5=swag(standard only), 6=tools(standard only), 7=trainings(digital only)
        
        const showDigital = ![5, 6].includes(productCategory); // Hide for swag(5), tools(6)
        const showStandard = ![3, 7].includes(productCategory); // Hide for books(3), trainings(7)
        const showMedia = ![4, 5, 6, 7].includes(productCategory); // Hide for games(4), swag(5), tools(6), trainings(7)
        
        if(showDigital){
            options += `<option value="0" ${selectedShipType == 0 ? 'selected' : ''}>Digital</option>`;
        }
        if(showStandard){
            options += `<option value="1" ${selectedShipType == 1 ? 'selected' : ''}>Standard</option>`;
        }
        if(showMedia){
            options += `<option value="2" ${selectedShipType == 2 ? 'selected' : ''}>Media</option>`;
        }
        
        return options;
    }

    function buildOptionsSelect(varId, formId){
        // Get product category from window global
        const productCategory = window.currentProductCategory || 0;
        
        // Categories 2,3,6,7 (activity, book, toolkit, training) don't need options - return empty
        if(![4, 5].includes(productCategory)){
            return '<small class="text-muted">n/a</small>';
        }
        
        // Use window globals if local not set (for consistency with products.blade.php)
        const values = optionValues.length > 0 ? optionValues : (window.optionValues || []);
        const assignments = Object.keys(variantAssignments).length > 0 ? variantAssignments : (window.variantAssignments || {});
        
        if(!values || values.length === 0){
            return '<small class="text-muted">No options available</small>';
        }
        
        // Filter values based on option's allowed categories from database
        const filteredValues = values.filter(v => {
            // If option has no categories assigned, show it for all products (backwards compatibility)
            if(!v.option_categories || !Array.isArray(v.option_categories) || v.option_categories.length === 0){
                return true;
            }
            
            // Only show option if current product category is in the option's allowed categories
            return v.option_categories.includes(productCategory);
        });
        
        if(filteredValues.length === 0){
            return '<small class="text-muted">No options available</small>';
        }
        
        // Group values by option name and option_id
        const grouped = {};
        filteredValues.forEach(v => {
            const optionId = v.options_id || 0;
            const groupName = v.option_name || 'Ungrouped';
            if(!grouped[optionId]){
                grouped[optionId] = {
                    name: groupName,
                    values: []
                };
            }
            grouped[optionId].values.push(v);
        });
        
        const assigned = assignments[varId] || [];
        
        // Create separate single-select dropdown for each option
        let html = '<div class="options-container">';
        Object.keys(grouped).sort((a,b) => {
            return grouped[a].name.localeCompare(grouped[b].name);
        }).forEach(optionId => {
            const group = grouped[optionId];
            const groupName = group.name;
            
            // Sort values appropriately (custom sort for sizes)
            const sortedValues = sortOptionValues(group.values, groupName);
            
            // Find which value (if any) is currently assigned for this option
            let selectedValueId = '';
            for(const v of sortedValues){
                if(assigned.includes(v.value_id)){
                    selectedValueId = v.value_id;
                    break;
                }
            }
            
            html += '<div class="mb-1">';
            html += '<label class="form-label" style="font-size:0.75rem;margin-bottom:0.125rem;">'+groupName.replace(/"/g,'&quot;')+'</label>';
            html += '<select class="form-select form-select-sm" name="assign['+formId+']['+optionId+']" style="font-size:0.75rem;">';
            html += '<option value="">-- Select --</option>';
            sortedValues.forEach(v => {
                const sel = (v.value_id == selectedValueId) ? ' selected' : '';
                html += '<option value="'+v.value_id+'"'+sel+'>'+v.name.replace(/"/g,'&quot;')+'</option>';
            });
            html += '</select>';
            html += '</div>';
        });
        html += '</div>';
        return html;
    }

    function createVariantRow(variant){
        const row = document.createElement('tr');
        // Use tempId for new variants to ensure unique form field names
        const formId = variant._isNew ? variant._tempId : variant.var_id;
        
        row.dataset.varId = variant.var_id;
        row.dataset.isNew = variant._isNew ? 'true' : 'false';
        if(variant._isNew){
            row.dataset.tempId = variant._tempId;
        }
        
        const optionsContent = buildOptionsSelect(variant.var_id, formId);
        
        // Build collapsed summary text
        const shipTypeText = variant.ship_type == 0 ? 'Digital' : (variant.ship_type == 2 ? 'Media' : 'Standard');
        const availText = variant.avail == 1 ? 'Yes' : 'No';
        const priceFormatted = parseFloat(variant.price || 0).toFixed(2);
        const qtyText = variant.ship_type == 0 ? '∞' : (variant.qty || 0);
        
        // Get assigned options for this variant
        let optionsText = '';
        const productCategory = window.currentProductCategory || 0;
        if([4, 5].includes(productCategory)) {
            const assignedValueIds = (window.variantAssignments && window.variantAssignments[variant.var_id]) || [];
            const allOptionValues = window.optionValues || [];
            if(assignedValueIds.length > 0 && allOptionValues.length > 0) {
                const assignedOptions = assignedValueIds.map(valueId => {
                    const optValue = allOptionValues.find(v => v.value_id == valueId);
                    return optValue ? optValue.name : null;
                }).filter(name => name !== null);
                if(assignedOptions.length > 0) {
                    optionsText = assignedOptions.join(' / ');
                }
            }
        }
        
        row.innerHTML = `
            <td class="grip grip-col" style="cursor:move;"><i class="fas fa-grip-vertical"></i></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-link p-0 collapse-toggle" title="Expand/Collapse">
                    <i class="fa-light fa-arrows-to-dotted-line"></i>
                </button>
            </td>
            <td class="collapsed-cell" colspan="13">
                <div class="collapsed-info">
                    ${variant.name ? `<div class="info-group">
                        <span class="info-label">Name:</span>
                        <span class="info-value" style="color: #28a745; font-weight: 600;">${variant.name}</span>
                    </div>` : ''}
                    ${variant.sku ? `<div class="info-group">
                        <span class="info-label">SKU:</span>
                        <span class="info-value" style="color: #28a745; font-weight: 600;">${variant.sku}</span>
                    </div>` : ''}
                    <div class="info-group">
                        <span class="info-label">Ship:</span>
                        <span class="info-value" style="color: #28a745; font-weight: 600;">${shipTypeText}</span>
                    </div>
                    ${optionsText ? `<div class="info-group">
                        <span class="info-label">Options:</span>
                        <span class="info-value" style="color: #28a745; font-weight: 600;">${optionsText}</span>
                    </div>` : ''}
                    <div class="info-group">
                        <span class="info-label">Price:</span>
                        <span class="info-value" style="color: #28a745; font-weight: 600;">$${priceFormatted}</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Qty:</span>
                        <span class="info-value" style="color: ${qtyText >= 1 ? '#28a745' : '#dc3545'}; font-weight: 600;">${qtyText}</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Available:</span>
                        <span class="info-value" style="color: ${variant.avail == 1 ? '#28a745' : '#dc3545'}; font-weight: 600;">${availText}</span>
                    </div>
                    <div class="collapsed-actions">
                        <button type="button" class="btn btn-sm btn-primary edit-collapsed-variant" title="Expand to edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-variant-row" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td class="var-id-cell text-center">
                <input type="checkbox" class="variant-checkbox" aria-label="Select variant ${variant.var_id}">
                <br><small>${variant.var_id}</small>
                ${variant._isNew ? '' : `<input type="hidden" name="v[${formId}][var_id]" value="${variant.var_id}">`}
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" 
                       name="v[${formId}][name]" 
                       value="${variant.name || ''}" 
                       placeholder="Variant Name">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" 
                       name="v[${formId}][sku]" 
                       value="${variant.sku || ''}" 
                       placeholder="SKU">
            </td>
            <td class="collapsible-col">
                <select class="form-select form-select-sm" name="v[${formId}][ship_type]" data-form-id="${formId}" class="ship-type-select">
                    ${buildShipTypeOptions(variant.ship_type)}
                </select>
            </td>
            <td class="collapsible-col slug-col">
                <div class="slug-input-container">
                    <input type="text" class="form-control form-control-sm deliver-slug-input" 
                       name="v[${formId}][deliver_slug]" 
                       value="${variant.deliver_slug || ''}" 
                       placeholder="/slug" 
                       data-var-id="${formId}" 
                       data-form-id="${formId}">
                    <small class="text-danger slug-error-${formId}" style="display:none;"></small>
                </div>
            </td>
            <td class="collapsible-col options-column">
                ${optionsContent}
            </td>
            <td>
                <input type="number" class="form-control form-control-sm" 
                       name="v[${formId}][price]" 
                       value="${parseFloat(variant.price || 0).toFixed(2)}" 
                       step="0.01" 
                       min="0" 
                       placeholder="0.00">
            </td>
            <td class="collapsible-col">
                <select class="form-select form-select-sm" name="v[${formId}][taxable]">
                    <option value="0" ${variant.taxable == 0 ? 'selected' : ''}>No</option>
                    <option value="1" ${variant.taxable == 1 ? 'selected' : ''}>Yes</option>
                </select>
            </td>
            <td class="collapsible-col weight-cell text-center" data-form-id="${formId}">
                <div class="weight-inputs" style="display:flex;gap:4px;justify-content:center;align-items:flex-end;">
                    <div style="display:flex;flex-direction:column;">
                        <small class="text-muted" style="font-size:0.7rem;margin-bottom:2px;">lbs</small>
                        <input type="number" class="form-control form-control-sm weight-lbs-input" 
                               name="v[${formId}][weight_lbs]" 
                               value="${variant.weight_lbs || 0}" 
                               min="0" max="999" step="1"
                               style="width:60px;" 
                               placeholder="0">
                    </div>
                    <div style="display:flex;flex-direction:column;">
                        <small class="text-muted" style="font-size:0.7rem;margin-bottom:2px;">oz</small>
                        <input type="number" class="form-control form-control-sm weight-oz-input" 
                               name="v[${formId}][weight_oz]" 
                               value="${variant.weight_oz || 0}" 
                               min="0" max="15" step="1"
                               style="width:50px;" 
                               placeholder="0">
                    </div>
                </div>
                <span class="weight-na d-none text-muted" style="display:inline-block;" title="Not applicable for digital items">n/a</span>
            </td>
            <td class="collapsible-col qty-cell text-center" data-form-id="${formId}">
                <input type="number" class="form-control form-control-sm qty-input" 
                       name="v[${formId}][qty]" 
                       value="${parseInt(variant.qty || 0)}" 
                       min="0" max="1000" step="1"
                       placeholder="0">
                <span class="qty-infinity d-none fs-3" style="display:inline-block;" title="Unlimited">∞</span>
            </td>
            <td>
                <select class="form-select form-select-sm" name="v[${formId}][avail]">
                    <option value="0" ${variant.avail == 0 ? 'selected' : ''}>No</option>
                    <option value="1" ${variant.avail == 1 ? 'selected' : ''}>Yes</option>
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger delete-variant-row" aria-label="Delete variant">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        // Manually add class to options column cell after innerHTML is set
        setTimeout(() => {
            const optionsCell = row.querySelector('td:nth-child(8)'); // 8th td is options column (after adding collapse button)
            if(optionsCell && !optionsCell.classList.contains('options-column')){
                optionsCell.classList.add('options-column');
            }
        }, 0);
        
        // Collapse existing variants by default, keep new variants expanded
        if(!variant._isNew){
            row.classList.add('collapsed');
        }
        
        return row;
    }
    
    // Make functions globally accessible for products.blade.php AJAX handler
    window.createVariantRow = createVariantRow;
    window.toggleSlugRequired = toggleSlugRequired;
    window.toggleWeightUI = toggleWeightUI;
    window.toggleQtyUI = toggleQtyUI;
    window.toggleOptionsColumnVisibility = toggleOptionsColumnVisibility;
    
    // Toggle individual row collapse/expand
    document.getElementById('variantsTableBody').addEventListener('click', function(e){
        const toggleBtn = e.target.closest('.collapse-toggle');
        if(toggleBtn){
            const row = toggleBtn.closest('tr');
            const icon = toggleBtn.querySelector('i');
            row.classList.toggle('collapsed');
            
            // Swap icon based on state
            if(row.classList.contains('collapsed')){
                // Collapsed state - show chevron down
                icon.className = 'fas fa-chevron-down';
            } else {
                // Expanded state - show arrows to dotted line
                icon.className = 'fa-light fa-arrows-to-dotted-line';
            }
        }
        
        // Handle edit button on collapsed row
        const editBtn = e.target.closest('.edit-collapsed-variant');
        if(editBtn){
            const row = editBtn.closest('tr');
            row.classList.remove('collapsed');
            // Update icon when expanding via edit button
            const toggleBtn = row.querySelector('.collapse-toggle');
            if(toggleBtn){
                const icon = toggleBtn.querySelector('i');
                if(icon) icon.className = 'fa-light fa-arrows-to-dotted-line';
            }
        }
    });
    
    // Expand all variants
    document.getElementById('expand-all-variants').addEventListener('click', function(){
        document.querySelectorAll('#variantsTableBody tr').forEach(row => {
            row.classList.remove('collapsed');
            // Update icons to expanded state
            const icon = row.querySelector('.collapse-toggle i');
            if(icon) icon.className = 'fa-light fa-arrows-to-dotted-line';
        });
    });
    
    // Collapse all variants
    document.getElementById('collapse-all-variants').addEventListener('click', function(){
        document.querySelectorAll('#variantsTableBody tr').forEach(row => {
            row.classList.add('collapsed');
            // Update icons to collapsed state
            const icon = row.querySelector('.collapse-toggle i');
            if(icon) icon.className = 'fas fa-chevron-down';
        });
    });

    // Delete single variant row
    document.getElementById('variantsTableBody').addEventListener('click', function(e){
        if(e.target.closest('.delete-variant-row')){
            const btn = e.target.closest('.delete-variant-row');
            const row = btn.closest('tr');
            const varId = row.dataset.varId;
            const isNew = row.dataset.isNew === 'true';
            const variantName = row.querySelector('input[name*="[name]"]')?.value || 'this variant';
            
            // Check if this is a new unsaved variant (varId is 'NEW' or negative number, or isNew flag is true)
            if(isNew || varId === 'NEW' || parseInt(varId) < 0){
                // New unsaved variant - just remove from DOM
                if(confirm(`Remove "${variantName}" from this form?\n\nThis variant hasn't been saved yet.`)){
                    row.remove();
                    updateSelectedCount();
                }
            } else {
                // Existing variant - delete immediately via AJAX
                const varId = row.dataset.varId;
                
                if(confirm(`Delete variant "${variantName}"?\n\nThis will delete immediately and cannot be undone.`)){
                    const productId = document.getElementById('product_id')?.value;
                    
                    // Show loading state
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                    
                    fetch(`/${window.path || 'educators'}/backend/products/variants/delete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            var_id: varId
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if(data.error){
                            window.showToast(data.message || 'Failed to delete variant', 'error');
                            // Restore button
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-trash"></i>';
                        } else {
                            window.showToast(data.message || 'Variant deleted successfully', 'success');
                            // Remove row from DOM
                            row.remove();
                            updateSelectedCount();
                            
                            // Update button color in products table
                            updateProductButtonColor(productId);
                            
                            // Reload products table if available
                            if(typeof productsTable !== 'undefined' && productsTable.ajax){
                                productsTable.ajax.reload(null, false);
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Delete error:', err);
                        window.showToast('Request failed: ' + (err.message || 'Unknown error'), 'error');
                        // Restore button
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash"></i>';
                    });
                }
            }
        }
    });

    // Validate slugs on blur
    document.getElementById('variantsTableBody').addEventListener('blur', function(e){
        if(e.target.classList.contains('deliver-slug-input')){
            const input = e.target;
            const varId = input.dataset.varId;
            const formId = input.dataset.formId;
            const errorSpan = document.querySelector('.slug-error-' + varId);
            const slug = input.value.trim();
            
            // Skip validation if row is marked for deletion
            const row = input.closest('tr');
            if(row && row.dataset.markedForDeletion === 'true'){
                input.classList.remove('is-invalid');
                if(errorSpan) errorSpan.style.display = 'none';
                return;
            }
            
            // Check if required for digital
            if(isVariantDigital(formId) && !slug){
                if(errorSpan){
                    errorSpan.textContent = 'Delivery slug is required for digital variants';
                    errorSpan.style.display = 'block';
                }
                input.classList.add('is-invalid');
                return;
            }
            
            if(!slug){
                if(errorSpan) errorSpan.style.display = 'none';
                input.classList.remove('is-invalid');
                return;
            }
            
            // Check for invalid characters
            const invalidChars = slug.match(/[^a-z0-9\-\/]/gi);
            if(invalidChars){
                if(errorSpan){
                    errorSpan.textContent = 'Only letters, numbers, hyphens, and slashes allowed';
                    errorSpan.style.display = 'block';
                }
                input.classList.add('is-invalid');
                return;
            }
            
            // Clean slug
            input.value = slug.toLowerCase().replace(/[^a-z0-9\-\/]/g, '');
            input.classList.remove('is-invalid');
            if(errorSpan) errorSpan.style.display = 'none';
        }
    }, true);

    function isVariantDigital(formId){
        const shipTypeSel = document.querySelector(`select[name="v[${formId}][ship_type]"]`);
        if(!shipTypeSel) return false;
        const shipTypeValue = parseInt(shipTypeSel.value, 10);
        return shipTypeValue === 0; // 0 = Digital, 1 = Standard (physical), 2 = Media (physical)
    }

    function toggleSlugRequired(formId){
        const slugInput = document.querySelector(`.deliver-slug-input[data-form-id="${formId}"]`);
        if(!slugInput) return;
        const isDigital = isVariantDigital(formId);
        if(isDigital){
            slugInput.setAttribute('required', 'required');
            slugInput.classList.add('border-primary');
        } else {
            slugInput.removeAttribute('required');
            slugInput.classList.remove('border-primary');
        }
    }

    function toggleWeightUI(formId){
        const cell = document.querySelector(`.weight-cell[data-form-id="${formId}"]`);
        if(!cell) return;
        const weightInputs = cell.querySelector('.weight-inputs');
        const lbsInput = cell.querySelector('.weight-lbs-input');
        const ozInput = cell.querySelector('.weight-oz-input');
        const naSpan = cell.querySelector('.weight-na');
        const isDigital = isVariantDigital(formId);
        if(isDigital){
            if(weightInputs) weightInputs.classList.add('d-none');
            if(lbsInput){
                lbsInput.value = '0';
                lbsInput.disabled = true;
            }
            if(ozInput){
                ozInput.value = '0';
                ozInput.disabled = true;
            }
            if(naSpan) naSpan.classList.remove('d-none');
        } else {
            if(weightInputs) weightInputs.classList.remove('d-none');
            if(lbsInput) lbsInput.disabled = false;
            if(ozInput) ozInput.disabled = false;
            if(naSpan) naSpan.classList.add('d-none');
        }
    }



    function toggleQtyUI(formId){
        const cell = document.querySelector(`.qty-cell[data-form-id="${formId}"]`);
        if(!cell) return;
        const input = cell.querySelector('.qty-input');
        const infinity = cell.querySelector('.qty-infinity');
        const isDigital = isVariantDigital(formId);
        const existingHidden = cell.querySelector('input.qty-hidden');
        if(isDigital){
            if(input){
                // Store current value before hiding (but not if it's already the digital marker)
                const currentValue = input.value;
                if(currentValue && parseInt(currentValue) !== 100000 && parseInt(currentValue) >= 0){
                    input.dataset.originalValue = currentValue;
                }
                input.removeAttribute('name');
                input.value = '100000'; // internal marker only
                input.classList.add('d-none');
                input.disabled = true; // disable to bypass validation
                input.removeAttribute('min');
                input.removeAttribute('max');
                input.removeAttribute('step');
            }
            if(infinity) infinity.classList.remove('d-none');
            if(!existingHidden){
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `v[${formId}][qty]`;
                hidden.value = '100000';
                hidden.className = 'qty-hidden';
                cell.appendChild(hidden);
            } else {
                existingHidden.value = '100000';
            }
        } else {
            if(input){
                input.classList.remove('d-none');
                if(!input.name) input.name = `v[${formId}][qty]`;
                input.min = '0';
                input.max = '1000';
                input.step = '1';
                // Restore original value if it was cached
                if(input.dataset.originalValue){
                    input.value = input.dataset.originalValue;
                    delete input.dataset.originalValue;
                } else {
                    const current = parseInt(input.value, 10);
                    if(isNaN(current) || current > 1000 || current === 100000){ input.value = '0'; }
                }
                input.disabled = false;
            }
            if(infinity) infinity.classList.add('d-none');
            if(existingHidden) existingHidden.remove();
        }
    }

    // React to ship_type selection changes
    document.addEventListener('change', function(e){
        const sel = e.target && e.target.matches('select[name^="v["][name$="][ship_type]"]') ? e.target : null;
        if(!sel) return;
        const m = sel.name.match(/^v\[(.+)\]\[ship_type\]$/);
        if(!m) return;
        const formId = m[1];
        toggleSlugRequired(formId);
        toggleWeightUI(formId);
        toggleQtyUI(formId);
    });

    // React to option selection changes - validate for duplicate size-color combinations
    document.addEventListener('change', function(e){
        const select = e.target;
        
        // Check if this is an option select (inside options-container)
        if(select && select.closest('.options-container')){
            // Re-validate on any option change
            validateUniqueSizeColorCombinations();
        }
    });

    // Validate that no duplicate size-color combinations exist
    function validateUniqueSizeColorCombinations(){
        const productCategory = window.currentProductCategory || 0;
        
        // Only validate for categories that have options (4=games, 5=swag)
        if(![4, 5].includes(productCategory)){
            return true; // No validation needed for other categories
        }
        
        // Collect all size-color combinations from non-deleted variants
        const combinations = {};
        let duplicateFound = false;
        
        document.querySelectorAll('tr:not([data-marked-for-deletion="true"])').forEach(row => {
            // Get all option selects in this variant row
            const optionSelects = row.querySelectorAll('.options-container select');
            
            if(optionSelects.length === 0) return; // Skip if no options
            
            // Extract color and size values
            let colorValue = '';
            let sizeValue = '';
            
            optionSelects.forEach(select => {
                const optionLabel = select.previousElementSibling;
                const labelText = optionLabel ? optionLabel.textContent.toLowerCase().trim() : '';
                const selectedValue = select.value;
                
                if(labelText.includes('color')){
                    colorValue = selectedValue;
                } else if(labelText.includes('size')){
                    sizeValue = selectedValue;
                }
            });
            
            // Skip if either value is missing (not fully specified)
            if(!colorValue || !sizeValue) return;
            
            // Create a unique key for this combination
            const key = `${colorValue}|${sizeValue}`;
            
            // Check if this combination already exists
            if(combinations[key]){
                duplicateFound = true;
                // Highlight both rows with the duplicate
                row.classList.add('table-danger');
                combinations[key].row.classList.add('table-danger');
                
                // Mark the option selects for visual indication
                row.querySelectorAll('.options-container select').forEach(select => {
                    select.classList.add('is-invalid');
                });
                combinations[key].row.querySelectorAll('.options-container select').forEach(select => {
                    select.classList.add('is-invalid');
                });
            } else {
                combinations[key] = {row: row};
                // Clear any previous error styling if this is not a duplicate
                row.classList.remove('table-danger');
                row.querySelectorAll('.options-container select').forEach(select => {
                    select.classList.remove('is-invalid');
                });
            }
        });
        
        if(duplicateFound){
            window.showToast('Error: Cannot have duplicate size-color combinations. Example: You can have 1 black SM and 1 blue SM, but not 2 black SM.', 'error');
            return false;
        }
        
        return true;
    }

    // Form submission
    document.getElementById('edit_variants').addEventListener('submit', function(e){
        e.preventDefault();
        
        // Validate unique size-color combinations first
        if(!validateUniqueSizeColorCombinations()){
            return;
        }
        
        // Validate digital variants have delivery slugs (except those marked for deletion)
        let hasError = false;
        document.querySelectorAll('tr:not([data-marked-for-deletion="true"]) .deliver-slug-input').forEach(input => {
            const formId = input.dataset.formId;
            if(isVariantDigital(formId) && !input.value.trim()){
                input.classList.add('is-invalid');
                const errorSpan = document.querySelector('.slug-error-' + formId);
                if(errorSpan){
                    errorSpan.textContent = 'Delivery slug is required for digital variants';
                    errorSpan.style.display = 'block';
                }
                hasError = true;
            }
        });
        
        if(hasError){
            window.showToast('Please fix validation errors before saving', 'error');
            return;
        }
        
        const btn = document.getElementById('save-variants-btn');
        const spinner = btn.querySelector('.spinner-border');
        
        spinner.classList.remove('d-none');
        btn.disabled = true;
        
        const formData = new FormData(this);
        
        // Add sort order based on current row positions in the DOM
        // For swag (category 5), auto-sort by color (alphabetical) then size (XS->4XL)
        const productCategory = window.currentProductCategory || 0;
        const rows = Array.from(document.querySelectorAll('#variantsTableBody tr:not([data-marked-for-deletion="true"])'));
        
        let sortedRows = rows;
        
        if(productCategory === 5) {
            // Auto-sort swag by color then size
            const sizeOrder = ['xs', 'sm', 's', 'med', 'm', 'lrg', 'l', 'xl', '2xl', '3xl', '4xl'];
            
            sortedRows = rows.slice().sort((rowA, rowB) => {
                // Extract color and size from each row's option selects
                const getColorSize = (row) => {
                    let color = '';
                    let size = '';
                    const optionSelects = row.querySelectorAll('.options-container select');
                    optionSelects.forEach(select => {
                        const label = select.previousElementSibling;
                        const labelText = label ? label.textContent.toLowerCase().trim() : '';
                        const selectedOption = select.options[select.selectedIndex];
                        const selectedText = selectedOption ? selectedOption.textContent.toLowerCase().trim() : '';
                        
                        if(labelText.includes('color')) {
                            color = selectedText;
                        } else if(labelText.includes('size')) {
                            size = selectedText;
                        }
                    });
                    return { color, size };
                };
                
                const a = getColorSize(rowA);
                const b = getColorSize(rowB);
                
                // First sort by color (alphabetical)
                if(a.color !== b.color) {
                    return a.color.localeCompare(b.color);
                }
                
                // Then sort by size (using sizeOrder array)
                const aIndex = sizeOrder.indexOf(a.size);
                const bIndex = sizeOrder.indexOf(b.size);
                
                if(aIndex !== -1 && bIndex !== -1) {
                    return aIndex - bIndex;
                }
                if(aIndex !== -1) return -1;
                if(bIndex !== -1) return 1;
                
                // Fallback to alphabetical for unknown sizes
                return a.size.localeCompare(b.size);
            });
        }
        
        // Assign sort order based on sorted array
        sortedRows.forEach((row, index) => {
            const varId = row.dataset.varId;
            const formId = row.dataset.isNew === 'true' ? row.dataset.tempId : varId;
            const sortValue = index + 1;
            formData.set(`v[${formId}][sort]`, sortValue);
        });
        
        // Add marked for deletion
        document.querySelectorAll('tr[data-marked-for-deletion="true"]').forEach(row => {
            const varId = row.dataset.varId;
            formData.append('delete[]', varId);
        });
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            spinner.classList.add('d-none');
            btn.disabled = false;
            
            if(data.error){
                window.showToast(data.message || 'Failed to save variants', 'error');
            } else {
                window.showToast(data.message || 'Variants saved successfully', 'success');
                
                // Update the dropdown button color in the products table
                const productId = document.getElementById('product_id')?.value;
                if(productId && data.variants !== undefined){
                    // Check if any variants exist AND are available
                    const hasVariants = Array.isArray(data.variants) && data.variants.length > 0;
                    const hasAvailableVariant = hasVariants && data.variants.some(v => v.avail == 1);
                    
                    // Find the dropdown button for this product in the table (must look in main document, not modal)
                    const productRow = document.querySelector(`#productsTable tr[data-product-id="${productId}"]`);
                    if(productRow){
                        const dropdownBtn = productRow.querySelector('.btn-group .dropdown-toggle');
                        const productCategory = parseInt(productRow.dataset.category || '0');
                        
                        if(dropdownBtn){
                            // Only update red indicator for non-curriculum products (category != 1)
                            if(productCategory === 1){
                                // Curriculum - always show secondary (never red)
                                dropdownBtn.classList.remove('btn-danger');
                                dropdownBtn.classList.add('btn-secondary');
                                dropdownBtn.removeAttribute('title');
                            } else {
                                // Other categories - show red if no variants OR no available variants
                                if(hasAvailableVariant){
                                    dropdownBtn.classList.remove('btn-danger');
                                    dropdownBtn.classList.add('btn-secondary');
                                    dropdownBtn.removeAttribute('title');
                                } else {
                                    dropdownBtn.classList.remove('btn-secondary');
                                    dropdownBtn.classList.add('btn-danger');
                                    const msg = hasVariants ? 'No available variants' : 'No variants';
                                    dropdownBtn.setAttribute('title', msg);
                                }
                            }
                        }
                    }
                    
                    // Reload variants table with sorted data (keep modal open)
                    reloadVariantsTable(data.variants, data.option_assignments || {});
                    
                    // Reset new variant counter
                    newVariantCounter = 0;
                    
                    // Reload the products table in background
                    if(typeof productsTable !== 'undefined' && productsTable.ajax){
                        productsTable.ajax.reload(null, false);
                    }
                }
            }
        })
        .catch(err => {
            console.error('Variant save error:', err);
            spinner.classList.add('d-none');
            btn.disabled = false;
            window.showToast('Request failed: ' + (err.message || 'Unknown error'), 'error');
        });
    });

    // Reload variants table after save (keeps modal open)
    function reloadVariantsTable(variants, assignments = {}){
        const tbody = document.getElementById('variantsTableBody');
        tbody.innerHTML = '';
        
        // Update both local and window global assignments if provided
        if(Object.keys(assignments).length > 0){
            variantAssignments = assignments;
            window.variantAssignments = assignments;
        }
        
        // Rebuild assignments from variant data if included
        variants.forEach(variant => {
            if(variant.option_assignments && variant.option_assignments.length > 0){
                const valueIds = variant.option_assignments.map(a => a.value_id);
                variantAssignments[variant.var_id] = valueIds;
                window.variantAssignments[variant.var_id] = valueIds;
            }
        });
        
        // Create rows for each variant (already sorted by backend)
        variants.forEach(variant => {
            const row = createVariantRow({
                var_id: variant.var_id,
                name: variant.name || '',
                sku: variant.sku || '',
                deliver_slug: variant.deliver_slug || '',
                option: '',
                price: variant.price || 0,
                taxable: variant.taxable ?? 1,
                ship_type: variant.ship_type || 0,
                weight_lbs: variant.weight_lbs || 0,
                weight_oz: variant.weight_oz || 0,
                qty: variant.qty || 0,
                avail: variant.avail || 0,
                sort: variant.sort || 0,
                _isNew: false
            });
            tbody.appendChild(row);
            
            // Initialize UI based on ship_type
            const formId = variant.var_id;
            toggleSlugRequired(formId);
            toggleWeightUI(formId);
            toggleQtyUI(formId);
        });
        
        // Update options column visibility after rows are created
        toggleOptionsColumnVisibility();
        
        updateSelectedCount();
    }
    
    /**END**/
</script>








