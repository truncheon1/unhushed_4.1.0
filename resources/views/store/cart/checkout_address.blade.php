@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url()->previous() }}">Back </a> |
            <span style="font-weight: bold;color:#9acd57">Login to complete your purchase</span>
        </div>
    </div>

    <!-- SUBSCRIPTION CART HEADER-->
    <div class="container">
        @include('store.cart.statusbar')
        <!-- SHIPPING -->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <div class="card" style="width: 50rem;">
                    <div class="card-body">                        
                        @php
                            $savedAddresses = \App\Models\UserAddress::forUser(auth()->id())->get();
                            $defaultAddress = $savedAddresses->firstWhere('default', true);
                        @endphp

                        @if($savedAddresses->count() > 0)
                        <!-- Saved Addresses Section -->
                        <div class="mb-4" id="saved-addresses-section">
                            <div class="row mb-2">
                                <div class="col-md-6"><h5>Your Saved Addresses</h5></div>
                                <div class="col-6 text-right">
                                    <button type="button" id="use-selected-address" class="btn btn-success submit-btn" @if($savedAddresses->count() == 0) style="display: none;" @endif>Use Selected</button>
                                    <button type="button" class="btn btn-secondary" id="show-new-address"><i class="fa fa-plus"></i> Add New Address</button>
                                </div>
                            </div>
                            @foreach($savedAddresses as $addr)
                            <div class="form-check mb-3 p-3 border rounded @if($addr->default) border-primary @endif">
                                <input class="form-check-input" type="radio" name="selected_address" id="address_{{$addr->id}}" 
                                       value="{{$addr->id}}" @if($addr->default) checked @endif>
                                <label class="form-check-label w-100" for="address_{{$addr->id}}" style="cursor: pointer;">
                                    <strong>{{$addr->name}}</strong> @if($addr->default)<span class="badge bg-primary">Default</span>@endif<br>
                                    @if($addr->company)<small>{{$addr->company}}</small><br>@endif
                                    {{$addr->street}}<br>
                                    {{$addr->city}}, {{$addr->state_province}} {{$addr->zip}}<br>
                                    @if($addr->country !== 'US')<small>{{$addr->country}}</small><br>@endif
                                    @if($addr->phone)<small>Phone: {{$addr->phone}}</small>@endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- New/Edit Address Form -->
                        <div id="address-form-section" @if($savedAddresses->count() > 0) style="display: none;" @endif>
                            <h5 class="mb-3">@if($savedAddresses->count() > 0) Add New Address @else Enter Shipping Address @endif</h5>
                            @include('layouts.helpers.address-form', ['showCancel' => $savedAddresses->count() > 0, 'showDefaultCheckbox' => true])
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <div class="loading" style="display: none;">
                                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <button type="button" id="save-address" class="btn btn-primary submit-btn" style="display: none;">
                                Save Address & Continue
                            </button>
                            <button type="button" id="continue-to-checkout" class="btn btn-success submit-btn" style="display: none;">
                                Continue to Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        a.btn{
            color: #fff;
        }
        .rem{
            color: #f00;
            cursor: pointer;
        }
        .lds-ellipsis {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-ellipsis div {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #265a8e;
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }
        .vl {
            background: rgb(38,90,142) no-repeat center/2px 98%;
            background: linear-gradient(45deg, rgba(38,90,142,1) 0%, rgba(240,243,250,1) 40%, rgba(240,243,250,1) 60%, rgba(38,90,142,1)) no-repeat center/2px 100%;
        }
        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }
        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(24px, 0);
            }
        }
        .loading{
            display: none;
            margin: 0 auto;
        }
    </style>

    @include('layouts.helpers.address-form-js')
    
    <script type="text/javascript">
        let selectedAddressId = null;
        
        const showLoading = () => {
            $('.loading').show();
            $('.submit-btn').hide();
        }
        
        const hideLoading = () => {
            $('.loading').hide();
            $('.submit-btn').show();
        }
        
        // Show/hide new address form
        $('#show-new-address').on('click', function(){
            $('#saved-addresses-section').hide();
            $('#address-form-section').show();
            $('#save-address').show();
            $('#use-selected-address').hide();
            $('#continue-to-checkout').hide();
        });
        
        $('#cancel-new-address').on('click', function(){
            $('#address-form-section').hide();
            $('#saved-addresses-section').show();
            $('#save-address').hide();
            $('#use-selected-address').show();
            $('#continue-to-checkout').show();
        });
        
        // Use selected saved address
        $('#use-selected-address').on('click', function(){
            const selectedRadio = $('input[name="selected_address"]:checked');
            if(!selectedRadio.length){
                alert('Please select an address');
                return;
            }
            selectedAddressId = selectedRadio.val();
            
            showLoading();
            
            // Save address selection to session and redirect
            $.ajax({
                url: '{{ url($path."/shipping/select") }}',
                type: 'POST',
                data: {
                    address_id: selectedAddressId,
                    shipping_method: null,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response){
                    if(response.success){
                        window.location.href = '{{ url($path."/checkout") }}';
                    } else {
                        hideLoading();
                        alert(response.message || 'Failed to save address selection');
                    }
                },
                error: function(){
                    hideLoading();
                    alert('Failed to save address selection. Please try again.');
                }
            });
        });
        
        // Save new address and redirect to checkout
        $('#save-address').on('click', function(){
            const form = $('#address-form');
            
            // Basic validation
            if(!form[0].checkValidity()){
                form[0].reportValidity();
                return;
            }
            
            showLoading();
            
            // Save address
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response){
                    if(response.success){
                        selectedAddressId = response.address_id;
                        
                        // Save to session and redirect
                        $.ajax({
                            url: '{{ url($path."/shipping/select") }}',
                            type: 'POST',
                            data: {
                                address_id: selectedAddressId,
                                shipping_method: null,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response2){
                                if(response2.success){
                                    window.location.href = '{{ url($path."/checkout") }}';
                                } else {
                                    hideLoading();
                                    alert(response2.message || 'Failed to save address selection');
                                }
                            },
                            error: function(){
                                hideLoading();
                                alert('Failed to save address selection. Please try again.');
                            }
                        });
                    } else {
                        hideLoading();
                        alert(response.message || 'Failed to save address');
                    }
                },
                error: function(xhr){
                    hideLoading();
                    const errors = xhr.responseJSON?.errors;
                    if(errors){
                        let errorMsg = '';
                        for(let field in errors){
                            errorMsg += errors[field].join('\n') + '\n';
                        }
                        alert(errorMsg);
                    } else {
                        alert('Failed to save address. Please try again.');
                    }
                }
            });
        });
        
        // Continue to checkout without changing address
        $('#continue-to-checkout').on('click', function(){
            window.location.href = '{{ url($path."/checkout") }}';
        });
        
        // Show appropriate buttons on load
        $(document).ready(function(){
            @if($savedAddresses->count() == 0)
                $('#save-address').show();
            @else
                $('#continue-to-checkout').show();
            @endif
        });
    </script>
@endsection
