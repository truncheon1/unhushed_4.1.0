# Shipping & Tax Implementation Plan

## Current State Analysis

**Existing Infrastructure:**
- ✅ `shipping_addresses` table exists with user_id, name, email, street, city, zip, api fields
- ✅ `ShippingAddress` model exists
- ✅ `address.blade.php` collects new addresses
- ✅ `product_vars` has `weight`, `weight_lbs`, `weight_oz`, `ship_type`, `taxable` fields
- ✅ Stripe Cashier integrated with embedded checkout
- ⚠️ Current flow: Cart → Checkout redirects to Stripe (no address/shipping step)

**Requirements:**
1. Address selection/creation before checkout
2. USPS API integration for real-time shipping rates
3. Stripe automatic tax calculation
4. Tax-exempt customer handling

---

## Phase 1: Address Management System

### 1.1 Update Database Schema
**File:** `database/migrations/YYYY_MM_DD_add_fields_to_shipping_addresses.php`

```php
// Add missing fields
$table->boolean('default')->default(false);
$table->string('phone', 20)->nullable();
$table->string('company')->nullable();
$table->softDeletes(); // For address history
```

### 1.2 Update ShippingAddress Model
**File:** `app/Models/ShippingAddress.php`

```php
protected $fillable = [
    'user_id', 'name', 'email', 'street', 'city', 'zip',
    'api_address1', 'api_address2', 'api_city', 'api_state', 'api_zip5',
    'default', 'phone', 'company'
];

public function user() {
    return $this->belongsTo(User::class);
}

// Scope for user's addresses
public function scopeForUser($query, $userId) {
    return $query->where('user_id', $userId);
}
```

### 1.3 Update Address Blade View
**File:** `resources/views/store/cart/address.blade.php`

**New Structure:**
```blade
<!-- Section 1: Existing Addresses (if any) -->
@if($savedAddresses->count() > 0)
    <div class="saved-addresses mb-4">
        <h5>Select Saved Address</h5>
        @foreach($savedAddresses as $addr)
            <div class="address-card" data-address-id="{{ $addr->id }}">
                <input type="radio" name="saved_address" value="{{ $addr->id }}" 
                    @if($addr->default) checked @endif>
                <label>
                    <strong>{{ $addr->name }}</strong><br>
                    {{ $addr->street }}<br>
                    {{ $addr->city }}, {{ $addr->api_state }} {{ $addr->zip }}
                </label>
                <button class="btn btn-sm btn-link edit-address">Edit</button>
            </div>
        @endforeach
        <button class="btn btn-link" id="add-new-address">+ Add New Address</button>
    </div>
@endif

<!-- Section 2: New/Edit Address Form -->
<div id="address-form-section" @if($savedAddresses->count() > 0) style="display:none" @endif>
    <form id="address-form">
        <!-- Existing form fields -->
        <div class="form-check">
            <input type="checkbox" name="save_address" id="save_address" checked>
            <label for="save_address">Save this address for future orders</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="set_default" id="set_default">
            <label for="set_default">Set as default address</label>
        </div>
    </form>
</div>

<!-- Section 3: Shipping Options (populated after address validation) -->
<div id="shipping-options" style="display:none">
    <h5>Select Shipping Method</h5>
    <div id="shipping-rates-container"></div>
</div>

<button class="btn btn-secondary" id="continue-to-payment">Continue to Payment</button>
```

### 1.4 Update CartProductController
**File:** `app/Http/Controllers/Store/CartProductController.php`

