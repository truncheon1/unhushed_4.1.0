@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url($path.'/cart_subscriptions') }}">Subscription Cart </a> |
            <span style="font-weight: bold;color:#9acd57;">Payment</span>
        </div>
    </div>

    <!-- CARTS HEADER-->
    <div class="container">
        @include('store.cart.statusbar')
        <!-- CART CODE-->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <div class="card" style="width: 50rem;">
                    <div class="card-body">
                        <p class="diazo text-center" style="font-size: 26px; line-height:30px;">Complete Your Subscription</p>
                        <div class="row">
                            <!-- Left Column: Payment Form -->
                            <div class="col-md-6 col-sm-12">
                                <strong style="font-size: 1rem;">Payment</strong>
                                <form id="subscription-payment-form">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $orderId }}">
                                    
                                    <div id="payment-element" class="mb-3">
                                        <!-- Stripe Payment Element will be inserted here -->
                                    </div>
                                    <div id="payment-errors" class="text-danger small mt-2" role="alert"></div>

                                    <div class="text-right">
                                        <a href="{{ url($path.'/cart_subscriptions') }}" class="btn btn-secondary mr-2">
                                            <i class="fa fa-angle-left"></i> Back to Cart
                                        </a>
                                        <button type="submit" class="btn btn-green" id="submit-button" style="background-color: #9acd57; color: white; border-color: #9acd57;">
                                            <span id="button-text">SUBSCRIBE</span>
                                            <span id="button-spinner" class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" style="display:none;"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Right Column: Order Summary -->
                            <div class="col-md-6 col-sm-12">
                                <div class="mb-4 p-3" style="background-color: #f8f9fa; border-radius: 5px;">
                                    <strong style="font-size: 1rem;">Order Summary</strong>
                                    <hr>
                                    @foreach($items as $item)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ $item['name'] }} ({{ $item['qty'] }})</span>
                                            <span>${{ number_format($item['subtotal'], 2) }}</span>
                                        </div>
                                    @endforeach
                                    <!-- Recurring Info Alert -->
                                    <div class="alert-checkout">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Subscription:</strong> Starting {{ now()->addYear()->format('m-d-Y') }} you will be charged 
                                        ${{ number_format($recurringTotal, 2) }} per year for continued access to your subscriptions.
                                    </div>
                                    @if($discount)
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Discount ({{ $discount['code'] }})</span>
                                            <span>-${{ number_format($discount['amount'], 2) }}</span>
                                        </div>
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3" style="font-size: 1rem;">
                                        <strong>Due Today</strong>
                                        <strong class="text-primary">${{ number_format($total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
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
            <h4 class="text-white mb-2">Processing Your Subscription</h4>
            <p class="text-white-50">Please wait, do not refresh or close this page...</p>
        </div>
    </div>
</section>

<style>
    .alert-checkout {
        /*position*/
        padding: 5px 10px;
        /*design*/
        background: #4077b0;
        border-radius: 6px;
        /*text*/
        color: #f0f3fa;
    }
    
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
</style>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Stripe
    const stripe = Stripe('{{ config("cashier.key") }}');
    
    @if($clientSecret)
    // Create Payment Element with client secret from SetupIntent
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
    
    // Handle form submission
    const form = document.getElementById('subscription-payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Disable button and show loading
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        buttonSpinner.style.display = 'inline-block';
        paymentErrors.textContent = '';
        
        try {
            // Confirm the setup with Stripe (this will validate the payment element)
            const {error: submitError, setupIntent} = await stripe.confirmSetup({
                elements,
                confirmParams: {
                    return_url: window.location.href, // We'll handle this ourselves
                },
                redirect: 'if_required'
            });
            
            if (submitError) {
                throw new Error(submitError.message);
            }
            
            // Only show processing overlay after validation passes
            document.getElementById('processing-overlay').style.display = 'flex';
            
            // Send the setup intent ID to server to create subscription
            const response = await fetch('{{ url("/{$path}/subscription_payment_process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    order_id: {{ $orderId }},
                    setup_intent_id: setupIntent.id
                })
            });
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Server returned non-JSON response:', text);
                throw new Error('Server error. Please try again or contact support.');
            }
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Payment failed');
            }
            
            // Check if subscription payment requires additional action (3D Secure)
            if (data.requires_action && data.client_secret) {
                const {error: confirmError} = await stripe.confirmCardPayment(
                    data.client_secret
                );
                
                if (confirmError) {
                    throw new Error(confirmError.message);
                }
                
                // Payment confirmed, redirect to success
                window.location.href = data.success_url;
            } else if(data.redirect_to_org_creation) {
                // User needs to create organization before completing subscription
                window.location.href = data.success_url;
            } else {
                // Payment succeeded immediately - store success message for next page
                sessionStorage.setItem('purchase_success_message', 'Thank you! Your subscription is now active and you have been assigned to it. \nYou can now assign access to your team members below.');
                window.location.href = data.success_url;
            }
        } catch (error) {
            // Hide processing overlay if it was shown
            document.getElementById('processing-overlay').style.display = 'none';
            paymentErrors.textContent = error.message;
            submitButton.disabled = false;
            buttonText.style.display = 'inline';
            buttonSpinner.style.display = 'none';
        }
    });
    @else
    // No client secret - show error
    document.getElementById('payment-errors').textContent = 'Unable to initialize payment form. Please refresh and try again.';
    document.getElementById('submit-button').disabled = true;
    @endif
});
</script>
@endsection
