<?php
$path = $path ?? '';
?>
<!-- DONATION -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-12" style="max-height:fit-content;">
            <div class="topDonate">
                <form id="donation-form" onsubmit="event.preventDefault(); handleHeaderDonationSubmit(); return false;">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-sm-4 mr-4">
                            <div class="can-toggle branding">
                                <input id="d" type="checkbox" name="recurring" class="rec">
                                <label for="d">
                                    <div class="can-toggle__switch border" data-checked="ONCE" data-unchecked="MONTHLY"></div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="sr-only" for="donationAmount">Amount</label>
                            <div class="input-group ml-1 donate-input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text border-right-0">$</div>
                                </div>
                                <input type="text" name="amount" value="20" class="form-control border-left-0 p-0" id="donationAmount" placeholder="20">
                            </div>
                        </div>
                        <div class="form-group col-sm-2 ml-1">
                            <button type="submit" class="btn btn-secondary btn-sm" id="donate-button">DONATE</button>
                        </div>
                    </div>
                    <div id="donation-errors" class="text-danger small mt-1" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stripe Payment Modal -->
<div class="modal fade" id="donationModal" tabindex="-1" role="dialog" aria-labelledby="donationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="donationModalLabel">Make a Donation</h5>
                <button type="button" class="close close-modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Donation Form Inside Modal -->
                <form id="modal-donation-form">
                    @csrf
                    <div class="form-group">
                        <label>Donation Type</label>
                        <div class="can-toggle branding">
                            <input id="modal-d" type="checkbox" name="recurring" class="rec" checked>
                            <label for="modal-d">
                                <div class="can-toggle__switch border" data-checked="ONCE" data-unchecked="MONTHLY"></div>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="modal-donationAmount">Amount</label>
                        <div class="input-group donate-input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text border-right-0">$</div>
                            </div>
                            <input type="text" name="amount" value="20" class="form-control border-left-0" id="modal-donationAmount" placeholder="20">
                        </div>
                    </div>
                    
                    <!-- Anonymous Toggle -->
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modal-anonymous" name="anonymous">
                            <label class="form-check-label" for="modal-anonymous">
                                Make this donation anonymously
                            </label>
                        </div>
                        <small class="text-muted" id="anonymous-warning" style="display:none;">
                            <i class="fas fa-info-circle"></i> Anonymous donations do not receive tax receipts.
                        </small>
                    </div>
                    
                    <!-- Optional Message -->
                    <div class="form-group">
                        <label for="modal-message">Message (Optional)</label>
                        <textarea class="form-control" id="modal-message" name="message" rows="2" maxlength="1000" placeholder="Share why you're supporting UN|HUSHED..."></textarea>
                        <small class="text-muted">Maximum 1000 characters</small>
                    </div>
                    
                    <div id="modal-donation-errors" class="text-danger small mt-2" style="display:none;"></div>
                </form>
                
                <hr class="my-3">
                
                <!-- Auth Required Notice -->
                <div id="auth-required-notice" class="alert alert-info" style="display:none;">
                    <i class="fas fa-user-lock"></i> <strong>Login Required</strong><br>
                    <span id="auth-reason"></span>
                </div>
                
                <!-- Login/Register Forms (Hidden by default) -->
                <div id="auth-section" style="display:none;">
                    <ul class="nav nav-tabs" id="authTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login-panel" role="tab">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="register-tab" data-toggle="tab" href="#register-panel" role="tab">Create Account</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="authTabContent">
                        <div class="tab-pane fade show active" id="login-panel" role="tabpanel">
                            <form id="quick-login-form">
                                @csrf
                                <div class="form-group">
                                    <label for="login-email">Email</label>
                                    <input type="email" class="form-control" id="login-email" required>
                                </div>
                                <div class="form-group">
                                    <label for="login-password">Password</label>
                                    <input type="password" class="form-control" id="login-password" required>
                                </div>
                                <div id="login-errors" class="text-danger small mb-2" style="display:none;"></div>
                                <button type="submit" class="btn btn-primary btn-block">Login & Continue</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="register-panel" role="tabpanel">
                            <form id="quick-register-form">
                                @csrf
                                <div class="form-group">
                                    <label for="register-name">Full Name</label>
                                    <input type="text" class="form-control" id="register-name" required>
                                </div>
                                <div class="form-group">
                                    <label for="register-email">Email</label>
                                    <input type="email" class="form-control" id="register-email" required>
                                </div>
                                <div class="form-group">
                                    <label for="register-password">Password</label>
                                    <input type="password" class="form-control" id="register-password" minlength="8" required>
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>
                                <div class="form-group">
                                    <label for="register-password-confirm">Confirm Password</label>
                                    <input type="password" class="form-control" id="register-password-confirm" required>
                                </div>
                                <div id="register-errors" class="text-danger small mb-2" style="display:none;"></div>
                                <button type="submit" class="btn btn-primary btn-block">Create Account & Continue</button>
                            </form>
                        </div>
                    </div>
                    <hr class="my-3">
                </div>
                
                <!-- Payment Section -->
                <div id="payment-section">
                    <div class="mb-3">
                        <p class="mb-2"><strong>Donation Type:</strong> <span id="modal-donation-type-display">One-time</span></p>
                        <p class="mb-0"><strong>Amount:</strong> $<span id="modal-donation-amount-display">20.00</span></p>
                    </div>
                    
                    <!-- Stripe Card Element -->
                    <div class="form-group">
                        <label for="modal-card-element">Card Details</label>
                        <div id="modal-card-element" class="form-control" style="height: 40px; padding-top: 10px;">
                            <!-- Stripe Card Element will be inserted here -->
                        </div>
                        <div id="modal-card-errors" class="text-danger small mt-2" role="alert"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="modal-complete-donation-button">
                    <span id="modal-button-text">Complete Donation</span>
                    <span id="modal-button-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
