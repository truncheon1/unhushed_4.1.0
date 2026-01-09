@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/backend') }}"> Backend</a> |
                <span style="font-weight: bold;color:#9acd57">Products</span>
            </div>
        </div>

        <!--  TABLE -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header container-fluid">
                        <div class="row">
                            <div class="col-md-7">PRODUCTS</div>
                            <div class="col-md-5 text-right">
                                @if(auth()->user()->can('access-master') || auth()->user()->can('modify-products'))
                                <a class="btn btn-secondary btn-sm add-product" aria-label="Add new product">ADD</a>
                                <a href="#" class="btn btn-secondary btn-sm disabled" id="deleteSelectedProducts" aria-label="Delete selected products" aria-disabled="true">DELETE (0)</a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Product options actions">OPTIONS</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item edit-options-master" href="#" aria-label="Edit option master list">Master</a></li>
                                        <li><a class="dropdown-item edit-options-values" href="#" aria-label="Edit option values assignments">Values</a></li>
                                    </ul>
                                </div>
                                <a class="btn btn-secondary btn-sm edit-tags" aria-label="Edit product tags">TAGS</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="productsTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align:center"><input type="checkbox" id="selectAllProducts" aria-label="Select all products" /></th>
                                    <th id="image" colspan="1" style="text-align:center">Image</th>
                                    <th id="name" colspan="1" style="text-align:left">Name</th>
                                    <th id="category" colspan="1" style="text-align:center">Category</th>
                                    <th id="sort" colspan="1" style="text-align:center">Sort</th>
                                    <th id="edit" colspan="1" style="text-align:center">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                @php($variant = $product->vars->first() ?? null)
                                @php($hasAvailableVariant = $product->vars->where('avail', 1)->isNotEmpty())
                                @php($isCurriculum = $product->category == 1)
                                @php($showRedIndicator = !$isCurriculum && !$hasAvailableVariant)
                                <tr id="row_{{$product->id}}" data-product-id="{{$product->id}}" data-category="{{$product->category}}">
                                    <!-- checkbox -->
                                    <td style="text-align:center"><input type="checkbox" class="product-select" value="{{$product->id}}" aria-label="Select product {{$product->name}}" /></td>
                                    <!-- image -->
                                    <td colspan="1" style="text-align:center">
                                        @php($thumb = $product->primary_image_path)
                                        @if($thumb && $thumb !== 'placeholder.png')
                                            <img class="tinyImg" loading="lazy" src="{{ url('/uploads/products/'.$thumb) }}" alt="Product image: {{$product->name}}" />
                                        @else
                                            <span class="text-muted" style="font-size:0.65rem" aria-label="No image for {{$product->name}}">NO IMG</span>
                                        @endif
                                    </td>
                                    <!-- name -->
                                    <td colspan="1" style="text-align:left">{{$product->name}}</td>
                                    <!-- category -->
                                    <td colspan="1" style="text-align:center" data-order="{{$product->category}}">{{$categories[$product->category] ?? 'n/a'}}</td>
                                    <!-- sort -->
                                    <td colspan="1" style="text-align:center" data-order="{{ (int)($product->sort ?? 0) }}">{{$product->category ?? ''}}-{{$product->sort ?? ''}}</td>
                                    <!-- edit dropdown -->
                                    <td colspan="1" style="text-align:center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle {{ $showRedIndicator ? 'btn-danger' : 'btn-secondary' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Product actions for {{$product->name}}" {{ $showRedIndicator ? 'title="No available variants"' : '' }}>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="dropdown-menu mt-1">
                                                <!-- basic info-->
                                                <a class="dropdown-item text-dark product-edit-link" rel="{{$product->id}}" data-product-id="{{$product->id}}" href="#" aria-label="Edit product info for {{$product->name}}">Basic Info</a>
                                                <!-- variants (all products except curriculum) -->
                                                 @if(($categories[$product->category] ?? '') != 'curriculum')
                                                <a class="dropdown-item text-dark variant-edit-link" rel="{{$product->id}}" data-product-id="{{$product->id}}" href="#" aria-label="Edit product variants for {{$product->name}}">Variants</a>
                                                <!-- images -->
                                                @endif
                                                <a class="dropdown-item text-dark product-images-link" rel="{{$product->id}}" href="{{ url($path.'/backend/products/'.$product->id) }}/images" aria-label="Manage images for {{$product->name}}">Images</a>
                                                <!-- descriptions -->
                                                <a class="dropdown-item text-dark product-descriptions-link" rel="{{$product->id}}" href="{{ url($path.'/backend/products/'.$product->id) }}/descriptions" aria-label="Manage descriptions for {{$product->name}}">Descriptions</a>
                                                <!-- Curriculum files (only curriculum) -->
                                                @if(($categories[$product->category] ?? '') == 'curriculum')
                                                <a class="dropdown-item text-dark edit-license-pricing-link" rel="{{$product->id}}" data-product-id="{{$product->id}}" href="#" aria-label="Edit license pricing for curriculum {{$product->name}}">License Pricing</a>
                                                <a class="dropdown-item text-dark"                  rel="{{$product->id}}"  href="{{ url($path.'/backend/packages/'.$product->id) }}/dashboard" aria-label="Open curriculum dashboard for {{$product->name}}">Curriculum</a>
                                                <!-- Files (products with digital files) -->
                                                @elseif(!in_array($categories[$product->category] ?? '', ['curriculum', 'training', 'swag']))
                                                <a class="dropdown-item product-activity-link" rel="{{$product->id}}"  href="{{ url($path.'/backend/products/'.$product->id) }}/activity_d" aria-label="Manage files for {{$product->name}}">Files</a>
                                                @endif
                                                <!-- delete -->
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger product-delete-link" rel="{{$product->id}}"  href="{{ url($path.'/backend/products/'.$product->id) }}/delete" aria-label="Delete product {{$product->name}}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot><tr><td colspan="6"></td></tr></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD PRODUCT MODAL CALL -->
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="addProductLabel">ADD PRODUCT</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/create')
            </div>
        </div>
    </div>
    <!-- EDIT PRODUCT MODAL CALL -->
    <div class="modal fade" id="editProductMaster" tabindex="-1" role="dialog" aria-labelledby="editProductMasterLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editProductMasterLabel">EDIT INFO FOR <span id="variantProductName" class="fst-italic"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-product')
            </div>
        </div>
    </div>
    <!-- EDIT TAGS MODAL CALL -->
    <div class="modal fade" id="editTags" tabindex="-1" role="dialog" aria-labelledby="editTagsLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editTagsLabel">EDIT TAGS</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-tags')
            </div>
        </div>
    </div>
    <!-- EDIT OPTIONS MODAL CALL -->
    <div class="modal fade" id="editOptions" tabindex="-1" role="dialog" aria-labelledby="editOptionsLabel" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editOptionsLabel">EDIT OPTIONS</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-options')
            </div>
        </div>
    </div>
    <!-- EDIT OPTION VALUES MODAL CALL -->
    <div class="modal fade" id="editOptionValues" tabindex="-1" role="dialog" aria-labelledby="editOptionValuesLabel" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editOptionValuesLabel">EDIT OPTION VALUES</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-option-values')
            </div>
        </div>
    </div>
    <!-- ACTIVITIES MODAL CALL -->
    <div class="modal fade" id="editActivities" tabindex="-1" role="dialog" aria-labelledby="editActivitiesLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editActivitiesLabel">ACTIVITIES FOR <span id="activitiesProductName" class="fst-italic"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/activities')
            </div>
        </div>
    </div>
    <!-- PRODUCT IMAGES MODAL CALL -->
    <div class="modal fade" id="editProductImages" tabindex="-1" role="dialog" aria-labelledby="editProductImagesLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editProductImagesLabel">IMAGES FOR <span id="imagesProductName" class="fst-italic"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-images')
            </div>
        </div>
    </div>
    <!-- PRODUCT DESCRIPTIONS MODAL CALL -->
    <div class="modal fade" id="editProductDescriptions" tabindex="-1" role="dialog" aria-labelledby="editProductDescriptionsLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editProductDescriptionsLabel">DESCRIPTIONS FOR <span id="descriptionsProductName" class="fst-italic"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-descriptions')
            </div>
        </div>
    </div>
    <!-- EDIT VARIANTS MODAL CALL -->
    <div class="modal fade" id="editVariants" tabindex="-1" role="dialog" aria-labelledby="editVariantsLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title" id="editVariantsLabel">EDIT VARIANTS FOR <span id="variantProductNameVariants" class="fst-italic"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <!-- Modal Body -->
                @include('backend/products/edit-vars')
            </div>
        </div>
    </div>
    <!-- EDIT LICENSE PRICING MODAL -->
    <div class="modal fade" id="editLicensePricing" tabindex="-1" role="dialog" aria-labelledby="editLicensePricingLabel" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editLicensePricingLabel">EDIT LICENSE PRICING FOR <span id="licensePricingProductName" class="fst-italic"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="addLicenseRowBtn">+ Add License</button>
                        <button type="button" class="btn btn-danger btn-sm" id="deleteSelectedLicensesBtn" disabled>Delete Selected (0)</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="saveAllLicensesBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Save All Changes
                        </button>
                    </div>
                    <div class="table-responsive" style="max-height:480px;overflow-y:auto;">
                        <table class="table table-sm table-bordered align-middle" id="licensePricingTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;text-align:center;"><input type="checkbox" id="selectAllLicenses" aria-label="Select all licenses"/></th>
                                    <th>Caption</th>
                                    <th style="width:90px;">Min</th>
                                    <th style="width:90px;">Max</th>
                                    <th style="width:110px;">Standard</th>
                                    <th style="width:110px;">Discount</th>
                                    <th style="width:110px;">Yearly</th>
                                    <th style="width:60px;text-align:center;">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="licensePricingTbody">
                                <tr><td colspan="8" class="text-center text-muted">Select a curriculum product to view tiers.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css" media="all">
        .dropdown-menu::before{ display:none; }
        .dz-details .dz-span { width:110px; position:relative; top:-100px; text-align:center; display:inline-block; }
        .dz-image img{ max-width:200px; }
        .dz-overlayer { width:109px; height:109px; position:relative; top:-109px; background-color:#000; opacity:0.1; }
        .dz-preview{ margin:10px; }
        .dz-span img { cursor:pointer; border:1px solid #000; border-radius:50%; padding:2px; margin-bottom:5px; background-color:#000; }
        .img-template-edit .dz-preview, .img-template .dz-preview { display:none; }
        .preview-container, .preview-container-edit { width:100%; display:flex; flex-wrap:wrap; }
        #imageUploadWide, #imageUploadWideEdit { display:flex; align-items:center; background-color:#f7f7f7; border-bottom:1px solid #0000001A; text-align:center; color:#787878; font-size:12px; min-height:100px; }
        .modal-header { background-color:#f7f7f7; }
        .modal-dialog{ overflow-y:initial !important; }
        .modal-body{ overflow-y:auto; }
        .needsclick{ cursor:pointer; }
    </style>
    <script>
    // INLINE LICENSE PRICING UX (clean script block)
    function updateLicenseDeleteState(){
        const count = document.querySelectorAll('#licensePricingTbody .license-select:checked').length;
        const btn = document.getElementById('deleteSelectedLicensesBtn');
        if(btn){ btn.textContent = `Delete Selected (${count})`; btn.disabled = count === 0; }
    }
    document.addEventListener('change', function(e){
        if(e.target && e.target.id === 'selectAllLicenses'){
            const checked = e.target.checked;
            document.querySelectorAll('#licensePricingTbody .license-select').forEach(cb=>{ cb.checked = checked; });
            updateLicenseDeleteState();
        }
        if(e.target && e.target.classList.contains('license-select')){ updateLicenseDeleteState(); }
    });
    document.addEventListener('click', function(e){
        const addBtn = e.target.closest('#addLicenseRowBtn');
        if(addBtn){
            e.preventDefault();
            const tbody = document.getElementById('licensePricingTbody');
            if(!tbody) return;
            if(tbody.children.length === 1 && tbody.children[0].querySelector('.text-muted')){ tbody.innerHTML=''; }
            const tr = document.createElement('tr');
            tr.dataset.newRow = 'true';
            tr.innerHTML = `
                <td class="text-center"><input type="checkbox" class="license-select" aria-label="Select license tier"></td>
                <td><input type="text" class="form-control form-control-sm license-caption" value="" placeholder="Caption" /></td>
                <td><input type="number" class="form-control form-control-sm license-min" min="0" value="0" /></td>
                <td><input type="number" class="form-control form-control-sm license-max" min="1" value="1" /></td>
                <td><input type="number" class="form-control form-control-sm license-standard" step="0.01" min="0" value="0.00" /></td>
                <td><input type="number" class="form-control form-control-sm license-discount" step="0.01" min="0" value="0.00" /></td>
                <td><input type="number" class="form-control form-control-sm license-recurring" step="0.01" min="0" value="0.00" /></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger single-license-delete" title="Delete"><i class="fas fa-trash"></i></button></td>`;
            tbody.appendChild(tr);
        }
        const delBtn = e.target.closest('.single-license-delete');
        if(delBtn){
            e.preventDefault();
            const tr = delBtn.closest('tr');
            const tierId = tr.dataset.tierId;
            if(tierId){
                if(!confirm('Delete this license tier?')) return;
                const path = window.path || 'educators';
                fetch(`/${path}/backend/products/${window.licensePricingProductId}/license-pricing/${tierId}`, { method:'DELETE', headers:{'X-Requested-With':'XMLHttpRequest'} })
                    .then(r=>r.json())
                    .then(data=>{
                        if(data.error){ showToast(data.message || 'Delete failed','error'); return; }
                        showToast('License deleted','success');
                        tr.remove();
                        updateLicenseDeleteState();
                    })
                    .catch(()=> showToast('Request failed','error'));
            } else {
                tr.remove();
                updateLicenseDeleteState();
            }
        }
        const batchBtn = e.target.closest('#deleteSelectedLicensesBtn');
        if(batchBtn){
            e.preventDefault();
            const rows = Array.from(document.querySelectorAll('#licensePricingTbody tr')).filter(r=> r.querySelector('.license-select')?.checked);
            if(rows.length === 0) return;
            if(!confirm(`Delete ${rows.length} selected license tier(s)?`)) return;
            let failed = 0;
            rows.forEach(r=>{
                const id = r.dataset.tierId;
                if(!id){ r.remove(); return; }
                const path = window.path || 'educators';
                fetch(`/${path}/backend/products/${window.licensePricingProductId}/license-pricing/${id}`, { method:'DELETE', headers:{'X-Requested-With':'XMLHttpRequest'} })
                    .then(resp=>resp.json())
                    .then(data=>{ if(data.error){ failed++; } else { r.remove(); } updateLicenseDeleteState(); })
                    .catch(()=>{ failed++; updateLicenseDeleteState(); });
            });
            setTimeout(()=>{ showToast(`Deleted ${rows.length - failed} license(s).${failed? ' '+failed+' failed.':''}`, failed? 'error':'success'); }, 500);
        }
        const saveBtn = e.target.closest('#saveAllLicensesBtn');
        if(saveBtn){
            e.preventDefault();
            const spinner = saveBtn.querySelector('.spinner-border');
            spinner.classList.remove('d-none');
            saveBtn.disabled = true;
            const path = window.path || 'educators';
            const rows = Array.from(document.querySelectorAll('#licensePricingTbody tr')).filter(r=> !r.querySelector('.text-muted'));
            if(rows.length === 0){ spinner.classList.add('d-none'); saveBtn.disabled=false; return; }
            let creates = 0, updates = 0, fails = 0;
            (async function(){
                for(const r of rows){
                    const caption = r.querySelector('.license-caption')?.value.trim();
                    const min = r.querySelector('.license-min')?.value;
                    const max = r.querySelector('.license-max')?.value;
                    const standard = r.querySelector('.license-standard')?.value;
                    const discount = r.querySelector('.license-discount')?.value;
                    const recurring = r.querySelector('.license-recurring')?.value;
                    if(!caption || min==='' || max==='' || parseInt(min)>parseInt(max)) { fails++; continue; }
                    const body = new URLSearchParams({ caption, min_users:min, max_users:max, standard:standard, discount:discount, recurring:recurring });
                    const id = r.dataset.tierId;
                    const method = id? 'PATCH':'POST';
                    const endpoint = id? `/${path}/backend/products/${window.licensePricingProductId}/license-pricing/${id}` : `/${path}/backend/products/${window.licensePricingProductId}/license-pricing`;
                    try {
                        const resp = await fetch(endpoint, { method, headers:{'X-Requested-With':'XMLHttpRequest'}, body });
                        const json = await resp.json();
                        if(json.error){ fails++; continue; }
                        if(!id){ creates++; r.dataset.tierId = json.tier.id; }
                        else { updates++; }
                    } catch { fails++; }
                }
                spinner.classList.add('d-none');
                saveBtn.disabled = false;
                showToast(`Saved: ${creates} new, ${updates} updated, ${fails} failed`, fails? 'error':'success');
            })();
        }
    });
    </script>
    <script>
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
            
            if(!window.optionValues || window.optionValues.length === 0){
                return '<small class="text-muted">No options available</small>';
            }
            
            // Filter values based on option's allowed categories from database
            const filteredValues = window.optionValues.filter(v => {
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
            
            const assigned = window.variantAssignments[varId] || [];
            
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

        // Note: createVariantRow and helper functions are defined in edit-vars.blade.php

        function showToast(message, type = 'info'){
            const container = document.getElementById('toastContainer');
            if(!container) return alert(message); // fallback
            const id = 'toast_'+Date.now();
            const bg = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-secondary');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white ${bg} border-0`;
            toast.setAttribute('role','alert');
            toast.setAttribute('aria-live','assertive');
            toast.setAttribute('aria-atomic','true');
            toast.id = id;
            toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
            container.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, {delay: 3500});
            bsToast.show();
        }

        let productTypes = [];
        let digitalTypes = [];
        const metaEl = document.getElementById('product-meta');
        if(metaEl){
            try { const meta = JSON.parse(metaEl.textContent); productTypes = meta.categories || []; digitalTypes = meta.digital || []; } catch(e){ console.warn('Meta parse failed', e); }
        }

    // Backward compatibility: legacy scripts expect global arrays `categories` and sometimes `digital` / `product_assoc`
    window.categories = productTypes; // legacy name
    window.types = productTypes; // alias for types
    window.digital = digitalTypes; // legacy name (if referenced)
    window.product_assoc = productTypes; // alias for old product_assoc usage

    document.addEventListener('DOMContentLoaded', function(){
            const table = $('#productsTable').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pagingType: 'full_numbers',
                order: [[3,'asc'], [5,'asc']] // category col index 3, sort col index 5
            });
            const params = new URLSearchParams(window.location.search);
            const startPage = params.has('page') ? parseInt(params.get('page'),10) : 0;
            table.page(startPage).draw(false);
            $('#productsTable').on('page.dt', function(){
                const info = table.page.info();
                params.set('page', info.page);
                history.replaceState(null, '', '?'+params.toString());
            });

            // Single delete
            document.body.addEventListener('click', function(e){
                const el = e.target.closest('.product-delete-link');
                if(!el) return;
                e.preventDefault();
                if(!confirm('Delete this product?')) return;
                const url = el.getAttribute('href');
                const id = el.getAttribute('rel');
                fetch(url, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json'},
                    body: new URLSearchParams({id})
                }).then(r=>r.json()).then(r=>{
                    if(r.error){
                        showToast(r.message || 'Delete failed', 'error');
                    } else {
                        // Remove row from DataTable
                        const rowElement = document.querySelector('#row_'+id);
                        if(rowElement){
                            table.row(rowElement).remove().draw(false);
                        }
                        showToast('Product deleted', 'success');
                    }
                }).catch(()=> showToast('Request failed', 'error'));
            });

            // Checkbox selection & shift range
            const selectAll = document.getElementById('selectAllProducts');
            const deleteSelectedBtn = document.getElementById('deleteSelectedProducts');
            let lastChecked = null;

            function updateDeleteButton(){
                const count = document.querySelectorAll('.product-select:checked').length;
                // Update visible count
                deleteSelectedBtn.textContent = `DELETE (${count})`;
                if(count === 0){
                    deleteSelectedBtn.classList.add('disabled');
                    deleteSelectedBtn.setAttribute('aria-disabled','true');
                } else {
                    deleteSelectedBtn.classList.remove('disabled');
                    deleteSelectedBtn.setAttribute('aria-disabled','false');
                }
            }

            document.addEventListener('click', function(e){
                if(e.target && e.target.classList.contains('product-select')){
                    const checkboxes = Array.from(document.querySelectorAll('.product-select'));
                    if(e.shiftKey && lastChecked){
                        const start = checkboxes.indexOf(lastChecked);
                        const end = checkboxes.indexOf(e.target);
                        if(start > -1 && end > -1){
                            const [min, max] = start < end ? [start, end] : [end, start];
                            for(let i=min;i<=max;i++){ checkboxes[i].checked = true; }
                        }
                    }
                    lastChecked = e.target;
                    updateDeleteButton();
                }
            });

            if(selectAll){
                selectAll.addEventListener('change', function(){
                    document.querySelectorAll('.product-select').forEach(cb => { cb.checked = selectAll.checked; });
                    updateDeleteButton();
                });
            }

            // Batch delete
            deleteSelectedBtn && deleteSelectedBtn.addEventListener('click', function(){
                const ids = Array.from(document.querySelectorAll('.product-select:checked')).map(cb => cb.value);
                if(ids.length === 0) return;
                if(!confirm('Delete '+ids.length+' selected product(s)?')) return;
                const token = document.querySelector('meta[name="csrf-token"]').content;
                let success = 0, fail = 0;
                (async function(){
                    for(const id of ids){
                        const url = `{{ url($path.'/backend/products') }}/${id}/delete`;
                        try {
                            const r = await fetch(url, {method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}, body:new URLSearchParams({id})});
                            const json = await r.json();
                            if(json.error){ 
                                fail++; 
                            } else { 
                                success++; 
                                const rowElement = document.getElementById('row_'+id); 
                                if(rowElement){
                                    table.row(rowElement).remove();
                                }
                            }
                        } catch(e){ fail++; }
                    }
                    // Redraw table once after all deletions
                    table.draw(false);
                    showToast(`Deleted ${success} product(s). ${fail>0?fail+' failed.':''}`, fail>0?'error':'success');
                    updateDeleteButton();
                })();
            });
        });
    </script>
    <script>
    // Handlers for new OPTIONS dropdown selections
    document.addEventListener('click', function(e){
        const master = e.target.closest('.edit-options-master');
        if(master){
            e.preventDefault();
            const m = document.getElementById('editOptions');
            if(m && window.bootstrap){ 
                new bootstrap.Modal(m).show(); 
            } else if(m) {
                console.error('Bootstrap not loaded yet');
            }
            return;
        }
        const values = e.target.closest('.edit-options-values');
        if(values){
            e.preventDefault();
            const v = document.getElementById('editOptionValues');
            if(v && window.bootstrap){ 
                new bootstrap.Modal(v).show(); 
            } else if(v) {
                console.error('Bootstrap not loaded yet');
            }
            return;
        }
        // Open product edit modal
        const productLink = e.target.closest('.product-edit-link');
        if(productLink){
            e.preventDefault();
            const productId = productLink.getAttribute('data-product-id') || productLink.getAttribute('rel');
            if(!productId) return;
            // Get product name from the table row
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            const productName = row ? row.querySelector('td:nth-child(3)')?.textContent || '' : '';
            // Set the product name in the modal title
            const nameSpan = document.getElementById('variantProductName');
            if(nameSpan){ nameSpan.textContent = productName; }
            // Open the modal (actual form loading should be handled by existing include)
            const modalEl = document.getElementById('editProductMaster');
            if(modalEl && window.bootstrap){ new bootstrap.Modal(modalEl).show(); } else if(modalEl){ console.error('Bootstrap not loaded yet'); }
            return;
        }
        // Open variants modal
        const variantsLink = e.target.closest('.variant-edit-link');
        if(variantsLink){
            e.preventDefault();
            const productId = variantsLink.getAttribute('data-product-id') || variantsLink.getAttribute('rel');
            if(!productId) return;
            const path = window.path || 'educators';
            const url = `/${path}/backend/products/${productId}/variants`;
            fetch(url, { headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} })
                .then(r=> r.json())
                .then(data=>{
                    if(data.error){ 
                        showToast(data.message || 'Failed to load variants','error'); 
                        return; 
                    }
                    // Cache option values + assignments globally for buildOptionsSelect()
                    window.optionValues = data.values || [];
                    window.variantAssignments = data.assignments || {};
                    // Set product name & hidden product id
                    const nameSpan = document.getElementById('variantProductNameVariants');
                    if(nameSpan){ nameSpan.textContent = data.product?.name || ''; }
                    const hiddenPid = document.getElementById('product_id');
                    if(hiddenPid){ hiddenPid.value = productId; }
                    // Store product info globally for slug generation and option filtering
                    window.currentProductName = data.product?.name || '';
                    window.currentProductSlug = data.product?.slug || '';
                    window.currentProductCategory = data.product?.category || 0;
                    // Build rows
                    const tbody = document.getElementById('variantsTableBody');
                    if(tbody){
                        tbody.innerHTML = '';
                        const variants = data.variants || [];
                        if(variants.length === 0){
                            tbody.innerHTML = '<tr><td colspan=\"14\" class=\"text-center text-muted\">No variants yet. Click \"Add New Variant\".</td></tr>';
                        } else {
                            variants.forEach((v)=>{
                                try {
                                    const row = createVariantRow({
                                        var_id: v.var_id,
                                        name: v.name,
                                        sku: v.sku,
                                        deliver_slug: v.deliver_slug,
                                        price: v.price,
                                        taxable: v.taxable,
                                        ship_type: v.ship_type,
                                        weight_lbs: v.weight_lbs || 0,
                                        weight_oz: v.weight_oz || 0,
                                        qty: v.qty,
                                        avail: v.avail,
                                        _isNew: false
                                    });
                                    tbody.appendChild(row);
                                    // Initialize UI states based on ship_type
                                    toggleSlugRequired(v.var_id);
                                    toggleWeightUI(v.var_id);
                                    toggleQtyUI(v.var_id);
                                } catch(err){ 
                                    console.error('Failed to build variant row:', err); 
                                }
                            });
                            // Update options column visibility after all rows are created
                            if(typeof toggleOptionsColumnVisibility === 'function'){ 
                                toggleOptionsColumnVisibility(); 
                            }
                        }
                    }
                    const modalEl = document.getElementById('editVariants');
                    if(modalEl && window.bootstrap){ 
                        new bootstrap.Modal(modalEl).show(); 
                    }
                })
                .catch((err)=> {
                    console.error('Fetch error:', err);
                    showToast('Request failed','error');
                });
            return;
        }
        const license = e.target.closest('.edit-license-pricing-link');
        if(license){
            e.preventDefault();
            const productId = license.getAttribute('data-product-id') || license.getAttribute('rel');
            if(!productId){ return; }
            const path = window.path || 'educators';
            const url = `/${path}/backend/products/${productId}/license-pricing`;
            fetch(url, { headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} })
                .then(r=>r.json())
                .then(data=>{
                    if(data.error){ showToast(data.message || 'Failed to load license pricing', 'error'); return; }
                    const nameSpan = document.getElementById('licensePricingProductName');
                    if(nameSpan){ nameSpan.textContent = data.product?.name || ''; }
                    window.licensePricingProductId = productId;
                    const tbody = document.getElementById('licensePricingTbody');
                    if(tbody){
                        tbody.innerHTML = '';
                        if(!data.prices || data.prices.length === 0){
                            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No licenses yet. Click "+ Add License".</td></tr>';
                        } else {
                            data.prices.forEach(p=>{
                                const tr = document.createElement('tr');
                                tr.dataset.tierId = p.id;
                                tr.innerHTML = `
                                    <td class="text-center"><input type="checkbox" class="license-select" aria-label="Select license tier"></td>
                                    <td><input type="text" class="form-control form-control-sm license-caption" value="${p.package_caption || ''}" /></td>
                                    <td><input type="number" class="form-control form-control-sm license-min" min="0" value="${p.min_users}" /></td>
                                    <td><input type="number" class="form-control form-control-sm license-max" min="1" value="${p.max_users}" /></td>
                                    <td><input type="number" class="form-control form-control-sm license-standard" step="0.01" min="0" value="${p.standard_price}" /></td>
                                    <td><input type="number" class="form-control form-control-sm license-discount" step="0.01" min="0" value="${p.discount_price}" /></td>
                                    <td><input type="number" class="form-control form-control-sm license-recurring" step="0.01" min="0" value="${p.recurring_price}" /></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger single-license-delete" title="Delete"><i class="fas fa-trash"></i></button></td>`;
                                tbody.appendChild(tr);
                            });
                        }
                        updateLicenseDeleteState();
                    }
                    const l = document.getElementById('editLicensePricing');
                    if(l && window.bootstrap){ new bootstrap.Modal(l).show(); } else if(l){ console.error('Bootstrap not loaded yet'); }
                })
                .catch(()=> showToast('Request failed', 'error'));
            return;
        }
    });
    // Open Add Tier Modal
    document.addEventListener('click', function(e){
        const addBtn = e.target.closest('#addLicenseTierBtn');
        if(!addBtn) return;
        e.preventDefault();
        if(!window.licensePricingProductId){ showToast('No product selected','error'); return; }
        const m = document.getElementById('addLicenseTier');
        if(m && window.bootstrap){ new bootstrap.Modal(m).show(); }
    });
    // Handle Add Tier submit
    document.addEventListener('submit', function(e){
        if(e.target && e.target.id === 'addLicenseTierForm'){
            e.preventDefault();
            const form = e.target;
            const fd = new FormData(form);
            const path = window.path || 'educators';
            const url = `/${path}/backend/products/${window.licensePricingProductId}/license-pricing`;
            fetch(url, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:new URLSearchParams(fd) })
                .then(r=>r.json())
                .then(data=>{
                    if(data.error){ showToast(data.message || 'Add failed','error'); return; }
                    showToast('Tier added','success');
                    form.reset();
                    // Refresh listing by re-triggering link click
                    const trigger = document.querySelector(`.edit-license-pricing-link[data-product-id='${window.licensePricingProductId}']`);
                    if(trigger){ trigger.click(); }
                    const modalEl = document.getElementById('addLicenseTier');
                    if(modalEl && window.bootstrap){ bootstrap.Modal.getInstance(modalEl)?.hide(); }
                })
                .catch(()=> showToast('Request failed','error'));
        }
    });
    // Edit Tier
    document.addEventListener('click', function(e){
        const editBtn = e.target.closest('.license-tier-edit');
        if(!editBtn) return;
        e.preventDefault();
        const tierId = editBtn.getAttribute('data-tier-id');
        const path = window.path || 'educators';
        const url = `/${path}/backend/products/${window.licensePricingProductId}/license-pricing/${tierId}`;
        fetch(url, { headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} })
            .then(r=>r.json())
            .then(data=>{
                if(data.error){ showToast(data.message || 'Load failed','error'); return; }
                const form = document.getElementById('editLicenseTierForm');
                if(!form) return;
                form.tier_id.value = data.tier.id;
                form.caption.value = data.tier.package_caption;
                form.min_users.value = data.tier.min_users;
                form.max_users.value = data.tier.max_users;
                form.standard.value = data.tier.standard_price;
                form.discount.value = data.tier.discount_price;
                form.recurring.value = data.tier.recurring_price;
                const m = document.getElementById('editLicenseTier');
                if(m && window.bootstrap){ new bootstrap.Modal(m).show(); }
            })
            .catch(()=> showToast('Request failed','error'));
    });
    // Handle Edit submit
    document.addEventListener('submit', function(e){
        if(e.target && e.target.id === 'editLicenseTierForm'){
            e.preventDefault();
            const form = e.target;
            const tierId = form.tier_id.value;
            const fd = new FormData(form);
            const path = window.path || 'educators';
            const url = `/${path}/backend/products/${window.licensePricingProductId}/license-pricing/${tierId}`;
            fetch(url, { method:'PATCH', headers:{'X-Requested-With':'XMLHttpRequest'}, body:new URLSearchParams(fd) })
                .then(r=>r.json())
                .then(data=>{
                    if(data.error){ showToast(data.message || 'Update failed','error'); return; }
                    showToast('Tier updated','success');
                    const trigger = document.querySelector(`.edit-license-pricing-link[data-product-id='${window.licensePricingProductId}']`);
                    if(trigger){ trigger.click(); }
                    const modalEl = document.getElementById('editLicenseTier');
                    if(modalEl && window.bootstrap){ bootstrap.Modal.getInstance(modalEl)?.hide(); }
                })
                .catch(()=> showToast('Request failed','error'));
        }
    });
    // Delete tier
    document.addEventListener('click', function(e){
        const delBtn = e.target.closest('.license-tier-delete');
        if(!delBtn) return;
        e.preventDefault();
        if(!confirm('Delete this tier?')) return;
        const tierId = delBtn.getAttribute('data-tier-id');
        const path = window.path || 'educators';
        const url = `/${path}/backend/products/${window.licensePricingProductId}/license-pricing/${tierId}`;
        fetch(url, { method:'DELETE', headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(r=>r.json())
            .then(data=>{
                if(data.error){ showToast(data.message || 'Delete failed','error'); return; }
                showToast('Tier deleted','success');
                const trigger = document.querySelector(`.edit-license-pricing-link[data-product-id='${window.licensePricingProductId}']`);
                if(trigger){ trigger.click(); }
            })
            .catch(()=> showToast('Request failed','error'));
    });
    </script>
</section>
@endsection
