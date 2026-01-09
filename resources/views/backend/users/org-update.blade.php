
<!-- UPDATE ORG -->
<div class="modal-body">
    <form action="{{url($path.'/backend/org-update')}}" method="POST" role="form">
        <input type="hidden" name="id" value="{{$org->id}}" />
        @csrf
        <div class="row pb-2">
            <label for="name" class="col-form-label col-md-2 text-md-right pr-2">Name</label>
            <div class="col-md-10">
                <input id="name" type="text" class="form-control" name="name" value="{{$org->name}}">
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
            <label for="country" class="col-form-label col-md-2 text-md-right pr-2">Country</label>
            <div class="col-md-10">
                <select class="form-control" name="country" id="country">
                    <option value="">Select Country</option>
                    <option value="US" @if($org->country == 'US') selected @endif>United States</option>
                    <option value="CA" @if($org->country == 'CA') selected @endif>Canada</option>
                    <option value="" disabled>──────────</option>
                    @include('layouts.helpers.countries-iso')
                </select>
            </div>
        </div>
        <div class="row pb-2">
            <label for="address" class="col-form-label col-md-2 text-md-right pr-2">Address</label>
            <div class="col-md-10">
                <input id="address" type="text" class="form-control" name="address" value="{{$org->address}}" placeholder="Street address">
            </div>
        </div>
        <div class="row pb-2">
            <label for="city" class="col-form-label col-md-2 text-md-right pr-2">City</label>
            <div class="col-md-4">
                <input id="city" type="text" class="form-control" name="city" value="{{$org->city}}">
            </div>
            <label for="state" class="col-form-label col-md-2 text-md-right pr-2" id="state-label">State</label>
            <div class="col-md-4">
                <select class="form-control" name="state" id="state">
                    <option value="">Select State</option>
                    @foreach(['AL'=>'Alabama', 'AK'=>'Alaska', 'AZ'=>'Arizona', 'AR'=>'Arkansas', 'CA'=>'California', 'CO'=>'Colorado', 'CT'=>'Connecticut', 'DE'=>'Delaware', 'DC'=>'District Of Columbia', 'FL'=>'Florida', 'GA'=>'Georgia', 'HI'=>'Hawaii', 'ID'=>'Idaho', 'IL'=>'Illinois', 'IN'=>'Indiana', 'IA'=>'Iowa', 'KS'=>'Kansas', 'KY'=>'Kentucky', 'LA'=>'Louisiana', 'ME'=>'Maine', 'MD'=>'Maryland', 'MA'=>'Massachusetts', 'MI'=>'Michigan', 'MN'=>'Minnesota', 'MS'=>'Mississippi', 'MO'=>'Missouri', 'MT'=>'Montana', 'NE'=>'Nebraska', 'NV'=>'Nevada', 'NH'=>'New Hampshire', 'NJ'=>'New Jersey', 'NM'=>'New Mexico', 'NY'=>'New York', 'NC'=>'North Carolina', 'ND'=>'North Dakota', 'OH'=>'Ohio', 'OK'=>'Oklahoma', 'OR'=>'Oregon', 'PA'=>'Pennsylvania', 'RI'=>'Rhode Island', 'SC'=>'South Carolina', 'SD'=>'South Dakota', 'TN'=>'Tennessee', 'TX'=>'Texas', 'UT'=>'Utah', 'VT'=>'Vermont', 'VA'=>'Virginia', 'WA'=>'Washington', 'WV'=>'West Virginia', 'WI'=>'Wisconsin', 'WY'=>'Wyoming'] as $code => $name)
                        <option value="{{$code}}" @if($org->state == $code) selected @endif>{{$name}}</option>
                    @endforeach
                </select>
                <input type="text" class="form-control" name="province" id="province" value="{{$org->state}}" placeholder="Province/Region" style="display:none;">
            </div>
        </div>
        <div class="row pb-2">
            <label for="zip" class="col-form-label col-md-2 text-md-right pr-2">Postal Code</label>
            <div class="col-md-4">
                <input id="zip" type="text" class="form-control" name="zip" value="{{$org->zip}}" placeholder="ZIP or postal code">
            </div>
        </div>
        <div class="row pb-2">
            <label for="url" class="col-form-label col-md-2 text-md-right pr-2">URL</label>
            <div class="col-md-8">
                <input id="url" type="text" class="form-control" name="url" value="{{$org->url}}">
            </div>
        </div>
        <div class="row pb-2">
            <label for="email_match" class="col-form-label col-md-2 text-md-right pr-2">Email Match</label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text" id="email_match">@</span>
                    <input type="text" class="form-control" id="email_match" name="email_match" value="{{$org->email_match}}">
                </div>
                <p style="font-size:10px; color:red;">Warning! This will map all users with this email ending to this organization. Never map @gmail.com.</p>
            </div> 
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary" onclick="return validateOrgUpdate()">SAVE</button>
            </div>
        </div>
    </form>
</div>

<script>
    // Validate organization update form
    function validateOrgUpdate(){
        var emailMatch = $('input[name="email_match"]').val().toLowerCase().trim();
        if(emailMatch){
            var blockedDomains = ['gmail.com', 'yahoo.com', 'yahoo.co.uk', 'hotmail.com', 'outlook.com', 'aol.com', 'icloud.com', 'protonmail.com', 'mail.com'];
            if(blockedDomains.includes(emailMatch)){
                alert('Error: Cannot use public email domains (' + emailMatch + ') for organization mapping. This would incorrectly assign personal email users to your organization.');
                return false;
            }
        }
        return true;
    }
    
    // Country change handler for organization update form
    $('#country').on('change', function(){
        const country = $(this).val();
        if(country === 'US'){
            $('#state-label').text('State');
            $('#state').show().prop('required', true);
            $('#province').hide().prop('required', false);
        } else {
            $('#state-label').text('Province/Region');
            $('#state').hide().prop('required', false);
            $('#province').show().prop('required', true);
        }
    });
    
    // Initialize on modal show
    $('#orgUpdate').on('shown.bs.modal', function(){
        $('#country').trigger('change');
    });

    //Moveable Modals
    $("#orgUpdate").draggable({
        handle: ".modal-header"
    });
</script>