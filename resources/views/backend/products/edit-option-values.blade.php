<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/products/values') }}" method="POST" id="save_values" role="form" onsubmit="return saveValues()">
        @csrf
        <div class="row">
            <div class="col-12">
                <div style="max-height: 420px; overflow-y: auto; border: 1px solid #eee; border-radius: 6px; background: #fff;">
                    <table class="table table-striped values-list" style="width: 100%">
                    <thead>
                        <tr>
                            <th scope="col"             style="text-align:center;"><input type="checkbox" id="selectAllValues" aria-label="Select all values" /></th>
                            <th id="option"   colspan="1" style="text-align:left;" >Option</th>
                            <th id="value"   colspan="1" style="text-align:left;" >Value</th>
                            <th id="delete" colspan="1" style="text-align:center;"><i class="fa fa-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\ProductOptionValues::with('option')->orderBy('value_id', 'asc')->get() as $value)
                        <tr>
                            <td style="text-align:center;">
                                <input type="checkbox" name="select_value_{{$value->value_id}}" class="select-value" aria-label="Select value {{$value->name}}" />
                            </td>
                            <td style="text-align:left;">
                                {{ optional($value->option)->name ?? 'Unknown option' }}
                            </td>
                            <td style="text-align:left;">
                                <input type="text" name="n[{{$value->value_id}}]" placeholder="value name" value="{{$value->name}}" class="form-control"/>
                            </td>
                            <td style="text-align:center;">
                                <a class="text-danger delete-existing-value" href="#" data-value-id="{{$value->value_id}}" title="Delete value"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="form-group row m-4">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary add-value" aria-label="Add a new value row">Add Value</button>
                <button type="button" class="btn btn-danger delete-selected-values disabled" aria-disabled="true" aria-label="Delete selected values">
                    <span class="btn-text">Delete Selected (0)</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <button type="submit" class="btn btn-secondary save-values" aria-label="Save value changes">
                    <span class="btn-text">Save Values</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Build options select HTML for new rows
    const optionsSelectHtml = `
        <select name="new_options_id[]" class="form-select form-select-sm">
            @foreach(\App\Models\ProductOptions::orderBy('name')->get() as $opt)
                <option value="{{$opt->option_id}}">{{ $opt->name }}</option>
            @endforeach
        </select>
    `;

    // Add new blank value row (use delegation for robustness)
    document.addEventListener('click', function(e){
        const addBtn = e.target.closest('.add-value');
        if(!addBtn) return;
        const tbody = document.querySelector('.values-list tbody');
        if(!tbody) return;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td></td>
            <td>
                ${optionsSelectHtml}
            </td>
            <td>
                <input type="text" name="new_name[]" placeholder="value name" value="" class="form-control" />
            </td>
            <td style="text-align:center;">
                <a class="text-danger rem-option" href="#" title="Remove unsaved value"><i class="fa fa-trash"></i></a>
            </td>
        `;
        tbody.appendChild(row);
        // auto scroll
        const container = document.querySelector('.values-list').parentElement;
        container.scrollTop = container.scrollHeight;
    });

    // Select all logic
    const selectAllValues = document.getElementById('selectAllValues');
    const deleteSelectedValuesBtn = document.querySelector('.delete-selected-values');
    function updateDeleteSelectedValues(){
        const count = document.querySelectorAll('.select-value:checked').length;
        if(deleteSelectedValuesBtn){ deleteSelectedValuesBtn.textContent = `Delete Selected (${count})`; }
        if(count === 0){
            deleteSelectedValuesBtn?.classList.add('disabled');
            deleteSelectedValuesBtn?.setAttribute('aria-disabled','true');
        } else {
            deleteSelectedValuesBtn?.classList.remove('disabled');
            deleteSelectedValuesBtn?.setAttribute('aria-disabled','false');
        }
    }
    selectAllValues?.addEventListener('change', function(){
        document.querySelectorAll('.select-value').forEach(cb=> cb.checked = selectAllValues.checked);
        updateDeleteSelectedValues();
    });
    document.addEventListener('click', function(e){
        if(e.target.classList && e.target.classList.contains('select-value')){
            updateDeleteSelectedValues();
        }
    });

    // Batch delete selected values (debounced)
    let deleteValuesBatchLock = false;
    deleteSelectedValuesBtn?.addEventListener('click', function(){
        if(deleteSelectedValuesBtn.classList.contains('disabled') || deleteValuesBatchLock) return;
        const ids = Array.from(document.querySelectorAll('.select-value:checked'))
            .map(cb => cb.name.replace('select_value_',''));
        if(ids.length === 0) return;
        if(!confirm('Delete '+ids.length+' selected value(s)?')) return;
        deleteValuesBatchLock = true;
        // Show spinner
        const btnText = deleteSelectedValuesBtn.querySelector('.btn-text');
        const spinner = deleteSelectedValuesBtn.querySelector('.spinner-border');
        if(btnText) btnText.classList.add('d-none');
        if(spinner) spinner.classList.remove('d-none');
        deleteSelectedValuesBtn.disabled = true;
        
        fetch(`/${window.path || 'educators'}/backend/products/values/delete-selected`, {
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
                    const row = document.querySelector(`input[name='select_value_${id}']`)?.closest('tr');
                    row && row.remove();
                });
                updateDeleteSelectedValues();
                window.showToast(r.message || 'Deleted','success');
            }
        }).catch(()=> window.showToast('Request failed','error'))
        .finally(()=>{
            // Hide spinner
            if(btnText) btnText.classList.remove('d-none');
            if(spinner) spinner.classList.add('d-none');
            deleteSelectedValuesBtn.disabled = false;
            deleteValuesBatchLock = false;
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

    // Delete existing option (AJAX)
    document.addEventListener('click', function(e){
        const del = e.target.closest('.delete-existing-value');
        if(!del) return;
        e.preventDefault();
        if(!confirm('Delete this value?')) return;
        const id = del.getAttribute('data-value-id');
        fetch(`/${window.path || 'educators'}/backend/products/values/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({id})
        }).then(r=>r.json()).then(r=>{
            if(r.error){ window.showToast ? showToast(r.message || 'Delete failed','error') : alert(r.message || 'Delete failed'); return; }
            const tr = del.closest('tr');
            tr?.remove();
            window.showToast ? showToast('Value deleted','success') : null;
        }).catch(()=> window.showToast ? showToast('Request failed','error') : alert('Request failed'));
    });

    // Save values (AJAX override form submit)
    function saveValues(){
        const form = document.getElementById('save_values');
        const saveBtn = form.querySelector('.save-values');
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
            window.showToast('Values saved','success');
            
            // Reload values data without closing modal
            setTimeout(()=> {
                reloadValuesTable();
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

    // Reload values table data via AJAX
    function reloadValuesTable(){
        fetch(`/${window.path || 'educators'}/backend/products/values/list`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(r=>r.json()).then(data=>{
            if(data.error){
                console.error('Failed to reload values:', data.message);
                return;
            }

            const tbody = document.querySelector('.values-list tbody');
            if(!tbody) return;
            
            // Clear existing rows
            tbody.innerHTML = '';
            
            // Rebuild table with fresh data
            data.values.forEach(value => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="text-align:center;">
                        <input type="checkbox" name="select_value_${value.value_id}" class="select-value" aria-label="Select value ${value.name}" />
                    </td>
                    <td style="text-align:left;">
                        ${value.option_name || 'Unknown option'}
                    </td>
                    <td style="text-align:left;">
                        <input type="text" name="n[${value.value_id}]" placeholder="value name" value="${value.name}" class="form-control"/>
                    </td>
                    <td style="text-align:center;">
                        <a class="text-danger delete-existing-value" href="#" data-value-id="${value.value_id}" title="Delete value"><i class="fa fa-trash"></i></a>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // Reset select all checkbox
            const selectAllRef = document.getElementById('selectAllValues');
            if(selectAllRef) selectAllRef.checked = false;
            updateDeleteSelectedValues();
        }).catch(err=>{
            console.error('Failed to reload values:', err);
        });
    }

    // Make modal draggable
    $("#editOptionValues").draggable({
        handle: ".modal-header"
    });
</script>

