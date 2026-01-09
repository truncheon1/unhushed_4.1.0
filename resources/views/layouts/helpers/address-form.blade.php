{{--
    Reusable Address Form Component
    
    Required variables:
    - $address: UserAddress model instance (can be empty/new)
    - $states: Array of US states (short => full name)
    - $path: Current path (educators/organizations/etc)
    
    Optional variables:
    - $showCancel: Boolean, whether to show cancel button (default: false)
    - $showDefaultCheckbox: Boolean, whether to show "set as default" checkbox (default: true)
--}}

@php
    $showCancel = $showCancel ?? false;
    $showDefaultCheckbox = $showDefaultCheckbox ?? true;
@endphp

<form method="POST" action="{{ url($path.'/address/save') }}" id="address-form">
    @csrf
    <input type="hidden" name="address_id" id="address_id" value="">
    
    <div class="form-group mb-3">
        <label for="name">Full Name *</label>
        <input type="text" name="name" id="name" class="form-control" 
               value="{{old('name', $address->name ?? auth()->user()->name)}}" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="company">Company (Optional)</label>
        <input type="text" name="company" id="company" class="form-control" 
               value="{{old('company', $address->company ?? '')}}">
    </div>
    
    <div class="form-group mb-3">
        <label for="email">Email *</label>
        <input type="email" name="email" id="email" class="form-control" 
               value="{{old('email', $address->email ?? auth()->user()->email)}}" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="phone">Phone *</label>
        <input type="tel" name="phone" id="phone" class="form-control" 
               value="{{old('phone', $address->phone ?? '')}}" required>
    </div>

    <div class="form-group mb-3">
        <label for="country">Country *</label>
        <select class="form-control" name="country" id="country" required>
            <option value="">Select Country</option>
            <option value="US" @if(old('country', $address->country ?? 'US') == 'US') selected @endif>United States</option>
            <option value="CA" @if(old('country', $address->country ?? '') == 'CA') selected @endif>Canada</option>
            <option value="" disabled>──────────</option>
            @include('layouts.helpers.countries-iso')
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="street">Street Address *</label>
        <input type="text" name="street" id="street" class="form-control" 
               value="{{old('street', $address->street ?? '')}}" 
               placeholder="Street number and name" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="city">City *</label>
        <input type="text" name="city" id="city" class="form-control" 
               value="{{old('city', $address->city ?? '')}}" required>
    </div>
    
    <div class="form-group mb-3" id="state-group">
        <label for="state">State *</label>
        <select class="form-control" name="state" id="state">
            <option value="">Select State</option>
            @foreach($states as $short=>$state_name)
                <option value="{{$short}}" @if(old('state', $address->state_province ?? '') == $short) selected @endif>{{$state_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3" id="province-group" style="display:none;">
        <label for="province">Province/Region *</label>
        <input type="text" name="province" id="province" class="form-control" 
               value="{{old('province', $address->state_province ?? '')}}"
               placeholder="Province, region, or state">
    </div>
    
    <div class="form-group mb-3">
        <label for="zip">Postal Code *</label>
        <input type="text" name="zip" id="zip" class="form-control" 
               value="{{old('zip', $address->zip ?? '')}}" 
               placeholder="ZIP or postal code" required>
    </div>

    @if($showDefaultCheckbox)
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="default" id="default" value="1">
        <label class="form-check-label" for="default">
            Set as default shipping address
        </label>
    </div>
    @endif

    @if($showCancel)
    <button type="button" class="btn btn-secondary mb-3" id="cancel-new-address">Cancel</button>
    @endif
</form>