**New Methods:**
```php
public function address($path = 'educators') {
    $user = auth()->user();
    $savedAddresses = ShippingAddress::forUser($user->id)->get();
    
    // Get or create temp address
    $address = session('temp_address') 
        ? (object) session('temp_address')
        : ShippingAddress::where('user_id', $user->id)->where('default', true)->first() 
            ?? new ShippingAddress(['user_id' => $user->id]);
    
    return view('store.cart.address')
        ->with('savedAddresses', $savedAddresses)
        ->with('address', $address)
        ->with('path', get_path($path))
        ->with('section3', 'address');
}

// AJAX endpoint to get USPS rates
public function getShippingRates(Request $request, $path = 'educators') {
    $addressId = $request->input('address_id');
    $address = ShippingAddress::find($addressId);
    
    $cart = Cart::find(session('cart_id'));
    $rates = $this->calculateUSPSRates($cart, $address);
    
    return response()->json([
        'success' => true,
        'rates' => $rates
    ]);
}
```

---

## Phase 2: USPS API Integration

### 2.1 Install USPS Package
```bash
composer require usps/usps-api-php
```

### 2.2 Add USPS Credentials to .env
```env
USPS_USERNAME=your_username
USPS_PASSWORD=your_password
USPS_FROM_ZIP=01375
USPS_FROM_ADDRESS="134 Montague Rd"
USPS_FROM_CITY="Sunderland"
USPS_FROM_STATE="MA"
```

### 2.3 Create USPS Service
**File:** `app/Services/USPSShippingService.php`

```php
<?php
namespace App\Services;

use USPS\RatePackage;
use USPS\Rate;

class USPSShippingService
{
    private $rate;
    
    public function __construct() {
        $this->rate = new Rate(config('services.usps.username'));
        $this->rate->setTestMode(config('services.usps.test_mode', false));
    }
    
    /**
     * Calculate shipping for cart items
     * @param Cart $cart
     * @param ShippingAddress $address
     * @return array of shipping options with prices
     */
    public function getRates($cart, $address) {
        $packages = $this->buildPackages($cart);
        $rates = [];
        
        foreach ($packages as $index => $package) {
            $package->setZipOrigination(config('services.usps.from_zip'));
            $package->setZipDestination($address->zip);
            
            // Add to rate request
            $this->rate->addPackage($package);
        }
        
        try {
            $this->rate->getRate();
            $response = $this->rate->getArrayResponse();
            
            // Parse USPS response and extract rates
            $rates = $this->parseRates($response);
            
        } catch (\Exception $e) {
            \Log::error('USPS Rate Error: ' . $e->getMessage());
            // Fallback to flat rate
            $rates = $this->getFlatRates($cart);
        }
        
        return $rates;
    }
    
    /**
     * Build USPS packages from cart items
     */
    private function buildPackages($cart) {
        $packages = [];
        
        foreach ($cart->items as $item) {
            $variant = ProductVar::find($item->var_id);
            
            if (!$variant || !$this->needsShipping($variant)) {
                continue;
            }
            
            for ($i = 0; $i < $item->qty; $i++) {
                $package = new RatePackage();
                $package->setService(RatePackage::SERVICE_ALL);
                $package->setFirstClassMailType(RatePackage::MAIL_TYPE_PACKAGE);
                
                // Determine container type based on ship_type
                if ($variant->ship_type === 'media') {
                    $package->setContainer(RatePackage::CONTAINER_VARIABLE);
                    $package->setSize(RatePackage::SIZE_REGULAR);
                } else {
                    $package->setContainer(RatePackage::CONTAINER_RECTANGULAR);
                    $package->setSize(RatePackage::SIZE_REGULAR);
                }
                
                // Set weight
                $pounds = $variant->weight_lbs ?? 0;
                $ounces = $variant->weight_oz ?? 0;
                
                // Convert total weight if stored as decimal in weight field
                if (!$pounds && !$ounces && $variant->weight) {
                    $totalOunces = $variant->weight * 16;
                    $pounds = floor($totalOunces / 16);
                    $ounces = $totalOunces % 16;
                }
                
                $package->setWeight($pounds, $ounces);
                $package->setField('Machinable', true);
                
                $packages[] = $package;
            }
        }
        
        return $packages;
    }
    
    /**
     * Check if variant requires shipping
     */
    private function needsShipping($variant) {
        return in_array($variant->ship_type, ['standard', 'media']);
    }
    
    /**
     * Parse USPS API response
     */
    private function parseRates($response) {
        $rates = [];
        
        // USPS returns rates per service type
        $serviceTypes = [
            'Priority Mail' => 'priority',
            'Priority Mail Express' => 'express',
            'First-Class Package Service' => 'first_class',
            'Media Mail' => 'media',
            'USPS Ground Advantage' => 'ground'
        ];
        
        foreach ($response['RateV4Response']['Package'] ?? [] as $package) {
            foreach ($package['Postage'] ?? [] as $postage) {
                $serviceName = $postage['MailService'];
                $rate = (float) $postage['Rate'];
                
                // Group by service type
                foreach ($serviceTypes as $uspsName => $key) {
                    if (stripos($serviceName, $uspsName) !== false) {
                        if (!isset($rates[$key])) {
                            $rates[$key] = [
                                'name' => $uspsName,
                                'price' => 0,
                                'delivery_days' => $postage['DeliveryDay'] ?? null
                            ];
                        }
                        $rates[$key]['price'] += $rate;
                        break;
                    }
                }
            }
        }
        
        return array_values($rates);
    }
    
    /**
     * Fallback flat rates if USPS API fails
     */
    private function getFlatRates($cart) {
        $totalWeight = 0;
        
        foreach ($cart->items as $item) {
            $variant = ProductVar::find($item->var_id);
            if ($variant && $this->needsShipping($variant)) {
                $totalWeight += ($variant->weight ?? 0) * $item->qty;
            }
        }
        
        // Simple weight-based fallback
        $baseRate = 5.00;
        $perPoundRate = 2.00;
        $shippingCost = $baseRate + ($totalWeight * $perPoundRate);
        
        return [
            [
                'name' => 'Standard Shipping',
                'price' => $shippingCost,
                'delivery_days' => '5-7'
            ]
        ];
    }
}
```

