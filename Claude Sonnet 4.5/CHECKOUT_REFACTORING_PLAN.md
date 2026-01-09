# Checkout Flow Refactoring Plan

## Problem Statement
Current implementation conflicts between custom address selection and Stripe embedded checkout with automatic tax calculation.

## Stripe Integration Strategy

### **Best Practice for Laravel + Stripe**
The optimal way to integrate Stripe in a Laravel application for a seamless, embedded, and custom-coded checkout experience is using:

1. **Stripe PHP SDK** - Backend payment processing (already using Laravel Cashier)
2. **Stripe Elements** - Secure, PCI-compliant UI components for payment forms
3. **Payment Intents API** - Flexible payment flow with confirmation
4. **Stripe Embedded Checkout** - Pre-built checkout experience (current approach)

### **Two Implementation Paths**

#### **Path A: Stripe Embedded Checkout (Current - Recommended for Speed)**
- Pre-built checkout UI embedded in your site
- Stripe handles address collection, shipping selection, tax calculation
- Less custom code, faster implementation
- Good for standard checkout flows
- **Current choice** - already implemented

#### **Path B: Custom Checkout with Elements + Payment Intents (Maximum Control)**
- Build custom checkout UI with Stripe Elements
- Full control over address collection and form layout
- Calculate tax yourself using Stripe Tax API
- More development work, maximum customization
- Better for complex business logic or unique UX requirements

**For this project, we'll continue with Path A (Embedded Checkout)** but refactor it properly to work with automatic tax.

## Stripe Requirements for Automatic Tax
1. **Address Collection**: Stripe must collect shipping address via `shipping_address_collection` parameter
2. **Shipping Rates**: Pre-calculate shipping rates and pass as `shipping_options` array
3. **Customer Update**: Don't use `customer_update[shipping]` - Stripe handles this automatically when collecting addresses

## Proposed Solution: Streamlined Stripe-Native Approach

### Flow Overview
```
Cart → Review Order (show summary) → Stripe Embedded Checkout (collects everything)
```

### Architecture

#### **Step 1: Review Order Page** (`/cart/review`)
**Purpose**: Show cart summary and collect any custom data before Stripe checkout

**Features**:
- Display cart items with quantities and prices
- Show if user has saved addresses (informational only)
- Optional: Allow selecting default billing address to prefill Stripe
- "Proceed to Checkout" button → creates Stripe session

**Data Collected**:
- None (all collection happens in Stripe)
- Optional: User can select existing address to prefill Stripe customer shipping

#### **Step 2: Stripe Embedded Checkout** (`/checkout`)
**Purpose**: Let Stripe handle address collection, shipping selection, tax calculation, and payment

**Stripe Session Configuration**:
```php
$sessionParams = [
    'ui_mode' => 'embedded',
    'customer' => $user->stripe_id, // Prefills saved data
    'line_items' => $cartLineItems,
    'mode' => 'payment',
    
    // Automatic tax
    'automatic_tax' => ['enabled' => true],
    
    // Let Stripe collect shipping address
    'shipping_address_collection' => [
        'allowed_countries' => ['US', 'CA'],
    ],
    
    // Pre-calculated shipping options (Stripe displays selector)
    'shipping_options' => [
        [
            'shipping_rate_data' => [
                'display_name' => 'USPS Ground Advantage',
                'type' => 'fixed_amount',
                'fixed_amount' => ['amount' => 500, 'currency' => 'usd'],
                'delivery_estimate' => [
                    'minimum' => ['unit' => 'business_day', 'value' => 2],
                    'maximum' => ['unit' => 'business_day', 'value' => 5],
                ],
            ],
        ],
        [
            'shipping_rate_data' => [
                'display_name' => 'USPS Priority Mail',
                'type' => 'fixed_amount',
                'fixed_amount' => ['amount' => 1000, 'currency' => 'usd'],
                'delivery_estimate' => [
                    'minimum' => ['unit' => 'business_day', 'value' => 1],
                    'maximum' => ['unit' => 'business_day', 'value' => 3],
                ],
            ],
        ],
    ],
    
    // Billing address for tax calculation
    'billing_address_collection' => 'required',
    
    // Save addresses back to customer (no need for customer_update)
    'customer_update' => [
        'address' => 'auto',
        'shipping' => 'auto', // Only if shipping_address_collection is enabled
    ],
];
```

**What Stripe Handles**:
- ✅ Collects shipping address
- ✅ Displays shipping rate options
- ✅ Calculates tax based on shipping address
- ✅ Collects billing address
- ✅ Processes payment
- ✅ Saves addresses to Stripe customer

**What We Handle**:
- Calculate available shipping rates (before creating session)
- Pass shipping options to Stripe
- Process webhook after payment success
- Save shipping info to our database

### Implementation Steps

#### **1. Create Review Order Page**
**File**: `resources/views/store/cart/review.blade.php`

Features:
- Cart items summary
- Subtotal display
- Optional: Show saved addresses (read-only, for user reference)
- "Proceed to Checkout" button → AJAX call to create Stripe session

#### **2. Modify CheckoutController**
**File**: `app/Http/Controllers/Store/CheckoutController.php`

**Method**: `checkout(Request $request, $path)`
- Remove address selection logic
- Calculate shipping options based on cart contents
- Pass shipping options to Stripe session
- Use `shipping_address_collection` instead of custom address page

