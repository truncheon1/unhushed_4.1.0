<!-- ADD ORG -->
<div class="modal-body">
    <form action="{{url($path.'/backend/org-add')}}" method="POST" role="form" onsubmit="return addOrg()" id="addOrgForm" aria-label="org-add">
        @csrf
        <div class="row pb-2">
            <label for="name" class="col-form-label col-md-2 text-md-right pr-2">Name</label>
            <div class="col-md-10">
                <input id="name" type="text" class="form-control" name="name" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="pedagogy" class="col-form-label col-md-2 text-md-right pr-2">Pedagogy</label>
            <div class="col-md-4 col-sm-6">
                <label for="pedagogy" class="sr-only">Pedagogy</label>
                <select class="form-control" name="pedagogy" id="pedagogy" value="{{$org->pedagogy}}">
                    @if(empty($org->pedagogy))
                    <option value="" selected>Select Pedagogy</option>
                    @else
                    <option value="{{$org->pedagogy}}">{{$org->pedagogy}}</option>
                    @endif
                    @include('layouts.helpers.pedagogy')
                </select>
            </div>
            <label for="grades" class="col-form-label col-md-1 text-md-right pr-2">Grades</label>
            <div class="col-md-2 col-sm-6">
                <label for="grades" class="sr-only">Grades</label>
                <select class="form-control" name="grades" id="grades" value="{{$org->grades}}">
                    @if(empty($org->grades))
                    <option value="" selected>Select Grades</option>
                    @else
                    <option value="{{$org->grades}}">{{$org->grades}}</option>
                    @endif
                    @include('layouts.helpers.grades')
                </select>
            </div>
            <label for="size" class="col-form-label col-md-1 text-md-right pr-2">Count</label>
            <div class="col-md-2">
                <input id="size" type="text" class="form-control" name="size" value="{{$org->size}}">
            </div>
        </div>
        <div class="row pb-2">
            <label for="org-country" class="col-form-label col-md-2 text-md-right pr-2">Country</label>
            <div class="col-md-10">
                <select class="form-control" name="country" id="org-country">
                    <option value="">Select Country</option>
                    <option value="US" selected>United States</option>
                    <option value="CA">Canada</option>
                    <option value="" disabled>──────────</option>
                    @include('layouts.helpers.countries-iso')
                </select>
            </div>
        </div>
        <div class="row pb-2">
            <label for="org-address" class="col-form-label col-md-2 text-md-right pr-2">Address</label>
            <div class="col-md-10">
                <input id="org-address" type="text" class="form-control" name="address" value="" placeholder="Street address">
            </div>
        </div>
        <div class="row pb-2">
            <label for="org-city" class="col-form-label col-md-2 text-md-right pr-2">City</label>
            <div class="col-md-4">
                <input id="org-city" type="text" class="form-control" name="city" value="">
            </div>
            <label class="col-form-label col-md-2 text-md-right pr-2" id="org-state-label">State</label>
            <div class="col-md-4">
                <select class="form-control" name="state" id="org-state">
                    <option value="">Select State</option>
                    @include('layouts.helpers.states')
                </select>
                <input type="text" class="form-control" name="province" id="org-province" value="" placeholder="Province/Region" style="display:none;">
            </div>
        </div>
        <div class="row pb-2">
            <label for="org-zip" class="col-form-label col-md-2 text-md-right pr-2">Postal Code</label>
            <div class="col-md-4">
                <input id="org-zip" type="text" class="form-control" name="zip" value="" placeholder="ZIP or postal code">
            </div>
        </div>
        <div class="row pb-2">
            <label for="url" class="col-form-label col-md-2 text-md-right pr-2">URL</label>
            <div class="col-md-10">
                <input id="url" type="text" class="form-control" name="url" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="email_match" class="col-form-label col-md-2 text-md-right pr-2">Email Match</label>
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text" id="email_match">@</span>
                    <input type="text" class="form-control" id="email_match" name="email_match" value="">
                </div>
                <p style="font-size:10px; color:red;">Warning! This will map all users with this email ending to this organization. Never map @gmail.com.</p>
            </div> 
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">SAVE</button>
            </div>
        </div>
    </form>
</div>

<script>
    // Country change handler for organization form
    $('#org-country').on('change', function(){
        const country = $(this).val();
        if(country === 'US'){
            $('#org-state-label').text('State');
            $('#org-state').show().prop('required', true);
            $('#org-province').hide().prop('required', false).val('');
        } else {
            $('#org-state-label').text('Province/Region');
            $('#org-state').hide().prop('required', false).val('');
            $('#org-province').show().prop('required', true);
        }
    });
    
    // Initialize on modal show
    $('#addOrg').on('shown.bs.modal', function(){
        $('#org-country').trigger('change');
    });

    //ADD ORGANIZATION
    function addOrg(){
        // Validate email match field
        var emailMatch = $('#addOrgForm input[name="email_match"]').val().toLowerCase().trim();
        if(emailMatch){
            var blockedDomains = ['gmail.com', 'yahoo.com', 'yahoo.co.uk', 'hotmail.com', 'outlook.com', 'aol.com', 'icloud.com', 'protonmail.com', 'mail.com'];
            if(blockedDomains.includes(emailMatch)){
                alert('Error: Cannot use public email domains (' + emailMatch + ') for organization mapping. This would incorrectly assign personal email users to your organization.');
                return false;
            }
        }
        
        frmData = $("#addOrgForm").serialize();
        url = $("#addOrgForm").attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: frmData,
            success: function(result) {
                if(result.success == false){
                    alert(show_result(result.message));
                    return;
                }else{
                    // Add new org to dropdown and select it
                    var orgName = $('#addOrgForm input[name="name"]').val();
                    var orgCity = $('#org-city').val();
                    var orgState = $('#org-state').val() || $('#org-province').val();
                    var orgCountry = $('#org-country option:selected').text();
                    var newOption = $('<option>', {
                        value: result.org_id,
                        text: orgName + ' | ' + orgCity + ' | ' + orgState + ' | ' + orgCountry
                    });
                    $('.view-users').append(newOption);
                    $('.view-users').val(result.org_id);
                    // Trigger change to reload table
                    $('.view-users').trigger('change');
                    $('#addOrg').modal('hide');
                }
            },
            fail: function(){
                alert('Organization was not added.');
            }
        });
        return false;
    };
</script>