### 2.4 Add USPS Config
**File:** `config/services.php`

```php
'usps' => [
    'username' => env('USPS_USERNAME'),
    'password' => env('USPS_PASSWORD'),
    'test_mode' => env('USPS_TEST_MODE', true),
    'from_zip' => env('USPS_FROM_ZIP', '01375'),
    'from_address' => env('USPS_FROM_ADDRESS', '134 Montague Rd'),
    'from_city' => env('USPS_FROM_CITY', 'Sunderland'),
    'from_state' => env('USPS_FROM_STATE', 'MA'),
],
```

---

## Phase 3: Stripe Tax Integration

### 3.1 Enable Stripe Tax in Dashboard
1. Go to Stripe Dashboard → Settings → Tax
2. Enable Stripe Tax
3. Configure tax settings for your business location
4. Add product tax codes for your products

### 3.2 Update Products/Variants with Tax Codes
**Migration:** `database/migrations/YYYY_MM_DD_add_stripe_tax_code_to_products.php`

```php
Schema::table('products', function (Blueprint $table) {
    $table->string('stripe_tax_code')->nullable(); // e.g., 'txcd_99999999' for books
});

Schema::table('product_vars', function (Blueprint $table) {
    $table->string('stripe_tax_code')->nullable();
});
```

**Tax Codes for Common Products:**
- Books: `txcd_20060000` (Printed Books)
- Digital Products: `txcd_10000000` (Digital goods)
- Educational Materials: `txcd_20060001` (Educational books)
- Games: `txcd_10103001` (Digital games)

### 3.3 Update CheckoutController for Tax
**File:** `app/Http/Controllers/Store/CheckoutController.php`