// Global function to handle header donation form submission (defined early to prevent page refresh)
window.handleHeaderDonationSubmit = function() {
    console.log('Header donation form submitted');
    const amount = parseFloat(document.getElementById('donationAmount').value);
    const recurring = !document.getElementById('d').checked;
    
    // Validate amount
    if (!amount || amount <= 0) {
        const donationErrors = document.getElementById('donation-errors');
        if (donationErrors) {
            donationErrors.textContent = 'Please enter a valid donation amount';
            donationErrors.style.display = 'block';
        }
        return false;
    }
    
    // Hide external form errors
    const donationErrors = document.getElementById('donation-errors');
    if (donationErrors) {
        donationErrors.style.display = 'none';
    }
    
    // Call the global openDonationModal function
    if (window.openDonationModal) {
        window.openDonationModal(amount, recurring);
    } else {
        console.error('openDonationModal function not yet loaded');
        // Store the values and retry after a short delay
        setTimeout(function() {
            if (window.openDonationModal) {
                window.openDonationModal(amount, recurring);
            }
        }, 100);
    }
    
    return false;
};

// Expose global function to open donation modal (defined early so onclick handlers work)
window.openDonationModal = function(amount, recurring) {
    console.log('Global openDonationModal called', {amount, recurring});
    
    // Wait for DOM if elements aren't ready yet
    if (!document.getElementById('donationModal')) {
        console.log('Modal not ready, waiting for DOM...');
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                window.openDonationModal(amount, recurring);
            });
        }
        return;
    }
    
    // Set default values if not provided
    amount = amount || 20;
    recurring = recurring !== undefined ? recurring : false;
    
    // Update modal form fields
    const modalAmountElem = document.getElementById('modal-donationAmount');
    const modalToggleElem = document.getElementById('modal-d');
    const modalTypeDisplay = document.getElementById('modal-donation-type-display');
    const modalAmountDisplay = document.getElementById('modal-donation-amount-display');
    const anonymousCheckbox = document.getElementById('modal-anonymous');
    const messageField = document.getElementById('modal-message');
    
    if (modalAmountElem) modalAmountElem.value = amount;
    if (modalToggleElem) modalToggleElem.checked = !recurring;
    if (modalTypeDisplay) modalTypeDisplay.textContent = recurring ? 'Monthly Recurring' : 'One-time';
    if (modalAmountDisplay) modalAmountDisplay.textContent = parseFloat(amount).toFixed(2);
    if (anonymousCheckbox) anonymousCheckbox.checked = false;
    if (messageField) messageField.value = '';
    
    // Clear errors
    const modalCardErrors = document.getElementById('modal-card-errors');
    const modalDonationErrors = document.getElementById('modal-donation-errors');
    if (modalCardErrors) modalCardErrors.textContent = '';
    if (modalDonationErrors) modalDonationErrors.style.display = 'none';
    
    // Call checkAuthRequirements if it exists (will be defined in DOMContentLoaded)
    if (typeof checkAuthRequirements === 'function') {
        checkAuthRequirements();
    }
    
    // Show modal using Bootstrap 5 or jQuery
    const modalEl = document.getElementById('donationModal');
    if (modalEl) {
        if (window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else if (window.jQuery) {
            jQuery(modalEl).modal('show');
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in (set by Blade)
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    
    // Initialize Stripe with your publishable key
    const stripe = Stripe('{{ config("cashier.key") }}');
    const elements = stripe.elements();
    
    // Create card element for modal
    const modalCardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    
    const donationModal = $('#donationModal');
    let modalCardMounted = false;
    
    // Additional event listener for external donation form (backup to inline handler)
    const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        console.log('Attaching submit listener to donation form');
        donationForm.addEventListener('submit', function(e) {
            console.log('Form submit event caught by listener');
            e.preventDefault();
            handleHeaderDonationSubmit();
            return false;
        });
    }
    
    // Update modal display when modal form values change
    const modalAmountInput = document.getElementById('modal-donationAmount');
    const modalToggle = document.getElementById('modal-d');
    const anonymousCheckbox = document.getElementById('modal-anonymous');
    
    function updateModalDisplay() {
        const amount = parseFloat(modalAmountInput.value) || 20;
        const recurring = !modalToggle.checked;
        document.getElementById('modal-donation-type-display').textContent = recurring ? 'Monthly Recurring' : 'One-time';
        document.getElementById('modal-donation-amount-display').textContent = amount.toFixed(2);
        
        // Check auth requirements
        checkAuthRequirements();
    }
    
    // Check if authentication is required
    function checkAuthRequirements() {
        const recurring = !modalToggle.checked;
        const anonymous = anonymousCheckbox.checked;
        
        // Show/hide anonymous warning
        const anonymousWarning = document.getElementById('anonymous-warning');
        if (anonymous) {
            anonymousWarning.style.display = 'block';
        } else {
            anonymousWarning.style.display = 'none';
        }
        
        // Recurring OR non-anonymous requires login
        const requiresAuth = (recurring || !anonymous) && !isLoggedIn;
        
        const authSection = document.getElementById('auth-section');
        const authNotice = document.getElementById('auth-required-notice');
        const authReason = document.getElementById('auth-reason');
        const paymentSection = document.getElementById('payment-section');
        const completeButton = document.getElementById('modal-complete-donation-button');
        
        if (requiresAuth) {
            // Show auth section, hide payment
            authSection.style.display = 'block';
            authNotice.style.display = 'block';
            paymentSection.style.display = 'none';
            completeButton.style.display = 'none';
            
            if (recurring) {
                authReason.textContent = 'Recurring donations require an account to manage your subscription.';
            } else {
                authReason.textContent = 'Tax-deductible donations require an account to receive your receipt.';
            }
        } else {
            // Hide auth, show payment
            authSection.style.display = 'none';
            authNotice.style.display = 'none';
            paymentSection.style.display = 'block';
            completeButton.style.display = 'inline-block';
        }
    }
    
    if (modalAmountInput) {
        modalAmountInput.addEventListener('input', updateModalDisplay);
    }
    if (modalToggle) {
        modalToggle.addEventListener('change', updateModalDisplay);
    }
    if (anonymousCheckbox) {
        anonymousCheckbox.addEventListener('change', updateModalDisplay);
    }
    
    // Mount card element when modal opens
    donationModal.on('shown.bs.modal', function() {
        console.log('Modal shown event fired, modalCardMounted:', modalCardMounted);
        if (!modalCardMounted) {
            try {
                const cardContainer = document.getElementById('modal-card-element');
                if (cardContainer) {
                    console.log('Attempting to mount card element...');
                    modalCardElement.mount('#modal-card-element');
                    modalCardMounted = true;
                    console.log('Card element mounted successfully on shown.bs.modal');
                }
            } catch (e) {
                console.error('Error mounting card element:', e);
                // If element was already mounted, just update the flag
                if (e.message && e.message.includes('already been mounted')) {
                    modalCardMounted = true;
                    console.log('Card element was already mounted');
                } else {
                    // Try to remount after a brief delay if first attempt fails
                    setTimeout(function() {
                        if (!modalCardMounted) {
                            try {
                                modalCardElement.mount('#modal-card-element');
                                modalCardMounted = true;
                                console.log('Card element mounted on retry');
                            } catch (retryError) {
                                console.error('Retry failed:', retryError);
                                if (retryError.message && retryError.message.includes('already been mounted')) {
                                    modalCardMounted = true;
                                }
                            }
                        }
                    }, 100);
                }
            }
        } else {
            console.log('Card element already mounted, skipping mount');
        }
    });
    
    // Expose function to ensure card is mounted (for direct modal opens)
    window.ensureModalCardMounted = function() {
        console.log('ensureModalCardMounted called, modalCardMounted:', modalCardMounted);
        if (!modalCardMounted) {
            const cardContainer = document.getElementById('modal-card-element');
            if (cardContainer && cardContainer.offsetParent !== null) {
                try {
                    console.log('Attempting to mount card element via direct call...');
                    modalCardElement.mount('#modal-card-element');
                    modalCardMounted = true;
                    console.log('Card element mounted via direct call');
                } catch (e) {
                    console.error('Error mounting card element via direct call:', e);
                    if (e.message && e.message.includes('already been mounted')) {
                        modalCardMounted = true;
                        console.log('Card element was already mounted (direct call)');
                    }
                }
            } else {
                console.log('Card container not visible yet');
            }
        } else {
            console.log('Card already mounted (ensureModalCardMounted)');
        }
    };
    
    // Handle quick login form
    const quickLoginForm = document.getElementById('quick-login-form');
    if (quickLoginForm) {
        quickLoginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const loginErrors = document.getElementById('login-errors');
            
            try {
                const response = await fetch('{{ url("/{$path}/login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Login successful - reload page to update auth state
                    window.location.reload();
                } else {
                    loginErrors.textContent = data.message || 'Invalid credentials';
                    loginErrors.style.display = 'block';
                }
            } catch (error) {
                loginErrors.textContent = 'An error occurred. Please try again.';
                loginErrors.style.display = 'block';
            }
        });
    }
    
    // Handle quick register form
    const quickRegisterForm = document.getElementById('quick-register-form');
    if (quickRegisterForm) {
        quickRegisterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('register-name').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const passwordConfirm = document.getElementById('register-password-confirm').value;
            const registerErrors = document.getElementById('register-errors');
            
            // Check password match
            if (password !== passwordConfirm) {
                registerErrors.textContent = 'Passwords do not match';
                registerErrors.style.display = 'block';
                return;
            }
            
            try {
                const response = await fetch('{{ url("/{$path}/register") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({ 
                        name, 
                        email, 
                        password, 
                        password_confirmation: passwordConfirm 
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Registration successful - reload page to update auth state
                    window.location.reload();
                } else {
                    registerErrors.textContent = data.message || 'Registration failed';
                    registerErrors.style.display = 'block';
                }
            } catch (error) {
                registerErrors.textContent = 'An error occurred. Please try again.';
                registerErrors.style.display = 'block';
            }
        });
    }
    
    // Display card errors
    modalCardElement.on('change', function(event) {
        const modalCardErrors = document.getElementById('modal-card-errors');
        if (event.error) {
            modalCardErrors.textContent = event.error.message;
        } else {
            modalCardErrors.textContent = '';
        }
    });
    
    // Handle payment submission
    const modalCompleteDonationButton = document.getElementById('modal-complete-donation-button');
    modalCompleteDonationButton.addEventListener('click', async function() {
        const amount = parseFloat(document.getElementById('modal-donationAmount').value);
        const recurring = !document.getElementById('modal-d').checked;
        const anonymous = document.getElementById('modal-anonymous').checked;
        const message = document.getElementById('modal-message').value;
        const modalCardErrors = document.getElementById('modal-card-errors');
        const modalDonationErrors = document.getElementById('modal-donation-errors');
        
        // Validate amount
        if (!amount || amount <= 0) {
            modalDonationErrors.textContent = 'Please enter a valid donation amount';
            modalDonationErrors.style.display = 'block';
            return;
        }
        
        modalDonationErrors.style.display = 'none';
        
        // Disable button and show loading
        modalCompleteDonationButton.disabled = true;
        document.getElementById('modal-button-text').style.display = 'none';
        document.getElementById('modal-button-spinner').style.display = 'inline-block';
        
        try {
            // First, create PaymentMethod from card element
            const {error: pmError, paymentMethod} = await stripe.createPaymentMethod({
                type: 'card',
                card: modalCardElement,
            });
            
            if (pmError) {
                throw new Error(pmError.message);
            }
            
            // Send payment method to server to create payment/subscription
            const response = await fetch('{{ url("/donate_checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify({
                    amount: amount,
                    recurring: recurring,
                    payment_method_id: paymentMethod.id,
                    anonymous: anonymous,
                    message: message
                })
            });
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Server returned non-JSON response:', text);
                throw new Error('Server error. Please try again or contact support.');
            }
            
            const data = await response.json();
            
            // Check if authentication is required (safety check)
            if (response.status === 401 && data.requires_auth) {
                // Show auth section and hide payment section
                isLoggedIn = false;
                checkAuthRequirements();
                modalCompleteDonationButton.disabled = false;
                document.getElementById('modal-button-text').style.display = 'inline';
                document.getElementById('modal-button-spinner').style.display = 'none';
                return;
            }
            
            if (!response.ok) {
                throw new Error(data.message || 'Payment failed');
            }
            
            // Handle based on payment type
            if (recurring) {
                // For subscriptions, check if confirmation needed
                if (data.requires_action) {
                    const {error: confirmError, subscription} = await stripe.confirmCardPayment(
                        data.payment_intent_client_secret
                    );
                    
                    if (confirmError) {
                        throw new Error(confirmError.message);
                    }
                }
                
                // Subscription created successfully
                window.location.href = '{{ url("/{$path}/dashboard/my-donations") }}?donation_success=recurring';
            } else {
                // For one-time payments, check if confirmation needed
                if (data.requires_action) {
                    const {error: confirmError, paymentIntent} = await stripe.confirmCardPayment(
                        data.client_secret
                    );
                    
                    if (confirmError) {
                        throw new Error(confirmError.message);
                    }
                    
                    // Payment confirmed
                    window.location.href = '{{ url("/{$path}/dashboard/my-donations") }}?donation_success=onetime';
                } else {
                    // Payment succeeded immediately
                    window.location.href = '{{ url("/{$path}/dashboard/my-donations") }}?donation_success=onetime';
                }
            }
        } catch (error) {
            modalCardErrors.textContent = error.message;
            modalCompleteDonationButton.disabled = false;
            document.getElementById('modal-button-text').style.display = 'inline';
            document.getElementById('modal-button-spinner').style.display = 'none';
        }
    });
    
    // Handle modal close buttons
    document.querySelectorAll('.close-modal, .cancel-modal').forEach(function(button) {
        button.addEventListener('click', function() {
            donationModal.modal('hide');
        });
    });
    
    // Reset modal on close
    donationModal.on('hidden.bs.modal', function() {
        const modalCardErrors = document.getElementById('modal-card-errors');
        const modalDonationErrors = document.getElementById('modal-donation-errors');
        if (modalCardErrors) modalCardErrors.textContent = '';
        if (modalDonationErrors) modalDonationErrors.style.display = 'none';
        modalCompleteDonationButton.disabled = false;
        document.getElementById('modal-button-text').style.display = 'inline';
        document.getElementById('modal-button-spinner').style.display = 'none';
    });
    
    // Handle toggle styling
    const externalToggle = document.getElementById('d');
    if (externalToggle) {
        externalToggle.addEventListener('change', function() {
            // Just for visual feedback
        });
    }
});
</script>

