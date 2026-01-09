# UNHUSHED Payment System Migration Plan
## PayPal → Stripe with Laravel Cashier

**Date:** December 8, 2024  
**Target:** Laravel 12, PHP 8.2+, Stripe API 2025-06-30

---

## Table of Contents
1. [Executive Summary](#executive-summary)
2. [Current System Analysis](#current-system-analysis)
3. [Target Architecture](#target-architecture)
4. [Migration Strategy](#migration-strategy)
5. [Implementation Phases](#implementation-phases)
6. [Database Schema Changes](#database-schema-changes)
7. [Code Refactoring Matrix](#code-refactoring-matrix)
8. [Product Category Handling](#product-category-handling)
9. [Testing Strategy](#testing-strategy)
10. [Rollback Plan](#rollback-plan)

---

## Executive Summary

### Current State
- PayPal Checkout SDK for all payment processing
- Dual cart system: `Cart` (one-time products) + `Orders` (subscriptions/trainings)
- Manual subscription management via PayPal Products/Plans API
- Legacy `swag_sizes` table still referenced but no longer used
- Category 7 products incorrectly processed as subscriptions

### Target State
- Stripe with Laravel Cashier 16 for all payment processing
- Unified cart/checkout flow using Stripe Checkout
- Category 1 products: **Subscriptions** with first-year pricing + renewals
- Category 7 products: **One-time purchases** (not subscriptions)
- Category 5 products: **Standard products** (remove `swag_sizes` dependencies)
- All other categories: **One-time purchases** through unified product checkout

### Key Benefits
- Unified payment flow reduces complexity
- Stripe Tax for automatic tax calculation
- Built-in SCA compliance
- Better analytics and reporting
- Hosted checkout pages reduce PCI scope
- Webhook-driven fulfillment automation

---

## Current System Analysis

### Database Tables
```
Current Payment Tables:
├── carts (one-time product purchases)
│   ├── id, user_id, completed (0=open, 1=ordered, 2=finished, 3=canceled)
│   ├── total, tax, shipping
│   └── pp_id (PayPal order ID)
├── cart_items
│   ├── cart_id, item_id, item_type, qty, cost
│   └── TYPE constants (0-9 for different product types)
├── orders (subscription/training purchases)
│   ├── id, user_id, status, total
│   └── STATUS constants
├── order_items
│   ├── order_id, item_id, item_type, qty, price
│   └── ITEM_TYPE_SUBSCRIPTION, ITEM_TYPE_TRAINING
├── subscriptions (PayPal subscription tracking)
│   ├── subscription_id (PayPal ID)
│   ├── pp_product_id, order_id
│   ├── active, exp_date, due
│   └── user_id
├── active_subscriptions (user access tracking)
│   └── Links users to subscribed products
└── paypal_* tables
    ├── paypal_products
    ├── paypal_plans
    └── paypal_product_assoc
```

### Current Flow

#### **One-Time Products (Cart System)**
1. User adds product → `CartItem` created with `item_type`
2. Checkout → `CartProductController::checkout()`
3. Address collection (if physical items)
4. Forward to PayPal → `CartProductController::fwd_paypal()`
5. Create PayPal order with all items
6. Redirect to PayPal Checkout
7. Return URL → webhook processing
8. Mark cart as completed

#### **Subscriptions (Order System)**
1. User selects curriculum → `CartSubscriptController::view_cart()`
2. Create `Order` with `OrderItems`
3. Login required
4. `CartSubscriptController::process_order()`:
   - Create PayPal Product
   - Create PayPal Plan with setup fee + recurring
   - Create PayPal Subscription
   - Store in `subscriptions` table
5. Redirect to PayPal approval
6. Webhook confirms → activate access

#### **Trainings (Currently Broken - Uses Order System)**
Category 7 products go through subscription flow but shouldn't be recurring.

### Product Categories
```php
0 = Unknown/Store general
1 = Curriculum (SUBSCRIPTIONS - yearly recurring)
2 = Activities (one-time)
3 = Books (one-time)
4 = Games (one-time)
5 = Swag (one-time, used to have swag_sizes table)
6 = Toolkits (one-time)
7 = Trainings (one-time, NOT subscriptions)
```

### Legacy Issues to Address
1. **`swag_sizes` table** - No longer used, remove all references
2. **Dual cart system** - Confusing UX, inconsistent flows
3. **Category 7 trainings** - Incorrectly treated as subscriptions
4. **PayPal-specific logic** - Scattered across controllers
5. **Manual webhook handling** - Complex and error-prone
6. **No automated tax calculation** - Manual tax rates

---

## Target Architecture

### Stripe Cashier Integration

#### **User Model Enhancement**
```php
// app/Models/User.php
use Laravel\Cashier\Billable;

class User extends Authenticatable {
    use Billable;
    
    // Cashier adds:
    // - stripe_id (customer ID)
    // - pm_type, pm_last_four (default payment method)
    // - trial_ends_at (generic trials)
}
```

#### **New Payment Flow**

##### **Category 1: Curriculum Subscriptions**
```php
// First-year pricing from curriculum_prices table
$pricing = CurriculumPrice::where('product_id', $productId)
    ->where('min_users', '<=', $qty)
    ->where('max_users', '>=', $qty)
    ->first();

// Create Stripe subscription with custom billing cycle
$user->newSubscription('curriculum', $stripePriceId)
    ->trialDays(0)
    ->quantity($qty)
    ->checkout([
        'success_url' => route('subscription-success'),
        'cancel_url' => route('cart'),
        'metadata' => [
            'product_id' => $productId,
            'first_year_price' => $pricing->discount_price,
            'renewal_price' => $pricing->recurring_price,
            'qty' => $qty
        ]
    ]);
```

**Key Implementation:**
- Create Stripe Prices for each curriculum tier (min/max users)
- Use `setup_fee` for first-year pricing
- Use `recurring` price for renewal year
- Store `product_id` in subscription metadata
- Grant access via webhook when subscription activates

##### **Category 7: Training (One-Time)**
```php
// Single checkout session
$checkout = $user->checkout([$stripePriceId => $qty], [
    'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => route('cart'),
    'metadata' => [
        'product_id' => $productId,
        'category' => 7,
        'participants' => $qty
    ]
]);
```

**Key Implementation:**
- Create Stripe Product/Price for each training
- Process as single charge (not subscription)
- Grant access via webhook completion
- Store purchase record in `purchases` table

##### **All Other Products (Unified Checkout)**
```php
// Build cart with multiple products
$lineItems = [];
foreach($cart->items as $item) {
    $variant = ProductVar::find($item->var_id);
    $lineItems[$variant->stripe_price_id] = $item->qty;
}

// Single Stripe Checkout session
$checkout = $user->checkout($lineItems, [
    'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => route('cart'),
    'allow_promotion_codes' => true,
    'shipping_address_collection' => [
        'allowed_countries' => ['US', 'CA']
    ],
    'metadata' => [
        'cart_id' => $cart->id
    ]
]);
```

**Key Implementation:**
- Each product variant gets Stripe Price ID
- Support multiple items in single checkout
- Stripe handles shipping address collection
- Stripe Tax calculates taxes automatically
- Webhook processes fulfillment

### Database Schema Changes

#### **Migration 1: Install Cashier Tables**
```bash
php artisan vendor:publish --tag="cashier-migrations"
php artisan migrate
```

Creates:
- `subscriptions` (Cashier's subscription management)
- `subscription_items` (for multi-product subscriptions)
- Adds columns to `users`: `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`

#### **Migration 2: Add Stripe IDs to Existing Tables**
```php
Schema::table('products', function (Blueprint $table) {
    $table->string('stripe_product_id')->nullable()->after('price');
});

Schema::table('product_vars', function (Blueprint $table) {
    $table->string('stripe_price_id')->nullable()->after('price');
});

Schema::table('curriculum_prices', function (Blueprint $table) {
    $table->string('stripe_price_id')->nullable()->after('recurring_price');
});
```

#### **Migration 3: Unified Purchase Records**
```php
// Replace dual system with single purchases table
Schema::create('purchases', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('product_id');
    $table->unsignedBigInteger('variant_id')->nullable();
    $table->string('stripe_checkout_session_id')->unique();
    $table->string('stripe_payment_intent_id')->nullable();
    $table->enum('type', ['product', 'training', 'subscription']); // Category-based
    $table->integer('quantity')->default(1);
    $table->decimal('amount', 10, 2); // Total paid
    $table->decimal('tax', 10, 2)->default(0);
    $table->decimal('shipping', 10, 2)->default(0);
    $table->string('status'); // pending, completed, refunded
    $table->json('metadata')->nullable(); // Store product details snapshot
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users');
    $table->foreign('product_id')->references('id')->on('products');
    $table->index(['user_id', 'status']);
    $table->index('stripe_checkout_session_id');
});
```

#### **Migration 4: Drop PayPal Tables**
```php
Schema::dropIfExists('paypal_products');
Schema::dropIfExists('paypal_plans');
Schema::dropIfExists('paypal_product_assoc');
// Keep old subscriptions/orders for historical data
Schema::rename('subscriptions', 'paypal_subscriptions_archive');
Schema::rename('orders', 'paypal_orders_archive');
```

#### **Migration 5: Remove swag_sizes References**
```php
// Drop the unused swag_sizes table
Schema::dropIfExists('swag_sizes');

// Remove SwagSize model: app/Models/SwagSize.php
// Update SwagController to remove swag_sizes methods
// Remove routes in web.php: /swag/{id}/sizes, /swag/sizes
```

---

## Migration Strategy

### Phase 1: Preparation (Week 1)
**Goal:** Set up Stripe infrastructure without touching production

#### Tasks:
1. **Install Laravel Cashier**
   ```bash
   composer require laravel/cashier
   php artisan vendor:publish --tag="cashier-migrations"
   php artisan migrate
   ```

2. **Configure Stripe**
   ```env
   # .env
   STRIPE_KEY=pk_test_...
   STRIPE_SECRET=sk_test_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   CASHIER_CURRENCY=usd
   CASHIER_CURRENCY_LOCALE=en_US
   ```

3. **Update User Model**
   ```php
   use Laravel\Cashier\Billable;
   class User extends Authenticatable {
       use Billable;
   }
   ```

4. **Stripe Product/Price Setup**
   - Create Stripe Products for all existing products
   - Create Prices for each product variant
   - Special handling for curriculum tiers:
     - Create Price with `recurring` interval
     - Use `billing_cycle_anchor` for annual billing
   - Store `stripe_product_id` and `stripe_price_id` in database

5. **Create Stripe Setup Script**
   ```php
   // app/Console/Commands/SyncProductsToStripe.php
   php artisan stripe:sync-products
   ```

### Phase 2: New Controllers (Week 2)
**Goal:** Build new payment flow without disrupting existing

#### Create New Controllers:
1. **`CheckoutController`** - Unified checkout for all products
2. **`SubscriptionController`** - Curriculum subscription management
3. **`WebhookController`** - Stripe webhook handling (or use Cashier's built-in)

#### Example: Unified Checkout Controller
```php
namespace App\Http\Controllers\Store;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Products;
use App\Models\ProductVar;
use Illuminate\Http\Request;
use Laravel\Cashier\Checkout;

class CheckoutController extends Controller
{
    public function initiateCheckout(Request $request, $path = 'educators')
    {
        $cart = Cart::find(session('cart_id'));
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Cart is empty');
        }
        
        // Separate subscriptions from one-time purchases
        $subscriptionItems = [];
        $oneTimeItems = [];
        
        foreach ($cart->items as $item) {
            $product = Products::find($item->item_id);
            
            if ($product->category == 1) {
                // Curriculum subscription
                $subscriptionItems[] = $item;
            } else {
                // One-time purchase
                $variant = ProductVar::where('product_id', $product->id)
                    ->where('var_id', $item->var_id ?? 1)
                    ->first();
                    
                if ($variant && $variant->stripe_price_id) {
                    $oneTimeItems[$variant->stripe_price_id] = $item->qty;
                }
            }
        }
        
        // Handle subscriptions separately
        if (!empty($subscriptionItems)) {
            return $this->createSubscriptionCheckout($subscriptionItems[0], $path);
        }
        
        // Create one-time checkout
        return $this->createOneTimeCheckout($oneTimeItems, $cart, $path);
    }
    
    private function createOneTimeCheckout($lineItems, $cart, $path)
    {
        // Determine if physical shipping needed
        $needsShipping = !$cart->only_digital();
        
        $checkoutOptions = [
            'success_url' => route('checkout.success', ['path' => $path]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cart.view', ['path' => $path]),
            'allow_promotion_codes' => true,
            'metadata' => [
                'cart_id' => $cart->id,
                'type' => 'one_time'
            ]
        ];
        
        if ($needsShipping) {
            $checkoutOptions['shipping_address_collection'] = [
                'allowed_countries' => ['US', 'CA']
            ];
        }
        
        if (auth()->check()) {
            // Authenticated user
            return auth()->user()->checkout($lineItems, $checkoutOptions);
        } else {
            // Guest checkout
            return Checkout::guest()->create($lineItems, $checkoutOptions);
        }
    }
    
    private function createSubscriptionCheckout($item, $path)
    {
        $product = Products::find($item->item_id);
        $qty = $item->qty;
        
        // Get appropriate pricing tier
        $pricing = \App\Models\CurriculumPrice::where('product_id', $product->id)
            ->where('min_users', '<=', $qty)
            ->where('max_users', '>=', $qty)
            ->first();
        
        if (!$pricing || !$pricing->stripe_price_id) {
            return redirect()->back()->with('error', 'Pricing not configured for this quantity');
        }
        
        // Create subscription checkout
        return auth()->user()
            ->newSubscription('curriculum_' . $product->id, $pricing->stripe_price_id)
            ->quantity($qty)
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('subscription.success', ['path' => $path]),
                'cancel_url' => route('cart.view', ['path' => $path]),
                'metadata' => [
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'type' => 'subscription'
                ]
            ]);
    }
    
    public function success(Request $request, $path = 'educators')
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('cart.view', ['path' => $path]);
        }
        
        // Retrieve session from Stripe
        $session = auth()->user()->stripe()->checkout->sessions->retrieve($sessionId);
        
        if ($session->payment_status !== 'paid') {
            return redirect()->route('cart.view', ['path' => $path])
                ->with('error', 'Payment not completed');
        }
        
        // Clear cart
        $cartId = $session->metadata->cart_id ?? null;
        if ($cartId) {
            $cart = Cart::find($cartId);
            if ($cart) {
                $cart->completed = Cart::CART_COMPLETE;
                $cart->save();
            }
        }
        
        return view('store.checkout.success')
            ->with('session', $session)
            ->with('path', $path);
    }
}
```

### Phase 3: Webhook Implementation (Week 2)
**Goal:** Automate fulfillment and access provisioning

#### Webhook Events to Handle:

1. **`checkout.session.completed`** - One-time purchases
   ```php
   // Handle product delivery, send confirmations
   $session = $event->data->object;
   $cartId = $session->metadata->cart_id;
   
   // Create Purchase record
   $purchase = new Purchase([
       'user_id' => User::where('stripe_id', $session->customer)->first()->id,
       'stripe_checkout_session_id' => $session->id,
       'stripe_payment_intent_id' => $session->payment_intent,
       'type' => 'product',
       'amount' => $session->amount_total / 100,
       'status' => 'completed',
       'metadata' => json_encode($session->metadata),
       'completed_at' => now()
   ]);
   $purchase->save();
   
   // Process line items for access provisioning
   foreach ($session->line_items as $item) {
       // Grant access to digital products
       // Queue shipping for physical products
   }
   ```

2. **`customer.subscription.created`** - Curriculum subscriptions
   ```php
   // Subscription created, grant access
   $subscription = $event->data->object;
   $user = User::where('stripe_id', $subscription->customer)->first();
   
   // Grant curriculum access
   $productId = $subscription->metadata->product_id;
   $qty = $subscription->metadata->qty;
   
   ActiveSubscriptions::create([
       'user_id' => $user->id,
       'product_id' => $productId,
       'quantity' => $qty,
       'stripe_subscription_id' => $subscription->id,
       'status' => 'active'
   ]);
   ```

3. **`customer.subscription.updated`** - Renewal/changes
4. **`customer.subscription.deleted`** - Cancellation
5. **`invoice.payment_succeeded`** - Successful renewal
6. **`invoice.payment_failed`** - Failed renewal

#### Cashier Webhook Setup:
```bash
# Create webhook in Stripe dashboard pointing to:
# https://yourdomain.com/stripe/webhook

# Or use Artisan command:
php artisan cashier:webhook --url="https://yourdomain.com/stripe/webhook"
```

### Phase 4: Frontend Updates (Week 3)
**Goal:** Update user-facing pages to use new flow

#### Pages to Update:
1. **Cart page** (`resources/views/store/cart/cart_products.blade.php`)
   - Change "Checkout" button to call new controller
   - Remove PayPal-specific messaging

2. **Subscription cart** (`resources/views/store/cart/cart_subscriptions.blade.php`)
   - Merge into single cart view
   - Update button to use Stripe Checkout

3. **Price/product pages** (`resources/views/store/product/*.blade.php`)
   - Add "Add to Cart" → unified flow
   - Remove old subscription button

4. **Success pages**
   - Create new `resources/views/store/checkout/success.blade.php`
   - Show order summary, next steps

5. **Account subscription management**
   - Add link to Stripe Billing Portal: `$user->redirectToBillingPortal()`
   - Show active subscriptions from Cashier

### Phase 5: Remove Legacy Code (Week 4)
**Goal:** Clean up old PayPal code

#### Files to Remove/Refactor:
1. **Controllers:**
   - `CartProductController::fwd_paypal()` - Remove
   - `CartSubscriptController::process_order()` - Remove
   - `CartSubscriptController::list_subscription()` - Remove
   - All PayPal API instantiation code

2. **Models:**
   - Drop `PayPalProducts`, `PayPalPlans`, `PayPalProductAssoc`
   - Remove `SwagSize` model
   - Archive old `Subscriptions` model

3. **Routes:**
   - Remove PayPal return/cancel routes
   - Remove swag_sizes routes
   - Add new Stripe routes

4. **Views:**
   - Remove PayPal button references
   - Remove swag_sizes management views

5. **Config:**
   - Remove `config/paypal.php`
   - Keep only Cashier config

### Phase 6: Testing (Week 4)
**Goal:** Comprehensive testing before production

#### Test Scenarios:
1. **Product Purchases:**
   - Single product checkout
   - Multiple products in cart
   - Digital-only cart (no shipping)
   - Mixed digital/physical cart
   - Guest checkout
   - Authenticated checkout
   - Promotion codes
   - Tax calculation

2. **Curriculum Subscriptions:**
   - New subscription signup
   - Different quantity tiers
   - Trial period handling
   - First year vs renewal pricing
   - Access provisioning
   - Subscription management via Billing Portal
   - Cancellation and grace periods
   - Failed payment handling

3. **Training Purchases:**
   - Single training purchase
   - Multiple participants
   - Access provisioning
   - NOT creating subscription

4. **Edge Cases:**
   - Empty cart
   - Deleted products
   - Out of stock
   - Invalid pricing tier
   - Webhook delivery failures
   - Payment failures
   - Network timeouts

5. **Migration Testing:**
   - Existing PayPal subscribers continue to work
   - Historical order data preserved
   - User accounts with Stripe IDs created

#### Stripe Test Cards:
```
Success: 4242 4242 4242 4242
Declined: 4000 0000 0000 0002
3D Secure: 4000 0025 0000 3155
```

### Phase 7: Production Deployment (Week 5)
**Goal:** Safe production rollout

#### Pre-Deployment:
1. Backup database
2. Test webhook connectivity
3. Verify Stripe API keys (live mode)
4. Update Stripe webhook URL to production
5. Test checkout flow in Stripe test mode one final time

#### Deployment Steps:
1. Put site in maintenance mode: `php artisan down`
2. Pull latest code
3. Run migrations: `php artisan migrate`
4. Sync products to Stripe: `php artisan stripe:sync-products --live`
5. Clear caches: `php artisan config:clear; php artisan cache:clear`
6. Bring site back up: `php artisan up`
7. Monitor webhook dashboard in Stripe
8. Test single purchase and subscription

#### Post-Deployment:
1. Monitor first 24 hours closely
2. Check webhook success rates
3. Verify access provisioning
4. Test subscription renewals (if any occur)
5. Customer support ready for questions

---

## Code Refactoring Matrix

### Files to Create

| File | Purpose |
|------|---------|
| `app/Http/Controllers/Store/CheckoutController.php` | Unified checkout for all products |
| `app/Http/Controllers/Store/SubscriptionController.php` | Subscription management |
| `app/Models/Purchase.php` | Unified purchase records |
| `app/Console/Commands/SyncProductsToStripe.php` | Sync product catalog to Stripe |
| `database/migrations/*_add_stripe_ids.php` | Add Stripe ID columns |
| `database/migrations/*_create_purchases_table.php` | New purchase tracking |
| `resources/views/store/checkout/success.blade.php` | Checkout success page |

### Files to Modify

| File | Changes |
|------|---------|
| `app/Models/User.php` | Add `use Billable` trait |
| `config/services.php` | Remove PayPal config |
| `routes/web.php` | Replace PayPal routes with Stripe routes |
| `resources/views/store/cart/cart_products.blade.php` | Update checkout button |
| `resources/views/store/product/price.blade.php` | Update "Add to Cart" logic |
| `app/Http/Controllers/Store/StorefrontController.php` | Remove swag_sizes references |

### Files to Remove

| File | Reason |
|------|--------|
| `app/Models/PayPalProducts.php` | PayPal specific |
| `app/Models/PayPalPlans.php` | PayPal specific |
| `app/Models/PayPalProductAssoc.php` | PayPal specific |
| `app/Models/SwagSize.php` | No longer used |
| `app/Http/Controllers/Backend/SwagController.php` (partial) | Remove swag_sizes methods |
| `config/paypal.php` | PayPal config |
| `app/Http/PayPalObjects/*` | PayPal SDK wrappers |

---

## Product Category Handling

### Category 1: Curriculum (Subscriptions)

**Database:** `curriculum_prices` table
```
- product_id
- min_users / max_users (quantity tiers)
- standard_price (original price)
- discount_price (first year price)
- recurring_price (renewal price)
- package_caption (tier description)
- stripe_price_id (NEW)
```

**Stripe Setup:**
```bash
# For each pricing tier, create a Stripe Price with recurring billing
stripe prices create \
  --product prod_curriculum_xxx \
  --currency usd \
  --recurring[interval]=year \
  --unit_amount=2999 \
  --billing_scheme=tiered \
  --tiers[0][up_to]=25 \
  --tiers[0][unit_amount]=2999
```

**Implementation:**
```php
// User selects quantity → find tier
$pricing = CurriculumPrice::where('product_id', $productId)
    ->where('min_users', '<=', $qty)
    ->where('max_users', '>=', $qty)
    ->first();

// Create subscription with special first-year pricing
$user->newSubscription('curriculum_' . $productId, $pricing->stripe_price_id)
    ->quantity($qty)
    ->trialDays(0) // No trial, immediate billing
    ->checkout([
        'success_url' => route('subscription.success'),
        'metadata' => [
            'product_id' => $productId,
            'first_year_price' => $pricing->discount_price,
            'renewal_price' => $pricing->recurring_price
        ]
    ]);
```

**Access Provisioning:**
- Webhook `customer.subscription.created` → grant access
- Store in `active_subscriptions` table
- Check `$user->subscribed('curriculum_' . $productId)` for access

### Category 7: Trainings (One-Time Purchases)

**Current Problem:** Incorrectly routed through subscription flow

**Fix:** Route through unified product checkout

**Implementation:**
```php
// In CheckoutController
if ($product->category == 7) {
    // Training - one-time purchase with quantity = participants
    $variant = $product->vars()->first(); // Single variant
    
    $checkout = $user->checkout([$variant->stripe_price_id => $qty], [
        'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('cart'),
        'metadata' => [
            'product_id' => $product->id,
            'category' => 7,
            'participants' => $qty
        ]
    ]);
    
    return $checkout;
}
```

**Access Provisioning:**
- Webhook `checkout.session.completed` → grant access
- Store in `purchases` table with `type = 'training'`
- Create records in `active_subscriptions` or similar for access tracking
- NOT a recurring subscription

### Category 5: Swag (Remove swag_sizes)

**Current Legacy:**
- `swag_sizes` table with color/size combinations
- SwagController methods for size management

**New Approach:**
- Use product variants (`product_vars`) like all other products
- Color/size as option values in new product options system
- Remove all `swag_sizes` references

**Migration Steps:**
1. Verify no active dependencies on `swag_sizes`
2. Drop table: `Schema::dropIfExists('swag_sizes');`
3. Remove model: `app/Models/SwagSize.php`
4. Remove SwagController methods: `swag_sizes()`, `swag_sizes_update()`
5. Remove routes: `/swag/{id}/sizes`, `/swag/sizes`
6. Update any views referencing swag_sizes

### Categories 0, 2, 3, 4, 6: Standard Products

**Flow:** Unified checkout via Stripe Checkout

**Implementation:**
```php
// Build line items from cart
$lineItems = [];
foreach ($cart->items as $item) {
    $variant = ProductVar::find($item->var_id);
    if ($variant && $variant->stripe_price_id) {
        $lineItems[$variant->stripe_price_id] = $item->qty;
    }
}

// Create checkout
$user->checkout($lineItems, [
    'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => route('cart'),
    'allow_promotion_codes' => true,
    'shipping_address_collection' => ['allowed_countries' => ['US', 'CA']],
    'metadata' => ['cart_id' => $cart->id]
]);
```

**Access Provisioning:**
- Digital products: Grant immediate access via webhook
- Physical products: Queue for fulfillment
- Store in `purchases` table

---

## Testing Strategy

### Test Environment Setup
```bash
# Install Cashier
composer require laravel/cashier --dev

# Use Stripe test mode
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# Test webhook locally with Stripe CLI
stripe listen --forward-to localhost:8000/stripe/webhook
```

### Unit Tests
```php
// tests/Unit/CurriculumPricingTest.php
public function test_finds_correct_pricing_tier()
{
    $pricing = CurriculumPrice::getPricingForQuantity($productId, 15);
    $this->assertEquals(10, $pricing->min_users);
    $this->assertEquals(25, $pricing->max_users);
}

// tests/Unit/ProductCategoryTest.php
public function test_training_is_not_subscription()
{
    $product = Products::factory()->create(['category' => 7]);
    $this->assertFalse($product->isSubscription());
}
```

### Feature Tests
```php
// tests/Feature/CheckoutTest.php
public function test_can_checkout_single_product()
{
    $user = User::factory()->create();
    $product = Products::factory()->create(['category' => 3]);
    
    $cart = Cart::create(['user_id' => $user->id]);
    CartItem::create([
        'cart_id' => $cart->id,
        'item_id' => $product->id,
        'qty' => 1
    ]);
    
    $response = $this->actingAs($user)
        ->post(route('checkout'), ['cart_id' => $cart->id]);
    
    $response->assertStatus(303); // Redirect to Stripe
    $this->assertStringContainsString('checkout.stripe.com', $response->headers->get('Location'));
}

public function test_can_create_curriculum_subscription()
{
    $user = User::factory()->create();
    $product = Products::factory()->create(['category' => 1]);
    
    $response = $this->actingAs($user)
        ->post(route('subscription.checkout'), [
            'product_id' => $product->id,
            'quantity' => 20
        ]);
    
    $response->assertStatus(303);
    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user->id,
        'name' => 'curriculum_' . $product->id
    ]);
}
```

### Integration Tests (with Stripe Test Mode)
```php
// tests/Integration/StripeCheckoutTest.php
public function test_completes_checkout_flow()
{
    // Use Stripe test card
    $this->markTestSkipped('Requires Stripe test mode setup');
    
    // Create checkout
    // Simulate Stripe redirect
    // Mock webhook
    // Verify purchase record created
}
```

---

## Rollback Plan

### Immediate Rollback (Within 24 hours)
1. Revert code: `git revert HEAD`
2. Rollback migrations: `php artisan migrate:rollback --step=5`
3. Switch `.env` back to PayPal keys
4. Restart queue workers
5. Monitor for any stuck carts

### Partial Rollback (If Stripe has issues)
- Keep Cashier installed but disable new checkout routes
- Re-enable PayPal controllers temporarily
- Add feature flag: `ENABLE_STRIPE_CHECKOUT=false`
- Route checkout through old flow based on flag

### Data Preservation
- All PayPal tables renamed to `*_archive` not dropped
- Historical subscription data intact
- Cart/order data preserved
- User payment methods stored in Stripe can be exported

---

## Risk Assessment

### High Risk Items
1. **Active subscriptions during migration** - Handle renewals carefully
   - **Mitigation:** Complete migration between billing cycles
   - Export all active PayPal subscriptions
   - Communicate changes 30 days in advance

2. **Webhook delivery failures** - Orders may not complete
   - **Mitigation:** Implement webhook retry logic
   - Manual fulfillment queue for failed webhooks
   - Monitor Stripe webhook dashboard

3. **Tax calculation differences** - Stripe Tax vs manual
   - **Mitigation:** Test tax rates across states
   - Review first week of orders manually

### Medium Risk Items
1. **User confusion with new checkout flow**
   - **Mitigation:** Add help text, FAQ updates
   - Customer support training

2. **Integration with QuickBooks/ActiveCampaign**
   - **Mitigation:** Update integrations to use Stripe webhooks
   - Test contact sync thoroughly

### Low Risk Items
1. **Performance differences** - Stripe API speed
2. **Currency rounding** - Pennies vs dollars
3. **Email notification timing** - Webhook vs immediate

---

## Success Metrics

### Week 1 Post-Launch
- [ ] Zero failed checkouts
- [ ] 100% webhook delivery success rate
- [ ] All purchases have Purchase records
- [ ] No customer support escalations

### Month 1 Post-Launch
- [ ] 95%+ checkout completion rate (vs abandoned carts)
- [ ] All subscription renewals successful
- [ ] Zero data integrity issues
- [ ] QuickBooks sync working correctly

### Ongoing
- [ ] PayPal costs eliminated
- [ ] Reduced checkout time by 50%
- [ ] Increased conversion rate (Stripe Checkout UX)
- [ ] Automated tax compliance

---

## Next Steps

1. **Approve this plan** - Review with team
2. **Create Stripe account** - Production + Test mode
3. **Set up development environment** - Install Cashier on dev
4. **Create product catalog in Stripe** - All products + pricing
5. **Begin Phase 1 implementation** - Target start date: __________

---

## Appendix A: Stripe Price ID Mapping

### Products Table Schema
```sql
ALTER TABLE products 
ADD COLUMN stripe_product_id VARCHAR(255) NULL,
ADD COLUMN stripe_price_id VARCHAR(255) NULL COMMENT 'For single-variant products';
```

### Product Variants Table Schema
```sql
ALTER TABLE product_vars
ADD COLUMN stripe_price_id VARCHAR(255) NULL;
```

### Curriculum Prices Table Schema
```sql
ALTER TABLE curriculum_prices
ADD COLUMN stripe_price_id VARCHAR(255) NULL;
```

### Sync Command Example
```php
// app/Console/Commands/SyncProductsToStripe.php
public function handle()
{
    $products = Products::all();
    
    foreach ($products as $product) {
        // Create Stripe Product
        $stripeProduct = $this->stripe->products->create([
            'name' => $product->name,
            'description' => $product->description,
            'metadata' => [
                'unhushed_product_id' => $product->id,
                'category' => $product->category
            ]
        ]);
        
        $product->stripe_product_id = $stripeProduct->id;
        $product->save();
        
        // Create Prices for variants
        foreach ($product->vars as $variant) {
            $stripePrice = $this->stripe->prices->create([
                'product' => $stripeProduct->id,
                'unit_amount' => $variant->price * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'unhushed_variant_id' => $variant->var_id,
                    'sku' => $variant->sku
                ]
            ]);
            
            $variant->stripe_price_id = $stripePrice->id;
            $variant->save();
        }
        
        $this->info("Synced: {$product->name}");
    }
}
```

---

## Appendix B: Webhook Event Handlers

### Custom Webhook Listener
```php
// app/Listeners/StripeEventListener.php
namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use App\Models\Purchase;
use App\Models\ActiveSubscriptions;

class StripeEventListener
{
    public function handle(WebhookReceived $event)
    {
        switch ($event->payload['type']) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->payload['data']['object']);
                break;
                
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->payload['data']['object']);
                break;
                
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->payload['data']['object']);
                break;
        }
    }
    
    private function handleCheckoutCompleted($session)
    {
        // Create Purchase record
        $user = User::where('stripe_id', $session['customer'])->first();
        
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => $session['id'],
            'stripe_payment_intent_id' => $session['payment_intent'],
            'type' => $session['metadata']['type'] ?? 'product',
            'amount' => $session['amount_total'] / 100,
            'tax' => $session['total_details']['amount_tax'] / 100,
            'status' => 'completed',
            'metadata' => json_encode($session['metadata']),
            'completed_at' => now()
        ]);
        
        // Process line items
        $lineItems = \Stripe\Checkout\Session::allLineItems($session['id']);
        foreach ($lineItems->data as $item) {
            // Grant access based on metadata
        }
    }
    
    private function handleSubscriptionCreated($subscription)
    {
        $user = User::where('stripe_id', $subscription['customer'])->first();
        $productId = $subscription['metadata']['product_id'];
        
        ActiveSubscriptions::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'quantity' => $subscription['metadata']['qty'],
            'stripe_subscription_id' => $subscription['id'],
            'status' => 'active',
            'expires_at' => now()->addYear()
        ]);
    }
}
```

---

**End of Migration Plan**

For questions or clarifications, contact the development team lead.