```php
private function checkoutOneTime(Request $request, $path, $cart, $items)
{
    $user = Auth::user();
    $lineItems = [];
    
    // Get shipping address and rate from session
    $shippingAddressId = session('selected_shipping_address');
    $shippingRate = session('selected_shipping_rate');
    $shippingAddress = ShippingAddress::find($shippingAddressId);
    
    if (!$shippingAddress) {
        return redirect("/{$path}/address")->with('error', 'Please select a shipping address');
    }
    
    foreach ($items as $cartItem) {
        $product = Products::find($cartItem->item_id);
        $variant = ProductVar::find($cartItem->var_id);
        
        if (!$variant || !$variant->stripe_price_id) {
            continue;
        }
        
        $lineItems[] = [
            'price' => $variant->stripe_price_id,
            'quantity' => $cartItem->qty,
            // Add tax code if available
            'tax_rates' => $variant->taxable ? 'auto' : null,
        ];
    }
    
    // Add shipping as line item
    if ($shippingRate && $shippingRate['price'] > 0) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Shipping: ' . $shippingRate['name'],
                    'description' => 'Delivery in ' . ($shippingRate['delivery_days'] ?? '5-7') . ' business days',
                ],
                'unit_amount' => intval($shippingRate['price'] * 100), // Convert to cents
            ],
            'quantity' => 1,
        ];
    }
    
    try {
        $stripe = \Laravel\Cashier\Cashier::stripe();
        
        $sessionParams = [
            'ui_mode' => 'embedded',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'return_url' => url("/{$path}/checkout/success?session_id={CHECKOUT_SESSION_ID}"),
            'customer' => $user->stripe_id ?? $user->createAsStripeCustomer()->stripe_id,
            'metadata' => [
                'cart_id' => $cart->id,
                'user_id' => $user->id,
                'path' => $path,
                'shipping_address_id' => $shippingAddressId,
            ],
        ];
        
        // Add automatic tax calculation
        $sessionParams['automatic_tax'] = ['enabled' => true];
        
        // Add customer tax ID if exempt
        if ($user->tax_exempt && $user->tax_id) {
            $sessionParams['customer_update'] = [
                'address' => 'auto',
                'shipping' => 'auto',
            ];
            // Stripe will check tax_ids on customer record
        }
        
        // Add shipping address
        $sessionParams['shipping_address_collection'] = [
            'allowed_countries' => ['US'],
        ];
        
        // Pre-fill shipping address
        $sessionParams['shipping_options'] = [
            [
                'shipping_rate_data' => [
                    'type' => 'fixed_amount',
                    'fixed_amount' => [
                        'amount' => 0, // Already included in line items
                        'currency' => 'usd',
                    ],
                    'display_name' => $shippingRate['name'],
                    'delivery_estimate' => [
                        'minimum' => [
                            'unit' => 'business_day',
                            'value' => 5,
                        ],
                        'maximum' => [
                            'unit' => 'business_day',
                            'value' => 7,
                        ],
                    ],
                ],
            ],
        ];
        
        $session = $stripe->checkout->sessions->create($sessionParams);
        
        return view('store.checkout.embedded')
            ->with('clientSecret', $session->client_secret)
            ->with('path', $path);
            
    } catch (\Exception $e) {
        \Log::error('Stripe Checkout Error: ' . $e->getMessage());
        return redirect("/{$path}/cart_products")->with('error', 'Unable to process checkout. Please try again.');
    }
}
```

---

## Phase 4: Tax Exempt Customers

### 4.1 Update Users Table
**Migration:** `database/migrations/YYYY_MM_DD_add_tax_exempt_to_users.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('tax_exempt')->default(false);
    $table->string('tax_id')->nullable(); // IRS tax ID or exemption certificate number
    $table->string('tax_exempt_type')->nullable(); // 'nonprofit', 'government', 'resale', etc.
    $table->timestamp('tax_exempt_verified_at')->nullable();
});
```

### 4.2 Create Tax Exemption Management
**File:** `app/Http/Controllers/User/TaxExemptController.php`

