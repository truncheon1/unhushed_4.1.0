# Stripe Donation System Implementation

## Overview
The donation system has been rebuilt to use Stripe Elements instead of PayPal. The implementation maintains the same UX/design while leveraging Stripe's secure payment processing for both one-time and recurring donations.

## Features Implemented

### 1. **One-Time Donations**
- Uses Stripe Payment Intents API
- Inline payment with Stripe Elements (card input)
- Modal popup for secure card entry
- Client-side payment confirmation
- Success redirect after payment

### 2. **Recurring Monthly Donations**
- Uses Stripe Checkout Sessions
- Creates subscription products and prices dynamically
- Redirect to Stripe-hosted checkout page
- Automatic recurring billing

### 3. **User Experience**
- ✅ Toggle preserved for one-time vs monthly selection
- ✅ Custom amount input with $20 default
- ✅ Same visual design and form style maintained
- ✅ Modal for card details (one-time only)
- ✅ Loading states and error handling
- ✅ Success page redirect

## Files Modified

### 1. **resources/views/layouts/donate.blade.php**
**Changes:**
- Removed PayPal form submission
- Added Stripe.js library
- Added Stripe Card Element
- Added payment modal for one-time donations
- Implemented JavaScript for:
  - Form validation
  - Stripe Element mounting
  - Payment Intent confirmation (one-time)
  - Checkout redirect (recurring)
  - Error display

**Key Components:**
```html
<!-- Donation Form (compact fixed position) -->
<form id="donation-form">
  - Toggle for one-time/recurring
  - Amount input field
  - Donate button
</form>

<!-- Payment Modal (shows for one-time donations) -->
<div class="modal" id="donationModal">
  - Displays donation details
  - Stripe Card Element
  - Complete donation button
</div>
```

### 2. **app/Http/Controllers/Store/CartDonateController.php**
**Complete Rewrite:**
- Removed all PayPal SDK dependencies
- Removed PayPal client initialization
- Added Stripe integration
- Separated one-time and recurring logic

**New Methods:**
- `donate_checkout(Request $request)` - Main entry point, validates and routes to payment type
- `createOneTimeDonation($amount)` - Creates PaymentIntent for single payments
- `createRecurringDonation($amount)` - Creates subscription with Checkout Session

**API Endpoints:**
```php
POST /donate_checkout
Response (One-time):
{
  "success": true,
  "client_secret": "pi_xxx_secret_xxx"
}

Response (Recurring):
{
  "success": true,
  "checkout_url": "https://checkout.stripe.com/..."
}
```

## Payment Flow

### One-Time Donation Flow
1. User enters amount and clicks DONATE (with toggle OFF)
2. Modal opens with donation summary
3. User enters card details in Stripe Element
4. User clicks "Complete Donation"
5. Frontend calls `/donate_checkout` to create PaymentIntent
6. Frontend confirms payment with Stripe.js
7. On success, redirect to `/educators/donated?payment_id=xxx`

### Recurring Donation Flow
1. User enters amount and clicks DONATE (with toggle ON)
2. Modal opens with donation summary
3. User clicks "Complete Donation"
4. Frontend calls `/donate_checkout` to create Checkout Session
5. User redirected to Stripe Checkout page
6. After payment, Stripe redirects to `/educators/donated?session_id=xxx`

## Configuration Requirements

### Environment Variables
Ensure these are set in `.env`:
```bash
STRIPE_KEY=pk_live_xxx  # or pk_test_xxx for testing
STRIPE_SECRET=sk_live_xxx  # or sk_test_xxx for testing
```

### Stripe Dashboard Setup
1. **Products**: Donation product is created automatically on first recurring donation
2. **Webhooks**: May need webhook endpoint for subscription events (optional for basic functionality)
3. **Checkout Settings**: Ensure success/cancel URLs are whitelisted

## Testing Guide

### Test One-Time Donation
1. Navigate to any page with the donation widget (top-right corner)
2. Leave toggle as "ONCE" (unchecked)
3. Enter amount (e.g., $25)
4. Click DONATE
5. Modal should open showing "One-time | $25.00"
6. Enter Stripe test card: `4242 4242 4242 4242`
7. Use any future expiry, any CVC, any ZIP
8. Click "Complete Donation"
9. Should redirect to success page

### Test Monthly Recurring Donation
1. Navigate to donation widget
2. Toggle to "MONTHLY" (checked)
3. Enter amount (e.g., $50)
4. Click DONATE
5. Modal should open showing "Monthly Recurring | $50.00"
6. Click "Complete Donation" (no card entry yet)
7. Redirected to Stripe Checkout page
8. Enter test card details on Stripe page
9. Complete payment
10. Redirected to success page

### Stripe Test Cards
```
Success: 4242 4242 4242 4242
Declined: 4000 0000 0000 0002
Requires authentication: 4000 0025 0000 3155
```

## Known Considerations

### 1. **Recurring Donation Pricing**
Currently, a new Stripe Price is created for each unique donation amount. This is fine but may clutter the Stripe Dashboard over time. Consider:
- Creating preset donation tiers ($10, $25, $50, $100)
- Implementing price caching/reuse logic

### 2. **Authentication**
Donations work for both authenticated and guest users:
- Authenticated users: Receipt email sent to user's email
- Guest users: No receipt (could add email field to form)

### 3. **Subscription Management**
Users with recurring donations will need a way to:
- View active subscriptions
- Cancel subscriptions
- Update payment methods

Consider implementing a donor portal using Stripe Billing Portal.

### 4. **Error Handling**
Current implementation handles:
- Invalid amounts
- Payment failures
- Network errors
- Card declines

Errors are displayed inline in the modal.

### 5. **Success Tracking**
The success page shows a generic thank you. Consider:
- Saving donation records to database
- Sending confirmation emails
- Tracking donation analytics
- Displaying donation amount on success page

## Future Enhancements

1. **Donation History**
   - Save donations to database
   - Show user their donation history
   - Generate tax receipts

2. **Preset Amounts**
   - Add quick-select buttons ($10, $25, $50, $100)
   - Most popular amount highlighting

3. **Impact Messaging**
   - Show what donation amounts fund
   - Display total donations raised
   - Real-time donation counter

4. **Email Receipts**
   - Custom branded receipts
   - Tax-deductible acknowledgment
   - Recurring donation reminders

5. **Payment Methods**
   - Add ACH/bank transfers
   - Add Apple Pay/Google Pay
   - International payment methods

## Troubleshooting

### Modal doesn't appear
- Check JavaScript console for errors
- Ensure jQuery and Bootstrap are loaded
- Verify Stripe publishable key is set

### Payment fails immediately
- Check Stripe API keys are correct
- Verify using test keys in development
- Check browser console for API errors

### Stripe Element doesn't load
- Verify Stripe.js CDN is accessible
- Check publishable key format
- Ensure elements.create() is called

### Recurring donation doesn't redirect
- Check Stripe secret key permissions
- Verify product creation succeeds
- Check network tab for API errors

## Support Resources
- [Stripe Elements Documentation](https://stripe.com/docs/payments/elements)
- [Stripe Payment Intents](https://stripe.com/docs/payments/payment-intents)
- [Stripe Checkout](https://stripe.com/docs/payments/checkout)
- [Laravel Cashier](https://laravel.com/docs/billing)