<style>
    .donate-input-group .input-group-prepend,
    .donate-input-group .input-group-text,
    .donate-input-group .form-control {
        height: 31px;
        line-height: 31px;
        font-size: 0.875rem;
    }
    .donate-input-group .input-group-text,
    .can-toggle {
        display: flex;
        align-items: center;
    }
    .topDonate .can-toggle {
        height: 31px;
        line-height: 31px;
    }
    .topDonate .can-toggle__switch {
        height: 31px !important;
        line-height: 31px !important;
        font-size: 0.75rem;
        padding: 0;
        margin-top: 9px;
    }
    .topDonate .can-toggle__switch:before,
    .topDonate .can-toggle__switch:after {
        height: 31px !important;
        line-height: 32px !important;
        padding: 0 !important;
    }
    .topDonate .can-toggle label {
        height: 31px;
    }
    /* Grey out unselected toggle option */
    .can-toggle__switch:before {
        color: #999 !important;
        opacity: 0.5;
    }
    
    .can-toggle__switch:after {
        color: #333 !important;
        opacity: 1;
        font-weight: 600;
    }
    
    @media screen and (max-width: 780px){
        .topDonate{
            display: none !important;
        }
    }
    @media all and (min-width: 780px) {
        .topDonate{
            position: fixed;
            top: 0;
            right: 0;
            z-index: 1000;
            height: 38px;
            width: 330px;
            background: white;
            padding: 5px 10px;
            border-top-left-radius: 10px;
        }
    }
</style>