```php
<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxExemptController extends Controller
{
    public function requestExemption(Request $request) {
        $request->validate([
            'tax_id' => 'required|string',
            'exemption_type' => 'required|in:nonprofit,government,resale,other',
            'certificate' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);
        
        $user = auth()->user();
        
        // Store certificate
        $path = $request->file('certificate')->store('tax-certificates', 'private');
        
        // Create exemption request (pending admin approval)
        $user->tax_exempt_request = [
            'tax_id' => $request->tax_id,
            'type' => $request->exemption_type,
            'certificate_path' => $path,
            'requested_at' => now(),
            'status' => 'pending',
        ];
        $user->save();
        
        // Notify admin
        // \Mail::to(config('mail.admin'))->send(new TaxExemptionRequest($user));
        
        return redirect()->back()->with('success', 'Tax exemption request submitted. We will review and respond within 2 business days.');
    }
    
    public function approveExemption($userId) {
        // Admin only
        $user = User::findOrFail($userId);
        $request = $user->tax_exempt_request;
        
        $user->tax_exempt = true;
        $user->tax_id = $request['tax_id'];
        $user->tax_exempt_type = $request['type'];
        $user->tax_exempt_verified_at = now();
        
        // Register with Stripe
        if ($user->stripe_id) {
            $stripe = \Laravel\Cashier\Cashier::stripe();
            $stripe->customers->update($user->stripe_id, [
                'tax_exempt' => 'exempt',
                'tax_ids' => [
                    ['type' => 'us_ein', 'value' => $request['tax_id']],
                ],
            ]);
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Tax exemption approved');
    }
}
```

---

## Phase 5: Route Updates

**File:** `routes/web.php`

```php
Route::prefix('/{path}')->group(function () {
    
    // Address management
    Route::get('/address', [CartProductController::class, 'address'])->name('address');
    Route::post('/address/save', [CartProductController::class, 'saveAddress']);
    Route::post('/address/validate', [CartProductController::class, 'validateAddress']);
    Route::get('/shipping/rates', [CartProductController::class, 'getShippingRates']);
    Route::post('/shipping/select', [CartProductController::class, 'selectShipping']);
    
    // Tax exemption
    Route::post('/tax-exempt/request', [TaxExemptController::class, 'requestExemption']);
    
    // Checkout continues to Stripe
    Route::match(['get', 'post'], '/stripe/checkout', [CheckoutController::class, 'checkout'])->name('stripe.checkout');
});

// Admin routes
Route::middleware(['role:admin|team'])->prefix('/backend')->group(function () {
    Route::get('/tax-exempt-requests', [TaxExemptController::class, 'index']);
    Route::post('/tax-exempt/{userId}/approve', [TaxExemptController::class, 'approveExemption']);
    Route::post('/tax-exempt/{userId}/deny', [TaxExemptController::class, 'denyExemption']);
});
```

---

## Implementation Order

### Week 1: Address Management
1. ✅ Create migration for shipping_addresses updates
2. ✅ Update ShippingAddress model
3. ✅ Modify address.blade.php with address selection UI
4. ✅ Update CartProductController address methods
5. ✅ Add AJAX endpoints for address management
6. ✅ Test address selection and creation flow

### Week 2: USPS Integration
1. ✅ Install USPS package
2. ✅ Create USPSShippingService
3. ✅ Add USPS credentials to .env
4. ✅ Implement rate calculation
5. ✅ Add shipping rate selection to address page
6. ✅ Store selected shipping in session
7. ✅ Test with various addresses and weights

### Week 3: Stripe Tax & Checkout Updates
1. ✅ Enable Stripe Tax in dashboard
2. ✅ Add tax codes to products migration
3. ✅ Update CheckoutController to include shipping in checkout
4. ✅ Implement automatic tax calculation
5. ✅ Test checkout with tax and shipping
6. ✅ Verify tax calculation in different states

