@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url(get_path($path).'/dashboard') }}"> Dashboard</a> |
                <span style="font-weight: bold;color:#9acd57">Address Book</span>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6" style="text-transform: uppercase; font-weight: bold;">ADDRESS BOOK</div>
                            <div class="col-6 text-right">
                            <button type="button" class="btn btn-secondary btn-sm" id="show-new-address"><i class="fa fa-plus"></i> Add New Address</button>
                        </div>
                        </div>
                    </div>
                    <div class="card-body" style= "min-width:600px">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @php
                            $savedAddresses = \App\Models\UserAddress::where('user_id', auth()->id())
                                ->whereNull('deleted_at')
                                ->get();
                            $defaultAddress = $savedAddresses->firstWhere('default', true);
                        @endphp

                        @if($savedAddresses->count() > 0)
                        <!-- Saved Addresses Section -->
                        <div class="mb-4" id="saved-addresses-section">
                            @foreach($savedAddresses as $addr)
                            <div class="card mb-3 address-card" data-address-id="{{$addr->id}}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <strong>{{$addr->name}}</strong> 
                                                @if($addr->default)
                                                    <span class="badge bg-primary">Default</span>
                                                @endif
                                            </h6>
                                            @if($addr->company)<div class="text-muted small">{{$addr->company}}</div>@endif
                                            <div class="mt-2">
                                                {{$addr->street}}<br>
                                                {{$addr->city}}, {{$addr->state_province}} {{$addr->zip}}<br>
                                                @if($addr->country !== 'US')<small>{{$addr->country}}</small><br>@endif
                                                @if($addr->phone)<small>Phone: {{$addr->phone}}</small>@endif
                                                @if($addr->email)<small>Email: {{$addr->email}}</small>@endif
                                            </div>
                                        </div>
                                        <div class="btn-group-vertical btn-group-sm ms-3">
                                            @if(!$addr->default)
                                                <button type="button" class="btn btn-outline-primary btn-sm set-default-btn" data-address-id="{{$addr->id}}">
                                                    <i class="fa fa-star"></i> Set Default
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-secondary btn-sm edit-address-btn" data-address-id="{{$addr->id}}">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-address-btn" data-address-id="{{$addr->id}}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
                        @else
                        @endif

                        <!-- New/Edit Address Form -->
                        <div id="address-form-section" style="display: none;">
                            <h5 class="mb-3" id="form-title">Add New Address</h5>
                            <input type="hidden" id="is_editing" value="0">
                            @include('layouts.helpers.address-form', ['showCancel' => false, 'showDefaultCheckbox' => true])
                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-primary" id="save-address-btn">Save Address</button>
                                <button type="button" class="btn btn-secondary" id="cancel-address-form">Cancel</button>
                            </div>
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
        
        // Reset form
        function resetAddressForm() {
            $('#address-form')[0].reset();
            $('#address_id').val('');
            $('#is_editing').val('0');
            $('#default').prop('checked', false);
            $('#form-title').text('Add New Address');
            $('#country').val('US').trigger('change');
        }
        
        // Show new address form
        $('#show-new-address').on('click', function(){
            resetAddressForm();
            $('#saved-addresses-section').hide();
            $('#address-form-section').show();
        });
        
        // Cancel address form
        $('#cancel-address-form').on('click', function(){
            $('#address-form-section').hide();
            $('#saved-addresses-section').show();
            resetAddressForm();
        });
        
        // Save address (create or update)
        $('#save-address-btn').on('click', function(){
            const form = $('#address-form');
            
            // Basic validation
            if(!form[0].checkValidity()){
                form[0].reportValidity();
                return;
            }
            
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response){
                    if(response.success){
                        location.reload(); // Reload to show updated list
                    } else {
                        alert(response.message || 'Failed to save address');
                        btn.prop('disabled', false).text('Save Address');
                    }
                },
                error: function(xhr){
                    const resp = xhr.responseJSON || {};
                    const errors = resp.errors;
                    const topMessage = resp.message || 'Failed to save address. Please fix the highlighted fields.';
                    if(errors){
                        let errorMsg = topMessage + '\n\n';
                        for(let field in errors){
                            errorMsg += errors[field].join('\n') + '\n';
                        }
                        alert(errorMsg.trim());
                    } else {
                        alert(topMessage);
                    }
                    btn.prop('disabled', false).text('Save Address');
                }
            });
        });
        
        // Edit address
        $('.edit-address-btn').on('click', function(){
            const addressId = $(this).data('address-id');
            
            // Fetch address data
            $.ajax({
                url: '{{ url($path."/address") }}/' + addressId,
                type: 'GET',
                success: function(address){
                    $('#form-title').text('Edit Address');
                    $('#address_id').val(address.id);
                    $('#is_editing').val('1');
                    $('#name').val(address.name);
                    $('#company').val(address.company || '');
                    $('#email').val(address.email);
                    $('#phone').val(address.phone || '');
                    $('#country').val(address.country || 'US').trigger('change');
                    $('#street').val(address.street);
                    $('#city').val(address.city);
                    
                    if(address.country === 'US'){
                        $('#state').val(address.state_province || '');
                    } else {
                        $('#province').val(address.state_province || '');
                    }
                    
                    $('#zip').val(address.zip);
                    $('#default').prop('checked', address.default);
                    
                    $('#saved-addresses-section').hide();
                    $('#address-form-section').show();
                },
                error: function(){
                    alert('Failed to load address data');
                }
            });
        });
        
        // Set default address
        $('.set-default-btn').on('click', function(){
            const addressId = $(this).data('address-id');
            const btn = $(this);
            
            btn.prop('disabled', true);
            
            $.ajax({
                url: '{{ url($path."/address") }}/' + addressId + '/set-default',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response){
                    if(response.success){
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to set default');
                        btn.prop('disabled', false);
                    }
                },
                error: function(){
                    alert('Failed to set default address');
                    btn.prop('disabled', false);
                }
            });
        });
        
        // Delete address
        $('.delete-address-btn').on('click', function(){
            if(!confirm('Are you sure you want to delete this address?')){
                return;
            }
            
            const addressId = $(this).data('address-id');
            const btn = $(this);
            
            btn.prop('disabled', true);
            
            $.ajax({
                url: '{{ url($path."/address") }}/' + addressId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response){
                    if(response.success){
                        $(`.address-card[data-address-id="${addressId}"]`).fadeOut(300, function(){
                            $(this).remove();
                            // Check if no addresses left
                            if($('.address-card').length === 0){
                                location.reload();
                            }
                        });
                    } else {
                        alert(response.message || 'Failed to delete address');
                        btn.prop('disabled', false);
                    }
                },
                error: function(xhr){
                    const resp = xhr.responseJSON || {};
                    const message = resp.message || 'Failed to delete address';
                    alert(message);
                    btn.prop('disabled', false);
                }
            });
        });
        

    </script>
@endsection
