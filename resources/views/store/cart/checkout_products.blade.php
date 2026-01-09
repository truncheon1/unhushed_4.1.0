@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url($path.'/cart_products') }}">Product Cart </a> |
            <span style="font-weight: bold;color:#9acd57;">Checkout</span>
        </div>
    </div>

    <!-- CHECKOUT CONTENT -->
    <div class="container">
        @include('store.cart.statusbar')
        <!-- CHECKOUT CODE-->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <!-- Left Column: Shipping & Payment -->
                <div class="col-md-7 col-sm-12">
                    <form id="product-payment-form">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $cartId }}">
                        <input type="hidden" name="selected_shipping_rate" id="selected_shipping_rate" value="">
                        @if($needsShipping)
                        <!-- Shipping Address Section -->
                        <div class="mb-4">
                            <strong style="font-size: 1rem;">Shipping</strong>
                            @if($shippingAddress)
                                <div class="col-10 border rounded p-3 mb-2 mt-2" style="background-color: #f8f9fa;">
                                    <div class="mb-2"><i class="fas fa-shipping-fast"></i> Shipping To:</div>
                                    <div><strong>{{ $shippingAddress->name }}</strong></div>
                                    @if($shippingAddress->company)<div>{{ $shippingAddress->company }}</div>@endif
                                    <div>{{ $shippingAddress->street }}</div>
                                    <div>{{ $shippingAddress->city }}, {{ $shippingAddress->state_province }} {{ $shippingAddress->zip }}</div>
                                    @if($shippingAddress->phone)<div class="text-muted small mb-2">Phone: {{ $shippingAddress->phone }}</div>@endif
                                        @if($shippingAddress->default) <span class="badge bg-primary p-2">Default</span>@endif
                                        <a href="{{ url($path.'/checkout_address') }}" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-edit"></i> Change Address
                                        </a>
                                </div>
                                
                                <div>
                                    
                                </div>
                            @else
                                <div class="alert alert-info mt-2">
                                    <i class="fas fa-info-circle"></i> You don't have a shipping address saved.
                                    <a href="{{ url($path.'/checkout_address') }}" class="btn btn-sm btn-primary ml-2">
                                        <i class="fa fa-plus"></i> Add Shipping Address
                                    </a>
                                </div>
                            @endif
                            
                        </div>
                        
                        <!-- Shipping Method Selection -->
                        <div id="shipping-methods-section" class="mb-4" style="display: none;">
                            <strong style="font-size: 1rem;">Shipping Method</strong>
                            <div id="shipping-methods-container"></div>
                        </div>
                        @endif
                        
                        <!-- Payment Section -->
                        <div id="payment-section" class="mb-4">
                            <strong style="font-size: 1rem;">Payment</strong>
                            <!-- Payment Element -->
                            <div id="payment-element" class="mb-3"></div>
                            <div id="payment-errors" class="text-danger small mt-2" role="alert"></div>
                        </div>

                        <div class="text-right">
                            <a href="{{ url($path.'/cart_products') }}" class="btn btn-secondary mr-2">
                                <i class="fa fa-angle-left"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-green" id="submit-button" style="background-color: #9acd57; color: white; border-color: #9acd57;" @if($needsShipping) disabled @endif>
                                <span id="button-text">PURCHASE</span>
                                <span id="button-spinner" class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" style="display:none;"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-md-5 col-sm-12 p-3" style="background-color:#f8f9fa;">
                        <strong style="font-size: 1rem;">Order Summary</strong>
                        <hr>
                        <!-- Itemized List -->
                        @foreach($items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['name'] }} @if($item['qty'] > 1)({{ $item['qty'] }})@endif</span>
                                <span>${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <!-- Sales Tax (8.25%) -->
                        @if($taxAmount > 0)
                        <div id="tax-line-item">
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Sales Tax (8.25%)</span>
                                <span id="tax-amount">${{ number_format($taxAmount, 2) }}</span>
                            </div>
                        </div>
                        @endif
                        <!-- Shipping -->
                        @if($needsShipping)
                            <div id="shipping-line-item" style="display: none;">
                                <div class="d-flex justify-content-between mb-2 text-muted">
                                    <span>Shipping (<span id="shipping-method-name"></span>)</span>
                                    <span id="shipping-cost">$0.00</span>
                                </div>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3" style="font-size: 1.25rem;">
                            <strong>Total</strong>
                            <strong class="text-primary" id="order-total">${{ number_format($subtotal + ($subtotal * 0.0825), 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Processing Overlay -->
    <div id="processing-overlay" style="display: none;">
        <div class="processing-content">
            <div class="spinner-border text-light mb-3" role="status" style="width: 4rem; height: 4rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h4 class="text-white mb-2">Processing Your Payment</h4>
            <p class="text-white-50">Please wait, do not refresh or close this page...</p>
        </div>
    </div>
</section>

<style>
    #processing-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .processing-content {
        text-align: center;
        padding: 2rem;
    }
    
    .shipping-method-option {
        padding: 12px;
        border: 2px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .shipping-method-option:hover {
        border-color: #9acd57;
        background-color: #f8f9fa;
    }
    
    .shipping-method-option.selected {
        border-color: #9acd57;
        background-color: #f0f8e6;
    }
</style>

<!-- Stripe.js - Load only if not already loaded -->
<script>
// Only load Stripe.js if not already present
if (typeof Stripe === 'undefined') {
    var stripeScript = document.createElement('script');
    stripeScript.src = 'https://js.stripe.com/v3/';
    stripeScript.onload = function() {
        console.log('Stripe.js loaded');
        initializeCheckout();
    };
    document.head.appendChild(stripeScript);
} else {
    console.log('Stripe.js already loaded');
    // Initialize immediately if Stripe is already available
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCheckout);
    } else {
        initializeCheckout();
    }
}

function initializeCheckout() {
    console.log('Checkout page loaded');
    
    // Initialize Stripe
    const stripe = Stripe('{{ config("cashier.key") }}');
    const needsShipping = {{ $needsShipping ? 'true' : 'false' }};
    const cartWeight = {{ $totalWeight }};
    const subtotal = {{ $subtotal }};
    const taxAmount = {{ $taxAmount }}; // Calculated server-side, excludes training products (category=7)
    
    console.log('needsShipping:', needsShipping);
    console.log('cartWeight:', cartWeight);
    console.log('subtotal:', subtotal);
    console.log('taxAmount (server-side):', taxAmount);
    
    let selectedShippingCost = 0;
    let selectedShippingMethod = '';
    
    @if($clientSecret)
    console.log('Client secret exists, initializing Stripe Elements');
    
    // Create Payment Element with client secret from PaymentIntent
    const elements = stripe.elements({
        clientSecret: '{{ $clientSecret }}',
        appearance: {
            theme: 'stripe',
            variables: {
                colorPrimary: '#9acd57',
            }
        }
    });
    
    // Create and mount the Payment Element
    const paymentElement = elements.create('payment', {
        layout: 'tabs',
        defaultValues: {
            billingDetails: {
                name: '{{ Auth::user()->name ?? "" }}',
                email: '{{ Auth::user()->email ?? "" }}'
            }
        }
    });
    
    paymentElement.mount('#payment-element');
    
    // Handle payment errors
    const paymentErrors = document.getElementById('payment-errors');
    paymentElement.on('change', function(event) {
        if (event.error) {
            paymentErrors.textContent = event.error.message;
        } else {
            paymentErrors.textContent = '';
        }
    });
    @endif
    
    // Auto-calculate shipping on page load
    async function loadShippingRates() {
        console.log('loadShippingRates called');
        
        if (!needsShipping) {
            console.log('No shipping needed');
            return;
        }
        
        @if($shippingAddress)
        const addressId = {{ $shippingAddress->id }};
        console.log('Address ID:', addressId);
        @else
        console.log('No shipping address found');
        return;
        @endif
        
        const statusDiv = document.getElementById('shipping-calculation-status');
        if (statusDiv) {
            statusDiv.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Calculating shipping rates...';
        }
        
        try {
            const url = '{{ url($path."/shipping/rates") }}?address_id=' + addressId;
            console.log('Fetching shipping rates from:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            console.log('Shipping rates response:', data);
            
            if (data.success) {
                if (data.digital_only) {
                    if (statusDiv) {
                        statusDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Digital order - no shipping required</span>';
                    }
                    document.getElementById('submit-button').disabled = false;
                    return;
                }
                
                displayShippingMethods(data.rates);
                
                // Auto-select cheapest rate
                if (data.rates && data.rates.length > 0) {
                    const cheapestRate = data.rates.reduce((min, rate) => 
                        (rate.rate || rate.cost) < (min.rate || min.cost) ? rate : min
                    );
                    
                    console.log('Cheapest rate:', cheapestRate);
                    
                    // Find and click the cheapest option
                    setTimeout(() => {
                        const options = document.querySelectorAll('.shipping-method-option');
                        console.log('Found shipping options:', options.length);
                        options.forEach(option => {
                            const optionCost = parseFloat(option.dataset.cost);
                            if (optionCost === (cheapestRate.rate || cheapestRate.cost)) {
                                console.log('Auto-selecting cheapest option');
                                option.click();
                            }
                        });
                    }, 100);
                }
                
                if (statusDiv) {
                    statusDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Shipping rates loaded - cheapest option selected</span>';
                }
                document.getElementById('shipping-methods-section').style.display = 'block';
            } else {
                console.error('Shipping rates error:', data.message);
                if (statusDiv) {
                    statusDiv.innerHTML = '<span class="text-danger">' + (data.message || 'Error calculating shipping') + '</span>';
                }
            }
        } catch (error) {
            console.error('Shipping calculation error:', error);
            if (statusDiv) {
                statusDiv.innerHTML = '<span class="text-danger">Error calculating shipping rates: ' + error.message + '</span>';
            }
        }
    }
    
    // Load shipping rates on page load
    if (needsShipping) {
        console.log('Auto-loading shipping rates on page load');
        loadShippingRates();
    }
    
    // Calculate shipping when button clicked (manual recalculation)
    const calcShippingBtn = document.getElementById('calculate-shipping-btn');
    if (calcShippingBtn) {
        console.log('Setting up recalculate button handler');
        calcShippingBtn.addEventListener('click', async function() {
            console.log('Recalculate button clicked');
            await loadShippingRates();
        });
    } else {
        console.log('Recalculate button not found');
    }
    
    function displayShippingMethods(rates) {
        console.log('displayShippingMethods called with rates:', rates);
        
        const container = document.getElementById('shipping-methods-container');
        if (!container) {
            console.error('shipping-methods-container not found');
            return;
        }
        
        container.innerHTML = '';
        
        if (!rates || rates.length === 0) {
            console.log('No rates available');
            container.innerHTML = '<div class="alert alert-warning">No shipping rates available. Please contact support.</div>';
            return;
        }
        
        console.log('Rendering', rates.length, 'shipping options');
        
        rates.forEach((rate, index) => {
            const option = document.createElement('div');
            option.className = 'shipping-method-option';
            option.dataset.cost = rate.rate || rate.cost;
            option.dataset.method = rate.display_name || rate.service;
            
            console.log('Creating option:', {
                service: rate.service,
                display_name: rate.display_name,
                cost: rate.rate || rate.cost,
                delivery: rate.delivery_days || rate.delivery_time
            });
            
            option.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${rate.display_name || rate.name || rate.service}</strong>
                        ${rate.delivery_days || rate.delivery_time ? `<br><small class="text-muted">${rate.delivery_days || rate.delivery_time}</small>` : ''}
                    </div>
                    <strong>$${parseFloat(rate.rate || rate.cost).toFixed(2)}</strong>
                </div>
            `;
            
            option.addEventListener('click', async function() {
                console.log('Shipping option clicked:', this.dataset.method);
                
                // Remove selected class from all options
                document.querySelectorAll('.shipping-method-option').forEach(el => {
                    el.classList.remove('selected');
                });
                
                // Add selected class to this option
                this.classList.add('selected');
                
                // Update shipping cost
                selectedShippingCost = parseFloat(this.dataset.cost);
                selectedShippingMethod = this.dataset.method;
                document.getElementById('selected_shipping_rate').value = JSON.stringify({
                    method: selectedShippingMethod,
                    cost: selectedShippingCost
                });
                
                console.log('Selected shipping:', {
                    method: selectedShippingMethod,
                    cost: selectedShippingCost
                });
                
                // Update Stripe PaymentIntent amount to include shipping
                try {
                    const updateResponse = await fetch('{{ url($path."/update-payment-amount") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            client_secret: '{{ $clientSecret }}',
                            shipping_cost: selectedShippingCost
                        })
                    });
                    
                    const updateData = await updateResponse.json();
                    console.log('PaymentIntent updated:', updateData);
                } catch (error) {
                    console.error('Error updating payment amount:', error);
                }
                
                // Update order total
                updateOrderTotal();
                
                // Show shipping in order summary
                document.getElementById('shipping-method-name').textContent = selectedShippingMethod;
                document.getElementById('shipping-cost').textContent = '$' + selectedShippingCost.toFixed(2);
                document.getElementById('shipping-line-item').style.display = 'block';
                
                // Enable submit button
                document.getElementById('submit-button').disabled = false;
            });
            
            container.appendChild(option);
        });
        
        console.log('Finished rendering shipping options');
    }
    
    function updateOrderTotal() {
        const total = subtotal + taxAmount + selectedShippingCost;
        console.log('Updating order total:', {
            subtotal: subtotal,
            tax: taxAmount,
            shipping: selectedShippingCost,
            total: total
        });
        document.getElementById('order-total').textContent = '$' + total.toFixed(2);
    }
    
    @if($clientSecret)
    // Handle form submission
    const form = document.getElementById('product-payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (needsShipping && !document.getElementById('selected_shipping_rate').value) {
            alert('Please select a shipping method');
            return;
        }
        
        // Disable button and show loading
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        buttonSpinner.style.display = 'inline-block';
        paymentErrors.textContent = '';
        
        // Show processing overlay
        document.getElementById('processing-overlay').style.display = 'flex';
        
        try {
            // Confirm the payment with Stripe
            const {error: submitError, paymentIntent} = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: window.location.href, // We'll handle this ourselves
                },
                redirect: 'if_required'
            });
            
            if (submitError) {
                throw new Error(submitError.message);
            }
            
            // Send the payment intent ID to server to complete purchase
            const formData = new FormData(form);
            formData.append('payment_intent_id', paymentIntent.id);
            @if($shippingAddress)
            formData.append('shipping_address_id', {{ $shippingAddress->id }});
            @endif
            
            const response = await fetch('{{ url($path."/product_payment_process") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Store success message in session storage for display after redirect
                if (data.success_message) {
                    sessionStorage.setItem('purchase_success_message', data.success_message);
                }
                // Redirect to success page
                window.location.href = data.redirect_url;
            } else {
                throw new Error(data.message || 'Payment processing failed');
            }
            
        } catch (error) {
            console.error('Payment error:', error);
            paymentErrors.textContent = error.message;
            
            // Hide overlay and re-enable button
            document.getElementById('processing-overlay').style.display = 'none';
            submitButton.disabled = false;
            buttonText.style.display = 'inline';
            buttonSpinner.style.display = 'none';
        }
    });
    @else
    document.getElementById('payment-element').innerHTML = '<div class="alert alert-danger">Unable to initialize payment. Please try again.</div>';
    @endif
}
</script>
@endsection