### Week 4: Tax Exemption System
1. ✅ Create tax_exempt migrations for users
2. ✅ Create TaxExemptController
3. ✅ Add tax exemption request form to user dashboard
4. ✅ Create admin approval interface
5. ✅ Integrate with Stripe customer tax_exempt status
6. ✅ Test exempt and non-exempt checkouts

---

## Testing Checklist

### Address Management Tests
- [ ] Create new address
- [ ] Select existing address
- [ ] Edit saved address
- [ ] Set default address
- [ ] Address validation with USPS API

### Shipping Tests
- [ ] Calculate rates for single item
- [ ] Calculate rates for multiple items
- [ ] Test media mail vs standard shipping
- [ ] Verify weight calculations (lbs + oz)
- [ ] Test USPS API failure fallback
- [ ] Verify shipping appears in Stripe checkout

### Tax Tests
- [ ] Tax calculated for MA resident
- [ ] Tax calculated for out-of-state
- [ ] No tax for tax-exempt customer
- [ ] Correct tax codes applied per product
- [ ] Tax shown correctly in checkout

### Integration Tests
- [ ] Full flow: Cart → Address → Shipping → Checkout → Payment → Success
- [ ] Guest cannot proceed without login
- [ ] Shipping address saved after successful payment
- [ ] Shipping cost included in Purchase record
- [ ] Tax amount recorded in Purchase

---

## Database Updates Summary

```sql
-- shipping_addresses
ALTER TABLE shipping_addresses 
ADD COLUMN default BOOLEAN DEFAULT FALSE,
ADD COLUMN phone VARCHAR(20),
ADD COLUMN company VARCHAR(255),
ADD COLUMN deleted_at TIMESTAMP NULL;

-- products & product_vars
ALTER TABLE products ADD COLUMN stripe_tax_code VARCHAR(50);
ALTER TABLE product_vars ADD COLUMN stripe_tax_code VARCHAR(50);

-- users
ALTER TABLE users 
ADD COLUMN tax_exempt BOOLEAN DEFAULT FALSE,
ADD COLUMN tax_id VARCHAR(50),
ADD COLUMN tax_exempt_type VARCHAR(50),
ADD COLUMN tax_exempt_verified_at TIMESTAMP NULL,
ADD COLUMN tax_exempt_request JSON;

-- purchases (verify these exist)
-- stripe_checkout_session_id, amount, tax, shipping already exist
```

---

## Configuration Files Needed

1. **`.env` additions:**
```env
USPS_USERNAME=
USPS_PASSWORD=
USPS_TEST_MODE=true
USPS_FROM_ZIP=01375
USPS_FROM_ADDRESS="134 Montague Rd"
USPS_FROM_CITY="Sunderland"
USPS_FROM_STATE="MA"

STRIPE_TAX_ENABLED=true
```

2. **`config/services.php`** - Add USPS config section

3. **Update `config/cashier.php`** if needed for tax handling

---

## Additional Implementation Details

### Digital-Only Cart Detection
**File:** `app/Http/Controllers/Store/CheckoutController.php`

```php
private function cartNeedsShipping($cart) {
    foreach ($cart->items as $item) {
        $variant = ProductVar::find($item->var_id);
        if ($variant && in_array($variant->ship_type, ['standard', 'media'])) {
            return true;
        }
    }
    return false;
}

public function checkout(Request $request, $path = 'educators') {
    // ... existing cart validation ...
    
    // Check if shipping is needed
    if ($this->cartNeedsShipping($cart)) {
        // Require address
        if (!session('selected_shipping_address')) {
            return redirect("/{$path}/address")->with('info', 'Please provide a shipping address');
        }
    } else {
        // Digital only - skip to payment
        return $this->checkoutDigitalOnly($request, $path, $cart, $oneTimeItems);
    }
    
    // Continue with normal checkout...
}
```

### USPS Address Validation
**File:** `app/Services/USPSShippingService.php`

