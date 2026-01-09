<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/products/options') }}" method="POST" id="save_options" role="form" onsubmit="return saveOptions()">
        @csrf
        <div class="row">
            <div class="col-12">
                <table class="table table-striped options-list" style="width: 100%">
                    <thead>
                        <tr>
                            <th scope="col"             style="text-align:center;"><input type="checkbox" id="selectAllOptions" aria-label="Select all options" /></th>
                            <th id="name"   colspan="1" style="text-align:left;" >Name</th>
                            <th id="categories" colspan="1" style="text-align:left;" >Categories</th>
                            <th id="delete" colspan="1" style="text-align:center;"><i class="fa fa-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\ProductOptions::all() as $option)
                        <tr>
                            <td style="text-align:center;">
                                <input type="checkbox" name="select_option_{{$option->option_id}}" class="select-option" aria-label="Select option {{$option->name}}" />
                            </td>
                            <td style="text-align:center;">
                                <input type="text" name="n[{{$option->option_id}}]" placeholder="option name" value="{{$option->name}}" class="form-control"/>
                            </td>
                            <td style="text-align:left;">
                                @php
                                    $cats = is_array($option->categories) ? $option->categories : [];
                                    $categoryNames = ['Curricula', 'Activities', 'Books', 'Games', 'Swag', 'Toolkits', 'Training'];
                                @endphp
                                <div class="category-buttons" data-option-id="{{$option->option_id}}">
                                    @for($i = 1; $i <= 7; $i++)
                                        <button type="button" class="btn btn-sm category-toggle {{ in_array($i, $cats) ? 'btn-success' : 'btn-outline-secondary' }}" data-category="{{$i}}" title="{{$categoryNames[$i-1]}}">{{$categoryNames[$i-1]}}</button>
                                    @endfor
                                    <input type="hidden" name="c[{{$option->option_id}}]" value="{{ json_encode($cats) }}" class="categories-input" />
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <a class="text-danger delete-existing-option" href="#" data-option-id="{{$option->option_id}}" title="Delete option"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-group row m-4">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary add-option" aria-label="Add a new option row">Add Option</button>
                <button type="button" class="btn btn-danger delete-selected-options disabled" aria-disabled="true" aria-label="Delete selected options">
                    <span class="btn-text">Delete Selected (0)</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <button type="submit" class="btn btn-secondary save-options" aria-label="Save option changes">
                    <span class="btn-text">Save Options</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Open modal (redundant if handled in parent, kept for direct triggering safety)
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.edit-options');
        if(btn){
            const modal = document.getElementById('editOptions');
            if(modal){ new bootstrap.Modal(modal).show(); }
        }
    });

    // Add new blank option row
    document.querySelector('.add-option')?.addEventListener('click', function(){
        const tbody = document.querySelector('.options-list tbody');
        if(!tbody) return;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td></td>
            <td>
                <input type="text" name="name[]" placeholder="option name" value="" class="form-control" />
            </td>
            <td style="text-align:left;">
                <div class="category-buttons" data-option-id="new">
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="1" title="Curricula">Curricula</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="2" title="Activities">Activities</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="3" title="Books">Books</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="4" title="Games">Games</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="5" title="Swag">Swag</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="6" title="Toolkits">Toolkits</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary category-toggle" data-category="7" title="Training">Training</button>
                    <input type="hidden" name="categories[]" value="[]" class="categories-input" />
                </div>
            </td>
            <td style="text-align:center;">
                <a class="text-danger rem-option" href="#" title="Remove unsaved option"><i class="fa fa-trash"></i></a>
            </td>
        `;
        tbody.appendChild(row);
        // auto scroll
        const container = document.querySelector('.options-list').parentElement;
        container.scrollTop = container.scrollHeight;
    });

    // Toggle category buttons
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.category-toggle');
        if(!btn) return;
        
        const category = parseInt(btn.getAttribute('data-category'));
        const container = btn.closest('.category-buttons');
        const input = container.querySelector('.categories-input');
        
        // Parse current categories
        let categories = [];
        try {
            categories = JSON.parse(input.value || '[]');
            if(!Array.isArray(categories)) categories = [];
        } catch(e) {
            categories = [];
        }
        
        // Toggle category
        const index = categories.indexOf(category);
        if(index > -1){
            // Remove
            categories.splice(index, 1);
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        } else {
            // Add
            categories.push(category);
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
        }
        
        // Update hidden input
        input.value = JSON.stringify(categories);
    });

    // Select all logic
    const selectAll = document.getElementById('selectAllOptions');
    const deleteSelectedBtn = document.querySelector('.delete-selected-options');
    function updateDeleteSelected(){
        const count = document.querySelectorAll('.select-option:checked').length;
        deleteSelectedBtn.textContent = `Delete Selected (${count})`;
        if(count === 0){
            deleteSelectedBtn.classList.add('disabled');
            deleteSelectedBtn.setAttribute('aria-disabled','true');
        } else {
            deleteSelectedBtn.classList.remove('disabled');
            deleteSelectedBtn.setAttribute('aria-disabled','false');
        }
    }
    selectAll?.addEventListener('change', function(){
        document.querySelectorAll('.select-option').forEach(cb=> cb.checked = selectAll.checked);
        updateDeleteSelected();
    });
    document.addEventListener('click', function(e){
        if(e.target.classList && e.target.classList.contains('select-option')){
            updateDeleteSelected();
        }
    });

    // Batch delete selected options (debounced)
    let deleteBatchLock = false;
    deleteSelectedBtn?.addEventListener('click', function(){
        if(deleteSelectedBtn.classList.contains('disabled') || deleteBatchLock) return;
        const ids = Array.from(document.querySelectorAll('.select-option:checked'))
            .map(cb => cb.name.replace('select_option_',''));
        if(ids.length === 0) return;
        if(!confirm('Delete '+ids.length+' selected option(s)?')) return;
        deleteBatchLock = true;
        // Show spinner
        const btnText = deleteSelectedBtn.querySelector('.btn-text');
        const spinner = deleteSelectedBtn.querySelector('.spinner-border');
        if(btnText) btnText.classList.add('d-none');
        if(spinner) spinner.classList.remove('d-none');
        deleteSelectedBtn.disabled = true;
        
        fetch(`/${window.path || 'educators'}/backend/products/options/delete-selected`, {
            method:'POST',
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':'application/json',
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body:new URLSearchParams({ 'ids[]': ids })
        }).then(r=>r.json()).then(r=>{
            if(r.error){ 
                window.showToast(r.message || 'Delete failed','error');
            } else {
                ids.forEach(id=>{
                    const row = document.querySelector(`input[name='select_option_${id}']`)?.closest('tr');
                    row && row.remove();
                });
                updateDeleteSelected();
                window.showToast(r.message || 'Deleted','success');
            }
        }).catch(()=> window.showToast('Request failed','error'))
        .finally(()=>{
            // Hide spinner
            if(btnText) btnText.classList.remove('d-none');
            if(spinner) spinner.classList.add('d-none');
            deleteSelectedBtn.disabled = false;
            deleteBatchLock = false;
        });
    });

    // Remove a newly added (unsaved) option
    document.addEventListener('click', function(e){
        const del = e.target.closest('.rem-option');
        if(del){
            e.preventDefault();
            const tr = del.closest('tr');
            tr?.remove();
        }
    });

    // Delete existing option (AJAX with cascade support)
    document.addEventListener('click', function(e){
        const del = e.target.closest('.delete-existing-option');
        if(!del) return;
        e.preventDefault();
        
        const id = del.getAttribute('data-option-id');
        const optionName = del.closest('tr')?.querySelector('input[type="text"]')?.value || 'this option';
        
        // First attempt - check dependencies
        function attemptDelete(cascade = false){
            fetch(`/${window.path || 'educators'}/backend/products/options/delete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({id, cascade: cascade ? '1' : ''})
            }).then(r=>r.json()).then(r=>{
                if(r.error){
                    // Check if cascade is required
                    if(r.requiresCascade){
                        const valuesPreview = r.valueNames.join(', ');
                        const msg = `"${optionName}" has ${r.valueCount} value(s):\n${valuesPreview}${r.valueCount > 5 ? '...' : ''}\n\n` +
                                    (r.junctionCount ? `Also ${r.junctionCount} variant assignment(s).\n\n` : '') +
                                    `DELETE ALL dependencies and the option?`;
                        if(confirm(msg)){
                            attemptDelete(true); // Cascade delete
                        }
                    } else {
                        window.showToast ? showToast(r.message || 'Delete failed','error') : alert(r.message || 'Delete failed');
                    }
                    return;
                }
                // Success
                const tr = del.closest('tr');
                tr?.remove();
                window.showToast ? showToast(r.message || 'Option deleted','success') : null;
            }).catch(()=> window.showToast ? showToast('Request failed','error') : alert('Request failed'));
        }
        
        if(confirm(`Delete "${optionName}"?`)){
            attemptDelete(false);
        }
    });

    // Save options (AJAX override form submit)
    function saveOptions(){
        const form = document.getElementById('save_options');
        const saveBtn = form.querySelector('.save-options');
        const btnText = saveBtn?.querySelector('.btn-text');
        const spinner = saveBtn?.querySelector('.spinner-border');
        
        // Show spinner
        if(btnText) btnText.classList.add('d-none');
        if(spinner) spinner.classList.remove('d-none');
        if(saveBtn) saveBtn.disabled = true;
        
        const url = form.getAttribute('action');
        const fd = new FormData(form);
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: new URLSearchParams(fd)
        }).then(r=>r.json()).then(r=>{
            if(r.error){ 
                window.showToast(r.message || 'Save failed','error');
                // Hide spinner on error
                if(btnText) btnText.classList.remove('d-none');
                if(spinner) spinner.classList.add('d-none');
                if(saveBtn) saveBtn.disabled = false;
                return;
            }
            window.showToast('Options saved','success');
            
            // Reload options data without closing modal
            setTimeout(()=> {
                reloadOptionsTable();
                // Hide spinner after reload
                if(btnText) btnText.classList.remove('d-none');
                if(spinner) spinner.classList.add('d-none');
                if(saveBtn) saveBtn.disabled = false;
            }, 500);
        }).catch(()=> {
            window.showToast('Request failed','error');
            // Hide spinner on error
            if(btnText) btnText.classList.remove('d-none');
            if(spinner) spinner.classList.add('d-none');
            if(saveBtn) saveBtn.disabled = false;
        });
        return false;
    }

    // Reload options table data via AJAX
    function reloadOptionsTable(){
        fetch(`/${window.path || 'educators'}/backend/products/options/list`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(r=>r.json()).then(data=>{
            if(data.error){
                console.error('Failed to reload options:', data.message);
                return;
            }
            
            const tbody = document.querySelector('.options-list tbody');
            if(!tbody) return;
            
            // Clear existing rows
            tbody.innerHTML = '';
            
            // Rebuild table with fresh data
            data.options.forEach(option => {
                const cats = Array.isArray(option.categories) ? option.categories : [];
                const categoryNames = ['Curricula', 'Activities', 'Books', 'Games', 'Swag', 'Toolkits', 'Training'];
                let categoryButtons = '';
                for(let i = 1; i <= 7; i++){
                    const active = cats.includes(i);
                    const catName = categoryNames[i-1];
                    categoryButtons += `<button type="button" class="btn btn-sm category-toggle ${active ? 'btn-success' : 'btn-outline-secondary'}" data-category="${i}" title="${catName}">${catName}</button>`;
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="text-align:center;">
                        <input type="checkbox" name="select_option_${option.option_id}" class="select-option" aria-label="Select option ${option.name}" />
                    </td>
                    <td style="text-align:center;">
                        <input type="text" name="n[${option.option_id}]" placeholder="option name" value="${option.name}" class="form-control"/>
                    </td>
                    <td style="text-align:left;">
                        <div class="category-buttons" data-option-id="${option.option_id}">
                            ${categoryButtons}
                            <input type="hidden" name="c[${option.option_id}]" value='${JSON.stringify(cats)}' class="categories-input" />
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <a class="text-danger delete-existing-option" href="#" data-option-id="${option.option_id}" title="Delete option"><i class="fa fa-trash"></i></a>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // Reset select all checkbox
            const selectAll = document.getElementById('selectAllOptions');
            if(selectAll) selectAll.checked = false;
            updateDeleteSelected();
        }).catch(err=>{
            console.error('Failed to reload options:', err);
        });
    }

    // Make modal draggable
    $("#editOptions").draggable({
        handle: ".modal-header"
    });
</script>

