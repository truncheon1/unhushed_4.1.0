
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/products/activities') }}" method="POST" id="save_activities" role="form" onsubmit="return saveActivities()">
        <input type="hidden" name="id" class="activity_id" value=""/>
        <input type="hidden" name="rem" class="rem-activities" value="" />
        @csrf
        <div class="row">
            <div class="col-12">
                <table class="table table-striped" style="width: 100%" id="activitiesTable">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th id="activity"   colspan="1" style="text-alignleft">Activity</th>
                            <th id="resource"   colspan="1" style="text-align:left">Resource</th>
                            <th id="variants"   colspan="1" style="text-align:left">Assigned To</th>
                            <th id="options"    colspan="1" style="text-align:center">Options</th>
                        </tr>
                    </thead>
                    <tbody class="sortActivities">
                        <tr>
                            <td class="grip"><i class="fas fa-grip-vertical"></i></td>
                            <td colspan="5">Some Content here</td>
                        </tr>
                        <tr>
                            <td class="grip"><i class="fas fa-grip-vertical"></i></td>
                            <td colspan="5">Some Content here2</td>
                        </tr>
                        <tr>
                            <td class="grip"><i class="fas fa-grip-vertical"></i></td>
                            <td colspan="5">Some Content here3</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <a href="#" class="add-activity">Add Activity</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-group row m-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary save-activity">Save Activities</button>
            </div>
        </div>
    </form>
</div>