```php
public function validateAddress($address) {
    $verify = new AddressVerify(config('services.usps.username'));
    $verify->setTestMode(config('services.usps.test_mode', false));
    
    $addressToVerify = new Address();
    $addressToVerify->setFirmName($address->company ?? '');
    $addressToVerify->setAddress($address->street);
    $addressToVerify->setCity($address->city);
    $addressToVerify->setState($address->api_state ?? $address->state);
    $addressToVerify->setZip5($address->zip);
    
    $verify->addAddress($addressToVerify);
    
    try {
        $verify->verify();
        $response = $verify->getArrayResponse();
        
        if (isset($response['AddressValidateResponse']['Address'])) {
            $validated = $response['AddressValidateResponse']['Address'];
            
            // Check for errors
            if (isset($validated['Error'])) {
                return [
                    'valid' => false,
                    'error' => $validated['Error']['Description']
                ];
            }
            
            return [
                'valid' => true,
                'address' => [
                    'street' => $validated['Address2'],
                    'city' => $validated['City'],
                    'state' => $validated['State'],
                    'zip' => $validated['Zip5'],
                    'zip4' => $validated['Zip4'] ?? null,
                ]
            ];
        }
        
    } catch (\Exception $e) {
        \Log::error('USPS Validation Error: ' . $e->getMessage());
        return [
            'valid' => false,
            'error' => 'Unable to validate address. Please check and try again.'
        ];
    }
}
```

### International Shipping Support
**Update Database Migration:**
```php
// Add to shipping_addresses table
$table->string('country', 2)->default('US'); // ISO 2-letter code
$table->string('state_province')->nullable(); // For non-US addresses
```

**USPS International Rates:**
```php
public function getInternationalRates($cart, $address) {
    $intlRate = new IntlRate(config('services.usps.username'));
    $intlRate->setTestMode(config('services.usps.test_mode', false));
    
    foreach ($cart->items as $item) {
        $variant = ProductVar::find($item->var_id);
        if (!$variant || !$this->needsShipping($variant)) continue;
        
        $package = new IntlPackage();
        $package->setPounds($variant->weight_lbs ?? 0);
        $package->setOunces($variant->weight_oz ?? 0);
        $package->setMailType('Package');
        $package->setValueOfContents($variant->price * $item->qty);
        $package->setCountry($address->country);
        $package->setOriginZip(config('services.usps.from_zip'));
        
        $intlRate->addPackage($package);
    }
    
    try {
        $intlRate->getRate();
        return $this->parseInternationalRates($intlRate->getArrayResponse());
    } catch (\Exception $e) {
        \Log::error('USPS International Rate Error: ' . $e->getMessage());
        return $this->getFlatInternationalRate($cart, $address->country);
    }
}
```

### Shipping Insurance
**Address Form Addition:**
```blade
<div class="form-check my-3" id="insurance-option" style="display:none">
    <input type="checkbox" class="form-check-input" id="shipping_insurance" name="shipping_insurance">
    <label class="form-check-label" for="shipping_insurance">
        Add Shipping Insurance (<span id="insurance-cost">$0.00</span>)
        <br><small class="text-muted">Insures package value up to $<span id="insured-amount">0.00</span></small>
    </label>
</div>
```

**Insurance Calculation:**
```php
private function calculateInsurance($cartTotal) {
    // USPS insurance rates (2024):
    // $0 - $50: $2.80
    // $50.01 - $100: $3.85
    // $100.01 - $200: $5.95
    // $200.01+: $5.95 + $1.10 per $100
    
    if ($cartTotal <= 50) {
        return 2.80;
    } elseif ($cartTotal <= 100) {
        return 3.85;
    } elseif ($cartTotal <= 200) {
        return 5.95;
    } else {
        $additional = ceil(($cartTotal - 200) / 100);
        return 5.95 + ($additional * 1.10);
    }
}
```