**Shipping Options Logic**:
```php
// For physical items, provide shipping options
if (!$usps->isDigitalOnly($cart)) {
    // Get fallback rates (or USPS API rates when available)
    $shippingOptions = [
        [
            'shipping_rate_data' => [
                'display_name' => 'USPS Ground Advantage',
                'type' => 'fixed_amount',
                'fixed_amount' => ['amount' => 500, 'currency' => 'usd'],
                'delivery_estimate' => [
                    'minimum' => ['unit' => 'business_day', 'value' => 2],
                    'maximum' => ['unit' => 'business_day', 'value' => 5],
                ],
            ],
        ],
        [
            'shipping_rate_data' => [
                'display_name' => 'USPS Priority Mail',
                'type' => 'fixed_amount',
                'fixed_amount' => ['amount' => 1000, 'currency' => 'usd'],
                'delivery_estimate' => [
                    'minimum' => ['unit' => 'business_day', 'value' => 1],
                    'maximum' => ['unit' => 'business_day', 'value' => 3],
                ],
            ],
        ],
    ];
    
    $sessionParams['shipping_options'] = $shippingOptions;
    $sessionParams['shipping_address_collection'] = [
        'allowed_countries' => ['US', 'CA'],
    ];
}
```

#### **3. Update Routes**
**File**: `routes/web.php`

```php
// Cart routes
Route::get('/cart', [CartProductController::class, 'index'])->name('cart');
Route::post('/cart/update', [CartProductController::class, 'updateCart']);

// Remove: /cart/address route (no longer needed)

// Checkout
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
```

#### **4. Update Success Handler**
**File**: `app/Http/Controllers/Store/CheckoutController.php`

**Method**: `success(Request $request, $path)`
- Retrieve Stripe session
- Get shipping details from `session->shipping_details`
- Get shipping cost from `session->shipping_cost`
- Save to our database (for record-keeping and fulfillment)

```php
$session = $stripe->checkout->sessions->retrieve($sessionId, [
    'expand' => ['line_items', 'shipping_cost', 'total_details']
]);

// Save shipping info to our database
if ($session->shipping_details) {
    $shippingAddress = ShippingAddress::firstOrCreate([
        'user_id' => $user->id,
        'name' => $session->shipping_details->name,
        'address' => $session->shipping_details->address->line1,
        'address2' => $session->shipping_details->address->line2,
        'city' => $session->shipping_details->address->city,
        'state' => $session->shipping_details->address->state,
        'zip' => $session->shipping_details->address->postal_code,
        'country' => $session->shipping_details->address->country,
    ]);
}
```

### Benefits of This Approach

✅ **No More Conflicts**: Stripe handles address collection, so automatic tax works correctly
✅ **Simpler Code**: Remove custom address selection page and shipping rate AJAX
✅ **Better UX**: Everything in one embedded form (address, shipping, payment)
✅ **Tax Accuracy**: Stripe calculates tax based on actual entered address
✅ **Future-Proof**: When USPS API works, we just update shipping_options data source
✅ **Saves Development Time**: Leverage Stripe's built-in UI instead of custom forms

### Migration Checklist

- [ ] Create cart review page (optional, can go straight to checkout)
- [ ] Modify CheckoutController to use shipping_options
- [ ] Remove address selection page and routes
- [ ] Update success handler to retrieve shipping from Stripe
- [ ] Test with digital-only cart (no shipping)
- [ ] Test with physical items (shipping options appear)
- [ ] Test with mixed cart
- [ ] Verify tax calculation accuracy
- [ ] Update navigation/breadcrumbs

### Alternative: Keep Custom Address Page (Advanced)

If you **really** want custom address selection, you need to:

1. **Disable automatic tax** (set `STRIPE_CALCULATE_TAX=false`)
2. Calculate tax yourself using our address
3. Add tax as a line item in Stripe session
4. Don't use `shipping_address_collection` (Stripe won't collect address)
5. Pass shipping cost as a line item

**This is NOT recommended** because:
- More complex code
- Manual tax calculations (error-prone)
- Lose Stripe Tax benefits (automatic rate updates, compliance)

### Recommendation

**Use the Stripe-native approach** (shipping_options + shipping_address_collection) for:
- Simplicity
- Accuracy
- Compliance
- Less maintenance

The only downside is less control over address UI, but Stripe's embedded checkout is well-designed and mobile-responsive.

### Future Consideration: Custom Checkout with Elements

If you later need more control (custom address validation, branded UI, complex shipping logic), you can migrate to:

**Custom Implementation with Stripe Elements + Payment Intents**

**Architecture**:
```
1. Custom Address Form (your HTML/CSS) → Collect & validate address
2. Calculate Tax (Stripe Tax API) → POST /v1/tax/calculations
3. Calculate Shipping (USPS API) → Display options
4. Stripe Elements (card input) → Secure PCI-compliant card collection
5. Payment Intent (backend) → Create payment intent with calculated amounts
6. Confirm Payment (frontend) → stripe.confirmPayment() with Elements
```

**Benefits**:
- Complete UI control (match your brand perfectly)
- Custom address validation (USPS, custom rules)
- Dynamic shipping calculation (per address change)
- Flexible payment flow (subscriptions, installments, etc.)

**Tradeoffs**:
- 3-5x more development time
- More code to maintain
- Tax calculation complexity (must handle edge cases)
- PCI compliance considerations (still handled by Elements)

**Implementation Complexity**: High (2-3 weeks vs 2-3 days for embedded)

For now, **embedded checkout is the right choice** to get functionality working quickly. The custom approach can be considered once core business operations are stable and you have specific customization requirements that embedded checkout can't meet.