<script>
    $("#editActivities").draggable({
        handle: ".modal-header"
    });

    $(".sortActivities").sortable({
        handle: 'td:first'
    });

    $("body").on("click", ".product-activity-link", function(e){
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
                    rem_activities = [];
                    // Store variants globally for use in fillActivities and addActivity
                    window.productVariants = response.variants || [];
                    fillActivities(response.activities, response.file_path);
                    $(".activity_id").val(response.id);
                    // Set the product name in the modal title
                    $("#activitiesProductName").text(productName);
                    $("#editActivities").modal('show');
                }
            },
            fail: function(res){
                alert('Failed to fetch activites!');
            }
        })
    });

    $(".add-activity").on('click', function(e){
        e.preventDefault();
        addActivity();
    })

    $("body").on("click", '.rem-existing-activity', function(e){
        e.preventDefault();
        id = $(this).attr('rel');
        rem_activities.push(id);
        if(confirm("Are you sure you want to delete this activity?")){
            $(this).parent().parent().remove();
        }
    })

    $("body").on("click", '.rem-new-activity', function(e){
        e.preventDefault();
        if(confirm("Are you sure you want to delete this activity?")){
            $(this).parent().parent().remove();
        }
    })

    function saveActivities(){
        var _url = $("#save_activities").attr('action');
        $(".rem-activities").val(rem_activities.join("|"));
        _form = $("#save_activities");
        
        // Check file sizes before submitting
        var maxFileSize = 20 * 1024 * 1024; // 20MB in bytes
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
            alert('The following files exceed the 20MB limit:\n\n' + oversizedFiles.join('\n') + '\n\nPlease use smaller files or upload them separately.');
            return false;
        }
        
        if(totalSize > 45 * 1024 * 1024){ // 45MB total (leaving room for other form data)
            alert('Total upload size (' + (totalSize / 1024 / 1024).toFixed(2) + 'MB) exceeds the 45MB limit.\n\nPlease upload fewer files at once or use smaller files.');
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
                    alert("Activities saved!");
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('Save failed:', xhr.responseText);
                if(xhr.responseJSON && xhr.responseJSON.message){
                    alert('Error: ' + xhr.responseJSON.message);
                } else if(xhr.status === 413 || xhr.statusText === 'Request Entity Too Large'){
                    alert('The upload is too large. Please use smaller files or upload fewer files at once.');
                } else {
                    alert('Failed to save activities. Please check all fields are filled in correctly.');
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    }

    $("body").on("change", '.switch_option', function(e){
        e.preventDefault();
        if($(this).val() == 2){
            $(this).parent().find('.file').css({'display':"none"});
            $(this).parent().find('.res').css({'display':"block"});
        }else{
            $(this).parent().find('.res').css({'display':"none"});
            $(this).parent().find('.file').css({'display':"block"});
        }
    });

    // Handle "All Variants" checkbox for existing activities
    $("body").on("change", '.variant-all-checkbox', function(e){
        var activityId = $(this).data('activity-id');
        var checkboxes = $(this).closest('td').find('.variant-checkbox[data-activity-id="' + activityId + '"]');
        if($(this).is(':checked')){
            // Uncheck all individual variants when "All" is checked
            checkboxes.prop('checked', false);
        }
    });

    // Handle individual variant checkboxes for existing activities
    $("body").on("change", '.variant-checkbox', function(e){
        var activityId = $(this).data('activity-id');
        var allCheckbox = $(this).closest('td').find('.variant-all-checkbox[data-activity-id="' + activityId + '"]');
        // If any individual variant is checked, uncheck "All"
        if($(this).is(':checked')){
            allCheckbox.prop('checked', false);
        }
    });

    // Handle "All Variants" checkbox for new activities
    $("body").on("change", '.variant-all-checkbox-new', function(e){
        var checkboxes = $(this).closest('td').find('.variant-checkbox-new');
        if($(this).is(':checked')){
            // Uncheck all individual variants when "All" is checked
            checkboxes.prop('checked', false);
        }
    });

    // Handle individual variant checkboxes for new activities
    $("body").on("change", '.variant-checkbox-new', function(e){
        var allCheckbox = $(this).closest('td').find('.variant-all-checkbox-new');
        // If any individual variant is checked, uncheck "All"
        if($(this).is(':checked')){
            allCheckbox.prop('checked', false);
        }
    });

    function fillActivities(activities, file_path){
        var _table = $("#activitiesTable");
        $(".sortActivities").html("");
        for(x = 0; x < activities.length; x++){
            var variantCheckboxes = '';
            if(window.productVariants && window.productVariants.length > 0){
                variantCheckboxes += '<div style="font-size: 12px;">';
                // Add "All Variants" checkbox
                var allChecked = (!activities[x].variant_ids || activities[x].variant_ids.length === 0) ? 'checked' : '';
                variantCheckboxes += '<label style="display:block; margin-bottom: 5px;"><input type="checkbox" class="variant-all-checkbox" data-activity-id="' + activities[x].id + '" ' + allChecked + '> <strong>All Variants</strong></label>';
                
                // Add individual variant checkboxes
                for(var v = 0; v < window.productVariants.length; v++){
                    var variant = window.productVariants[v];
                    var isChecked = '';
                    if(activities[x].variant_ids && activities[x].variant_ids.includes(variant.var_id)){
                        isChecked = 'checked';
                    }
                    variantCheckboxes += '<label style="display:block; margin-bottom: 3px;"><input type="checkbox" name="variants[' + activities[x].id + '][]" value="' + variant.var_id + '" class="variant-checkbox" data-activity-id="' + activities[x].id + '" ' + isChecked + '> ' + (variant.name || 'Variant #' + variant.var_id) + '</label>';
                }
                variantCheckboxes += '</div>';
            } else {
                variantCheckboxes = '<small class="text-muted">No variants</small>';
            }
            
            _row = `<tr>
                <td class="grip"><i class="fas fa-grip-vertical"></i></td>
                <td><input type="text" class="form-control" name="activity[${activities[x].id}]"    value="${activities[x].activity}"   placeholder="Activity" /></td>
                <td><select class="form-control switch_option" name="source[${activities[x].id}]"   style="max-width:150px;">
                    <option value="1">Upload</option>
                    <option value="2" ${activities[x].source == 2 ? "selected" : ""}>URL Resource</option>
                </select>`;
            if(activities[x].source == 1){
                _row += `<span>Current File: <a href="${file_path+activities[x].resource}" target="_blank">${activities[x].resource}</a></span>`;
            }
            _row +=  `
                <input type="file" class="form-control file" name="file_upload[${activities[x].id}]"  ${activities[x].source == 2 ? "style='display:none'" : ""} />
                <input type="text" name="resource[${activities[x].id}]" value="${activities[x].source == 2 ? activities[x].resource : ""}" class="form-control res" placeholder="Resource URL" ${activities[x].source == 1 ? "style='display:none'" : ""} /></td>
                <td>${variantCheckboxes}</td>
                <td><a href="#" class="text-danger rem-existing-activity text-center" rel="${activities[x].id}"><i class="fa fa-trash"></i></a></td>
                </tr>`;
            _table.append(_row);
        }
    }
    var rem_activities = [];
    function addActivity(){
        var _table = $("#activitiesTable");
        var variantCheckboxes = '';
        if(window.productVariants && window.productVariants.length > 0){
            variantCheckboxes += '<div style="font-size: 12px;">';
            // Add "All Variants" checkbox (checked by default for new activities)
            variantCheckboxes += '<label style="display:block; margin-bottom: 5px;"><input type="checkbox" class="variant-all-checkbox-new" checked> <strong>All Variants</strong></label>';
            
            // Add individual variant checkboxes
            for(var v = 0; v < window.productVariants.length; v++){
                var variant = window.productVariants[v];
                variantCheckboxes += '<label style="display:block; margin-bottom: 3px;"><input type="checkbox" name="variants_new[]" value="' + variant.var_id + '" class="variant-checkbox-new"> ' + (variant.name || 'Variant #' + variant.var_id) + '</label>';
            }
            variantCheckboxes += '</div>';
        } else {
            variantCheckboxes = '<small class="text-muted">No variants</small>';
        }
        
        var _row = `<tr>
            <td class="grip"></td>
            <td><input type="text" class="form-control" name="activity_new[]" placeholder="Activity" /></td>
            <td><select class="form-control switch_option" name="source_new[]" style="max-width:150px;">
                <option value="1">Upload</option>
                <option value="2">URL Resource</option>
            </select><input type="file" class="form-control file" name="file_upload_new[]" /> <input type="text" name="resource_new[]" class="form-control res" placeholder="Resource URL" style="display:none" /></td>
            <td>${variantCheckboxes}</td>
            <td><a href="#" class="text-danger rem-new-activity text-center"><i class="fa fa-trash"></i></a></td>
            </tr>`
        _table.append(_row);
    }
</script>