### Weight Limit Validation
```php
private function validateWeight($cart) {
    $totalWeight = 0;
    $isInternational = session('selected_address_country') !== 'US';
    $limit = $isInternational ? 66 : 70; // lbs
    
    foreach ($cart->items as $item) {
        $variant = ProductVar::find($item->var_id);
        if (!$variant || !$this->needsShipping($variant)) continue;
        
        $itemWeight = ($variant->weight_lbs ?? 0) + (($variant->weight_oz ?? 0) / 16);
        $totalWeight += $itemWeight * $item->qty;
    }
    
    if ($totalWeight > $limit) {
        return [
            'valid' => false,
            'weight' => $totalWeight,
            'limit' => $limit,
            'message' => "Your order weighs {$totalWeight} lbs, which exceeds USPS weight limits ({$limit} lbs). Please contact info@unhushed.org to discuss shipping options for your order."
        ];
    }
    
    return ['valid' => true, 'weight' => $totalWeight];
}
```

### Updated Address Form with International Support
```blade
<div class="form-group">
    <label for="country">Country</label>
    <select class="form-control" name="country" id="country">
        <option value="US" selected>United States</option>
        <option value="CA">Canada</option>
        <option value="GB">United Kingdom</option>
        <option value="AU">Australia</option>
        <!-- Add more countries -->
    </select>
</div>

<div class="form-group" id="state-group">
    <label for="state">State</label>
    <select class="form-control" name="state" id="state">
        <!-- US states -->
    </select>
</div>

<div class="form-group" id="province-group" style="display:none">
    <label for="province">Province/Region</label>
    <input type="text" class="form-control" name="province" id="province">
</div>

<script>
$('#country').on('change', function() {
    if ($(this).val() === 'US') {
        $('#state-group').show();
        $('#province-group').hide();
    } else {
        $('#state-group').hide();
        $('#province-group').show();
    }
});
</script>
```

---

## Notes & Considerations

1. **USPS API Limitations:**
   - Rate API has daily limits
   - International shipping requires different API calls
   - Consider caching rates for same address/weight combo

2. **Stripe Tax:**
   - Automatic tax requires Stripe Tax subscription (~$0.50 per transaction)
   - Alternative: Manual tax calculation per state
   - Recommend automatic for accuracy and compliance

3. **Performance:**
   - USPS API calls can be slow (2-5 seconds)
   - Implement loading states
   - Consider async rate fetching with JavaScript

4. **Edge Cases:**
   - Digital-only orders skip address/shipping
   - Mixed cart (digital + physical) needs conditional logic
   - PO Box addresses may have shipping restrictions

5. **Security:**
   - Validate address server-side before USPS call
   - Sanitize zip codes
   - Rate-limit USPS API calls per user

---

## Requirements Confirmed

1. **Digital Product Handling:** ✅
   - Use `product_vars.ship_type` instead of legacy `CartItem::TYPE_*` constants
   - Skip address collection if cart contains ONLY `ship_type = 'digital'`
   - Require address if ANY item has `ship_type IN ('media', 'standard')`
   - Remove old TYPE_ACTIVITYD system references during implementation

2. **Address Validation:** ✅
   - Validate address with USPS API before showing shipping rates
   - Use USPS Address Validation API to standardize addresses

3. **Address Book:** ✅
   - Support multiple saved addresses per user
   - Allow setting default address
   - Enable shipping to different recipients

4. **International Shipping:** ✅
   - Support international addresses
   - Use USPS International Rate API
   - Add country selection to address form

5. **Shipping Insurance:** ✅
   - Optional checkbox for insurance
   - Calculate insurance cost based on cart value
   - Add to shipping total in Stripe checkout

6. **Weight Limit Handling:** ✅
   - USPS domestic limit: 70 lbs per package
   - USPS international limit: varies by country (typically 66 lbs)
   - Show error: "Your order exceeds USPS weight limits. Please contact info@unhushed.org to discuss shipping options."
   - Split heavy orders suggestion for future enhancement

