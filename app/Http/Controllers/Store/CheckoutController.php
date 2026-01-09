<?php
namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\PurchaseCart;
use App\Models\PurchaseItem;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\CurriculumPrice;
use App\Models\PurchaseFulfillment;
use App\Models\ActiveSubscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class CheckoutController extends Controller
{
    /**
     * Initiate Stripe Checkout for cart contents
     */
    public function checkout(Request $request, $path = 'educators')
    {
        $path = get_path($path);
        
        // Get or require user
        if (!Auth::check()) {
            session(['purchaser' => true]);
            session(['path' => $path]);
            return redirect("/{$path}/cart_login")->with('info', 'Please login or create an account to checkout');
        }
        
        $user = Auth::user();
        $cartId = session('cart_id');
        
        if (!$cartId || !$cart = PurchaseCart::find($cartId)) {
            return redirect("/{$path}/cart_products")->with('error', 'Your cart is empty');
        }
        
        $cartItems = PurchaseItem::where('cart_id', $cart->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect("/{$path}/cart_products")->with('error', 'Your cart is empty');
        }
        
        // REFACTORED: Let Stripe collect addresses and shipping - no custom address page needed
        // Stripe handles address collection, shipping selection, and tax calculation automatically
        
        // Separate items by product category (not item_type anymore)
        $subscriptionItems = $cartItems->filter(function($item) {
            $product = Products::find($item->product_id);
            return $product && $product->category == 1; // Curriculum
        });
        $oneTimeItems = $cartItems->filter(function($item) {
            $product = Products::find($item->product_id);
            return !$product || $product->category != 1; // Non-curriculum
        });
        
        // Determine checkout type
        if ($subscriptionItems->isNotEmpty() && $oneTimeItems->isNotEmpty()) {
            return redirect("/{$path}/carts")->with('error', 'Cannot mix subscriptions with other products. Please checkout separately.');
        }
        
        if ($subscriptionItems->isNotEmpty()) {
            return $this->checkoutSubscription($request, $path, $cart, $subscriptionItems);
        } else {
            return $this->checkoutOneTime($request, $path, $cart, $oneTimeItems);
        }
    }
    
    /**
     * Check if cart contains physical items that need shipping
     * Uses product_vars.ship_type: 0 = digital only, >0 = physical (requires shipping)
     */
    private function cartNeedsShipping($cartItems)
    {
        foreach ($cartItems as $cartItem) {
            if ($cartItem->var_id) {
                // Use where() because ProductVar primary key is 'var_id', not 'id'
                $variant = ProductVar::where('var_id', $cartItem->var_id)->first();
                
                \Log::info('Checking cart item shipping', [
                    'cart_item_id' => $cartItem->id,
                    'var_id' => $cartItem->var_id,
                    'variant_found' => $variant ? 'yes' : 'no',
                    'ship_type' => $variant ? $variant->ship_type : 'null'
                ]);
                
                // Any non-zero ship_type requires shipping (1=physical, 2=book, etc.)
                if ($variant && $variant->ship_type > 0) {
                    \Log::info('Cart needs shipping - found physical item', ['ship_type' => $variant->ship_type]);
                    return true;
                }
            }
        }
        
        \Log::info('Cart is digital-only - no shipping needed');
        return false;
    }
    
    /**
     * Handle one-time product checkout (categories 2,3,4,5,6,7)
     * REFACTORED: Now uses Stripe Payment Element instead of Embedded Checkout
     */
    private function checkoutOneTime(Request $request, $path, $cart, $items)
    {
        $user = Auth::user();
        $itemsData = [];
        $subtotal = 0;
        $totalWeight = 0;
        
        foreach ($items as $cartItem) {
            $product = Products::find($cartItem->product_id);
            
            if (!$product) {
                continue;
            }
            
            // Get variant 
            $variant = ProductVar::where('var_id', $cartItem->var_id)->first();
            
            if (!$variant) {
                return redirect("/{$path}/cart_products")->with('error', "Product variant not found for '{$product->name}'");
            }
            
            $itemPrice = $variant->price * $cartItem->quantity;
            $subtotal += $itemPrice;
            
            // Calculate weight in ounces (weight_lbs and weight_oz fields)
            $itemWeightLbs = $variant->weight_lbs ?? 0;
            $itemWeightOz = $variant->weight_oz ?? 0;
            $itemWeightInOunces = ($itemWeightLbs * 16) + $itemWeightOz;
            $totalWeight += ($itemWeightInOunces * $cartItem->quantity);
            
            $itemsData[] = [
                'name' => $product->name,
                'qty' => $cartItem->quantity,
                'price' => $variant->price,
                'subtotal' => $itemPrice,
                'weight' => $variant->weight ?? 0,
                'ship_type' => $variant->ship_type ?? 0,
                'category' => $product->category,
            ];
        }
        
        if (empty($itemsData)) {
            return redirect("/{$path}/cart_products")->with('error', 'No valid items in cart');
        }
        
        // Check if cart needs shipping
        $needsShipping = $this->cartNeedsShipping($items);
        
        // Create Stripe PaymentIntent
        try {
            $stripe = \Laravel\Cashier\Cashier::stripe();
            
            // Ensure user has a Stripe customer ID
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }
            
            // Calculate tax (8.25% sales tax) - exclude training products (category=7)
            $taxRate = 0.0825;
            $taxableSubtotal = 0;
            foreach ($items as $cartItem) {
                $product = Products::find($cartItem->product_id);
                
                \Log::info('Tax calculation - checking cart item', [
                    'cart_item_id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $product ? $product->name : 'not found',
                    'product_category' => $product ? $product->category : 'N/A'
                ]);
                
                if ($product && $product->category != 7) {
                    // Get variant price
                    $variant = ProductVar::where('var_id', $cartItem->var_id)->first();
                    $itemPrice = $variant ? ($variant->price * $cartItem->quantity) : 0;
                    $taxableSubtotal += $itemPrice;
                    
                    \Log::info('Item included in taxable subtotal', [
                        'product_name' => $product->name,
                        'category' => $product->category,
                        'item_price' => $itemPrice,
                        'running_taxable_subtotal' => $taxableSubtotal
                    ]);
                } else {
                    \Log::info('Item EXCLUDED from tax (training product)', [
                        'product_name' => $product ? $product->name : 'unknown',
                        'category' => $product ? $product->category : 'N/A'
                    ]);
                }
            }
            
            $taxAmount = $taxableSubtotal * $taxRate;
            
            \Log::info('Final tax calculation', [
                'taxable_subtotal' => $taxableSubtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'full_subtotal' => $subtotal
            ]);
            
            // For initial PaymentIntent, use subtotal + tax (shipping will be added later)
            // Note: Shipping is calculated on frontend based on address and selected method
            $total = $subtotal + $taxAmount;
            
            // Convert to cents for Stripe
            $amountInCents = (int)round($total * 100);
            
            $paymentIntentParams = [
                'amount' => $amountInCents,
                'currency' => 'usd',
                'customer' => $user->stripe_id,
                'metadata' => [
                    'cart_id' => $cart->id,
                    'user_id' => $user->id,
                    'path' => $path,
                    'needs_shipping' => $needsShipping ? '1' : '0',
                    'subtotal' => number_format($subtotal, 2),
                    'tax' => number_format($taxAmount, 2),
                    'tax_rate' => '8.25%',
                ],
                'description' => 'Product purchase from UNHUSHED store',
            ];
            
            $paymentIntent = $stripe->paymentIntents->create($paymentIntentParams);
            
            // Get user's shipping address (from session if selected, otherwise default)
            $shippingAddress = null;
            if ($needsShipping) {
                // First check if address was selected in checkout_address
                $selectedAddressId = session('shipping_address_id');
                if ($selectedAddressId) {
                    // Try to find in old shipping_addresses table first
                    $shippingAddress = \App\Models\ShippingAddress::where('user_id', $user->id)
                        ->where('id', $selectedAddressId)
                        ->first();
                    
                    // If not found, try user_addresses table
                    if (!$shippingAddress) {
                        $userAddress = \App\Models\UserAddress::where('user_id', $user->id)
                            ->where('id', $selectedAddressId)
                            ->first();
                        
                        // Convert to ShippingAddress format for compatibility
                        if ($userAddress) {
                            $shippingAddress = (object)[
                                'id' => $userAddress->id,
                                'user_id' => $userAddress->user_id,
                                'name' => $userAddress->name,
                                'street' => $userAddress->street,
                                'city' => $userAddress->city,
                                'state_province' => $userAddress->state_province,
                                'zip' => $userAddress->zip,
                                'country' => $userAddress->country,
                                'phone' => $userAddress->phone,
                            ];
                        }
                    }
                }
                
                // Fall back to default address from user_addresses table
                if (!$shippingAddress) {
                    $userAddress = \App\Models\UserAddress::where('user_id', $user->id)
                        ->where('default', true)
                        ->first();
                    
                    if ($userAddress) {
                        $shippingAddress = (object)[
                            'id' => $userAddress->id,
                            'user_id' => $userAddress->user_id,
                            'name' => $userAddress->name,
                            'street' => $userAddress->street,
                            'city' => $userAddress->city,
                            'state_province' => $userAddress->state_province,
                            'zip' => $userAddress->zip,
                            'country' => $userAddress->country,
                            'phone' => $userAddress->phone,
                        ];
                    }
                }
                    
                // If still no address, get most recent from either table
                if (!$shippingAddress) {
                    $shippingAddress = \App\Models\ShippingAddress::where('user_id', $user->id)
                        ->latest()
                        ->first();
                    
                    // Try user_addresses if nothing in shipping_addresses
                    if (!$shippingAddress) {
                        $userAddress = \App\Models\UserAddress::where('user_id', $user->id)
                            ->latest()
                            ->first();
                        
                        if ($userAddress) {
                            $shippingAddress = (object)[
                                'id' => $userAddress->id,
                                'user_id' => $userAddress->user_id,
                                'name' => $userAddress->name,
                                'street' => $userAddress->street,
                                'city' => $userAddress->city,
                                'state_province' => $userAddress->state_province,
                                'zip' => $userAddress->zip,
                                'country' => $userAddress->country,
                                'phone' => $userAddress->phone,
                            ];
                        }
                    }
                }
            }
            
            return view('store.cart.checkout_products')
                ->with('clientSecret', $paymentIntent->client_secret)
                ->with('path', $path)
                ->with('cartId', $cart->id)
                ->with('items', $itemsData)
                ->with('subtotal', $subtotal)
                ->with('taxAmount', $taxAmount)
                ->with('needsShipping', $needsShipping)
                ->with('totalWeight', round($totalWeight / 16, 2)) // Convert ounces to pounds
                ->with('shippingAddress', $shippingAddress)
                ->with('section3', 'payment');
            
        } catch (\Exception $e) {
            \Log::error('Stripe PaymentIntent Error: ' . $e->getMessage());
            return redirect("/{$path}/cart_products")->with('error', 'Unable to process checkout. Please try again.');
        }
    }
    
    /**
     * Handle curriculum subscription checkout (category 1)
     */
    private function checkoutSubscription(Request $request, $path, $cart, $items)
    {
        $user = Auth::user();
        
        // Curriculum subscriptions should only have one item
        if ($items->count() > 1) {
            return redirect("/{$path}/cart_subscriptions")->with('error', 'Please checkout curriculum subscriptions one at a time');
        }
        
        $cartItem = $items->first();
        $product = Products::find($cartItem->product_id);
        
        if (!$product || $product->category != 1) {
            return redirect("/{$path}/cart_subscriptions")->with('error', 'Invalid curriculum product');
        }
        
        // Get appropriate pricing tier based on quantity
        $quantity = $cartItem->quantity;
        $pricingTier = CurriculumPrice::where('product_id', $product->id)
            ->where('min_users', '<=', $quantity)
            ->where('max_users', '>=', $quantity)
            ->whereNotNull('stripe_price_id')
            ->first();
        
        if (!$pricingTier || !$pricingTier->stripe_price_id) {
            return redirect("/{$path}/cart_subscriptions")->with('error', 'No pricing available for selected quantity');
        }
        
        // Create subscription embedded checkout
        try {
            $stripe = \Laravel\Cashier\Cashier::stripe();
            
            // Ensure user has a Stripe customer ID
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }
            
            $sessionParams = [
                'ui_mode' => 'embedded',
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $pricingTier->stripe_price_id,
                    'quantity' => $quantity,
                ]],
                'return_url' => (config('app.env') === 'production' ? secure_url("/{$path}/checkout/success?session_id={CHECKOUT_SESSION_ID}") : url("/{$path}/checkout/success?session_id={CHECKOUT_SESSION_ID}")),
                'customer' => $user->stripe_id,
                'metadata' => [
                    'cart_id' => $cart->id,
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'path' => $path,
                    'type' => 'curriculum_subscription',
                ],
            ];
            
            // Enable automatic tax calculation for subscriptions
            if (config('cashier.calculate_tax', false)) {
                $sessionParams['automatic_tax'] = [
                    'enabled' => true,
                ];
                
                // Required for automatic tax - save billing address to customer
                $sessionParams['customer_update'] = [
                    'address' => 'auto',
                ];
                
                // Collect billing address for tax calculation
                $sessionParams['billing_address_collection'] = 'required';
            } else {
                // Even without tax, collect billing address
                $sessionParams['billing_address_collection'] = 'required';
            }
            
            $session = $stripe->checkout->sessions->create($sessionParams);
            
            return view('store.checkout.embedded')
                ->with('clientSecret', $session->client_secret)
                ->with('path', $path);
            
        } catch (\Exception $e) {
            \Log::error('Stripe Subscription Checkout Error: ' . $e->getMessage());
            return redirect("/{$path}/cart_subscriptions")->with('error', 'Unable to process subscription. Please try again.');
        }
    }
    
    /**
     * Handle successful checkout callback
     */
    public function success(Request $request, $path = 'educators')
    {
        $path = get_path($path);
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect("/{$path}/cart_products")->with('error', 'Invalid checkout session');
        }
        
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view your order');
        }
        
        try {
            // Retrieve Stripe session with expanded details
            $stripe = Cashier::stripe();
            $session = $stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['line_items', 'customer', 'subscription', 'shipping_cost', 'total_details']
            ]);
            
            if ($session->payment_status !== 'paid' && $session->status !== 'complete') {
                return redirect("/{$path}/cart_products")->with('error', 'Payment not completed');
            }
            
            // Save shipping address from Stripe to our database (if provided)
            if (isset($session->shipping_details) && $session->shipping_details) {
                $shippingDetails = $session->shipping_details;
                $address = $shippingDetails->address;
                
                // Create or update shipping address in our database
                $streetLine = trim(($address->line1 ?? '') . ' ' . ($address->line2 ?? ''));

                $savedAddress = \App\Models\ShippingAddress::updateOrCreate([
                    'user_id' => $user->id,
                    'name' => $shippingDetails->name,
                    'street' => $streetLine,
                    'city' => $address->city,
                    'state_province' => $address->state,
                    'zip' => $address->postal_code,
                    'country' => $address->country,
                ], [
                    'phone' => $shippingDetails->phone ?? null,
                    'updated_at' => now(),
                ]);
                
                \Log::info('Saved shipping address from Stripe', [
                    'address_id' => $savedAddress->id,
                    'user_id' => $user->id,
                ]);
            }
            
            // Get cart from metadata
            $cartId = $session->metadata->cart_id ?? null;
            
            if (!$cartId || !$cart = PurchaseCart::find($cartId)) {
                \Log::warning("Checkout success but cart not found: {$cartId}");
            } else {
                // Check if this session was already processed (prevent duplicate processing)
                if ($cart->completed == PurchaseCart::CART_COMPLETE) {
                    \Log::info('Checkout session already processed', [
                        'session_id' => $sessionId,
                        'cart_id' => $cartId
                    ]);
                    
                    $request->session()->forget('cart_id');
                    
                    return redirect("/{$path}/cart_products")->with('success', 'Thank you for your purchase! Your order has been completed successfully.');
                }
                
                // Update cart with payment and completion details
                $cart->payment_id = $session->payment_intent;
                $cart->tax = ($session->total_details->amount_tax ?? 0) / 100;
                $cart->shipping = ($session->total_details->amount_shipping ?? 0) / 100;
                $cart->total = $session->amount_total / 100;
                $cart->completed = PurchaseCart::CART_ORDERED;
                $cart->completed_at = now();
                $cart->save();
                
                // Get cart items and grant access
                $cartItems = PurchaseItem::where('cart_id', $cart->id)->get();
                
                // Separate items by shipping type
                $digitalItems = [];
                $physicalItems = [];
                
                foreach ($cartItems as $cartItem) {
                    $product = Products::find($cartItem->product_id);
                    
                    if (!$product) {
                        continue;
                    }
                    
                    // Check if item is digital or physical
                    $variant = \App\Models\ProductVar::where('var_id', $cartItem->var_id)->first();
                    if ($variant && $variant->ship_type == 0) {
                        $digitalItems[] = $cartItem;
                    } else {
                        $physicalItems[] = $cartItem;
                    }
                    
                    // Grant access based on product category
                    $this->grantAccess($user, $product, $cartItem->quantity, $session);
                }
                
                // Get shipping address from Stripe session
                $addressId = null;
                if (isset($session->shipping_details->address)) {
                    $address = $session->shipping_details->address;
                    
                    // Save or update address in user_addresses
                    $userAddress = \App\Models\UserAddress::updateOrCreate([
                        'user_id' => $user->id,
                        'street' => $address->line1,
                        'city' => $address->city,
                        'zip' => $address->postal_code,
                    ], [
                        'name' => $session->shipping_details->name ?? $user->name,
                        'phone' => $session->shipping_details->phone ?? null,
                        'state_province' => $address->state ?? '',
                        'country' => $address->country ?? 'US',
                        'updated_at' => now(),
                    ]);
                    
                    $addressId = $userAddress->id;
                }
                
                // Create fulfillment record for digital items
                if (!empty($digitalItems)) {
                    PurchaseFulfillment::create([
                        'cart_id' => $cart->id,
                        'address_id' => null, // Digital items don't need address
                        'status' => PurchaseFulfillment::STATUS_DIGITAL,
                        'shipped' => $cart->completed_at,
                        'tracking' => 'digital',
                        'notes' => 'Digital products - instant delivery',
                    ]);
                }
                
                // Create fulfillment record for physical items
                if (!empty($physicalItems)) {
                    PurchaseFulfillment::create([
                        'cart_id' => $cart->id,
                        'address_id' => $addressId,
                        'status' => PurchaseFulfillment::STATUS_ORDERED,
                        'notes' => 'Physical products - order placed via Stripe',
                    ]);
                }
                
                // Clear session cart
                $request->session()->forget('cart_id');
            }
            
            return redirect("/{$path}/cart_products")->with('success', 'Thank you for your purchase! Your order has been completed successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Checkout success handler error: ' . $e->getMessage());
            return redirect("/{$path}/cart_products")->with('error', 'Error processing your order. Please contact support.');
        }
    }
    
    /**
     * Grant user access to purchased products
     */
    private function grantAccess($user, $product, $quantity, $session)
    {
        // For curriculum subscriptions, create ActiveSubscription record
        if ($product->category == 1 && isset($session->subscription)) {
            ActiveSubscriptions::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'subscription_id' => $session->subscription,
                'quantity' => $quantity,
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addYear(),
            ]);
        }
        
        // TODO: Implement other access grants based on product category
        // - Add curriculum to user's account
        // - Send download links for digital products
        // - Queue fulfillment for physical products
        // - Schedule training sessions
    }
    
    /**
     * Get shipping rate amount from USPS for selected service
     */
    private function getShippingRateAmount($shippingMethod, $addressId, $cartId): float
    {
        try {
            $address = \App\Models\ShippingAddress::find($addressId);
            $cart = PurchaseCart::with(['items.product', 'items.variant'])->find($cartId);
            
            if (!$address || !$cart) {
                return 0;
            }
            
            $usps = new \App\Services\USPSShippingService();
            
            // Check if digital only
            if ($usps->isDigitalOnly($cart)) {
                return 0;
            }
            
            // Get rates
            if ($address->country === 'US') {
                $result = $usps->getDomesticRates($cart, $address);
            } else {
                $result = $usps->getInternationalRates($cart, $address);
            }
            
            // Find matching rate
            if (!isset($result['error']) && isset($result['rates'])) {
                foreach ($result['rates'] as $rate) {
                    if ($rate['service'] === $shippingMethod) {
                        return (float) $rate['rate'];
                    }
                }
            }
            
            return 0;
        } catch (\Exception $e) {
            \Log::error('Failed to get shipping rate', [
                'shipping_method' => $shippingMethod,
                'address_id' => $addressId,
                'cart_id' => $cartId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
     * Get shipping options for Stripe checkout session
     * Returns array of shipping_rate_data for Stripe's shipping_options parameter
     */
    private function getShippingOptions($cart)
    {
        $usps = new \App\Services\USPSShippingService();
        
        // Check if cart is digital only
        if ($usps->isDigitalOnly($cart)) {
            return []; // No shipping options needed for digital-only
        }
        
        // Try to get USPS rates (will use fallback if API unavailable)
        // For now, use fallback rates since USPS OAuth is pending activation
        $shippingOptions = [
            [
                'shipping_rate_data' => [
                    'type' => 'fixed_amount',
                    'fixed_amount' => [
                        'amount' => 500, // $5.00 in cents
                        'currency' => 'usd',
                    ],
                    'display_name' => 'USPS Ground Advantage',
                    'delivery_estimate' => [
                        'minimum' => [
                            'unit' => 'business_day',
                            'value' => 2,
                        ],
                        'maximum' => [
                            'unit' => 'business_day',
                            'value' => 5,
                        ],
                    ],
                ],
            ],
            [
                'shipping_rate_data' => [
                    'type' => 'fixed_amount',
                    'fixed_amount' => [
                        'amount' => 1000, // $10.00 in cents
                        'currency' => 'usd',
                    ],
                    'display_name' => 'USPS Priority Mail',
                    'delivery_estimate' => [
                        'minimum' => [
                            'unit' => 'business_day',
                            'value' => 1,
                        ],
                        'maximum' => [
                            'unit' => 'business_day',
                            'value' => 3,
                        ],
                    ],
                ],
            ],
        ];
        
        return $shippingOptions;
        
        // TODO: When USPS OAuth is activated, implement dynamic rate calculation:
        // - Get cart weight and dimensions
        // - Calculate rates using USPS API
        // - Format response for Stripe shipping_options
        // - Handle international shipping with different rates
    }
    
    /**
     * Calculate shipping rates using USPS API
     */
    public function calculateShipping(Request $request, $path = 'educators')
    {
        $path = get_path($path);
        
        try {
            $zip = $request->input('zip');
            $city = $request->input('city');
            $state = $request->input('state');
            $address = $request->input('address');
            $weight = $request->input('weight', 1); // Default 1 lb if not provided
            
            if (!$zip || !$state) {
                return response()->json([
                    'success' => false,
                    'message' => 'ZIP code and state are required'
                ]);
            }
            
            // Use ShippingAPI to get rates
            $shippingAPI = new ShippingAPI();
            
            $rates = [];
            
            // Try Ground Advantage (USPS_GROUND_ADVANTAGE is actually "GROUND_ADVANTAGE" for RateV4 API)
            $groundSuccess = $shippingAPI->check_cost($zip, 'GROUND_ADVANTAGE', ceil($weight));
            if ($groundSuccess) {
                $response = $shippingAPI->get_last_response();
                if (isset($response->Package->Postage)) {
                    $rates[] = [
                        'name' => 'USPS Ground Advantage',
                        'cost' => (float)$response->Package->Postage->Rate->__toString(),
                        'delivery_time' => '2-5 business days',
                        'service_code' => 'GROUND_ADVANTAGE'
                    ];
                }
            }
            
            // Try Priority Mail
            $prioritySuccess = $shippingAPI->check_cost($zip, 'PRIORITY', ceil($weight));
            if ($prioritySuccess) {
                $response = $shippingAPI->get_last_response();
                if (isset($response->Package->Postage)) {
                    $rates[] = [
                        'name' => 'USPS Priority Mail',
                        'cost' => (float)$response->Package->Postage->Rate->__toString(),
                        'delivery_time' => '1-3 business days',
                        'service_code' => 'PRIORITY'
                    ];
                }
            }
            
            // Try Priority Mail Express
            $expressSuccess = $shippingAPI->check_cost($zip, 'PRIORITY_MAIL_EXPRESS', ceil($weight));
            if ($expressSuccess) {
                $response = $shippingAPI->get_last_response();
                if (isset($response->Package->Postage)) {
                    $rates[] = [
                        'name' => 'USPS Priority Mail Express',
                        'cost' => (float)$response->Package->Postage->Rate->__toString(),
                        'delivery_time' => 'Overnight to 2-day',
                        'service_code' => 'PRIORITY_MAIL_EXPRESS'
                    ];
                }
            }
            
            if (empty($rates)) {
                // Fallback rates if USPS API fails
                $rates = [
                    [
                        'name' => 'USPS Ground Advantage',
                        'cost' => 5.00,
                        'delivery_time' => '2-5 business days',
                        'service_code' => 'GROUND_ADVANTAGE'
                    ],
                    [
                        'name' => 'USPS Priority Mail',
                        'cost' => 10.00,
                        'delivery_time' => '1-3 business days',
                        'service_code' => 'PRIORITY'
                    ]
                ];
                
                \Log::warning('Using fallback shipping rates', [
                    'zip' => $zip,
                    'weight' => $weight,
                    'error' => $shippingAPI->get_last_message()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'rates' => $rates
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Shipping calculation error: ' . $e->getMessage());
            
            // Return fallback rates on error
            return response()->json([
                'success' => true,
                'rates' => [
                    [
                        'name' => 'USPS Ground Advantage',
                        'cost' => 5.00,
                        'delivery_time' => '2-5 business days',
                        'service_code' => 'GROUND_ADVANTAGE'
                    ],
                    [
                        'name' => 'USPS Priority Mail',
                        'cost' => 10.00,
                        'delivery_time' => '1-3 business days',
                        'service_code' => 'PRIORITY'
                    ]
                ]
            ]);
        }
    }
    
    /**
     * Process payment after Stripe confirmation
     */
    public function processProductPayment(Request $request, $path = 'educators')
    {
        $path = get_path($path);
        
        try {
            $paymentIntentId = $request->input('payment_intent_id');
            $cartId = $request->input('cart_id');
            $shippingRate = $request->input('selected_shipping_rate');
            
            if (!$paymentIntentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment intent ID is required'
                ]);
            }
            
            $user = Auth::user();
            $cart = PurchaseCart::find($cartId);
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ]);
            }
            
            // Retrieve payment intent from Stripe to verify
            $stripe = \Laravel\Cashier\Cashier::stripe();
            $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);
            
            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed'
                ]);
            }
            
            // Parse shipping info
            $shippingInfo = $shippingRate ? json_decode($shippingRate, true) : null;
            $shippingCost = $shippingInfo['cost'] ?? 0;
            $shippingMethod = $shippingInfo['method'] ?? null;
            
            // Calculate expected total with tax and shipping
            $cartItems = PurchaseItem::where('cart_id', $cart->id)->get();
            $subtotal = 0;
            $taxableSubtotal = 0;
            foreach ($cartItems as $item) {
                $variant = ProductVar::where('var_id', $item->var_id)->first();
                $itemTotal = ($variant->price ?? 0) * $item->quantity;
                $subtotal += $itemTotal;
                
                // Exclude training products (category=7) from taxable amount
                $product = Products::find($item->product_id);
                if ($product && $product->category != 7) {
                    $taxableSubtotal += $itemTotal;
                }
            }
            
            $taxRate = 0.0825;
            $taxAmount = $taxableSubtotal * $taxRate;
            $expectedTotal = $subtotal + $taxAmount + $shippingCost;
            
            // Verify payment amount matches expected total (allow 1 cent difference for rounding)
            $paidAmount = $paymentIntent->amount / 100;
            if (abs($paidAmount - $expectedTotal) > 0.02) {
                \Log::warning('Payment amount mismatch', [
                    'paid' => $paidAmount,
                    'expected' => $expectedTotal,
                    'subtotal' => $subtotal,
                    'tax' => $taxAmount,
                    'shipping' => $shippingCost
                ]);
            }
            
            // Update cart with payment details instead of creating Purchase records
            $cart->payment_id = $paymentIntentId;
            $cart->tax = $taxAmount;
            $cart->shipping = $shippingCost;
            $cart->total = $paidAmount;
            $cart->completed = PurchaseCart::CART_ORDERED;
            $cart->completed_at = now();
            $cart->save();
            
            // Get shipping address if provided
            $shippingAddressId = $request->input('shipping_address_id');
            $addressId = null;
            if ($shippingAddressId) {
                $shippingAddress = \App\Models\ShippingAddress::find($shippingAddressId);
                
                // Migrate old shipping address to new user_addresses table
                if ($shippingAddress) {
                    $userAddress = \App\Models\UserAddress::updateOrCreate([
                        'user_id' => $user->id,
                        'street' => $shippingAddress->street,
                        'city' => $shippingAddress->city,
                        'zip' => $shippingAddress->zip,
                    ], [
                        'name' => $shippingAddress->name,
                        'phone' => $shippingAddress->phone ?? null,
                        'state_province' => $shippingAddress->state_province ?? '',
                        'country' => $shippingAddress->country ?? 'US',
                        'updated_at' => now(),
                    ]);
                    
                    $addressId = $userAddress->id;
                }
            }
            
            // Separate items by shipping type
            $digitalItems = [];
            $physicalItems = [];
            
            // Grant access for each item and categorize by shipping type
            foreach ($cartItems as $cartItem) {
                $product = Products::find($cartItem->product_id);
                
                if (!$product) {
                    continue;
                }
                
                // Check if item is digital or physical
                $variant = ProductVar::where('var_id', $cartItem->var_id)->first();
                if ($variant && $variant->ship_type == 0) {
                    $digitalItems[] = $cartItem;
                } else {
                    $physicalItems[] = $cartItem;
                }
                
                // Grant access based on product category
                $this->grantProductAccess($user, $product, $cartItem->quantity);
            }
            
            // Create fulfillment record for digital items
            if (!empty($digitalItems)) {
                PurchaseFulfillment::create([
                    'cart_id' => $cart->id,
                    'address_id' => null, // Digital items don't need address
                    'status' => PurchaseFulfillment::STATUS_DIGITAL,
                    'shipped' => $cart->completed_at,
                    'tracking' => 'digital',
                    'notes' => 'Digital products - instant delivery',
                ]);
            }
            
            // Create fulfillment record for physical items
            if (!empty($physicalItems)) {
                PurchaseFulfillment::create([
                    'cart_id' => $cart->id,
                    'address_id' => $addressId,
                    'status' => PurchaseFulfillment::STATUS_ORDERED,
                    'notes' => 'Physical products - order placed via direct payment',
                ]);
            }
            
            // Mark cart as complete
            $cart->completed = PurchaseCart::CART_COMPLETE;
            $cart->save();
            
            // Clear session cart
            $request->session()->forget('cart_id');
            
            // Check if any training products were purchased
            $hasTrainingProducts = false;
            foreach ($cartItems as $cartItem) {
                $product = Products::find($cartItem->product_id);
                if ($product && $product->category == 7) {
                    $hasTrainingProducts = true;
                    break;
                }
            }
            
            // Redirect training purchasers to assignment page
            if ($hasTrainingProducts) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase completed successfully',
                    'redirect_url' => url("/{$path}/dashboard/assign-access"),
                    'success_message' => 'Thank you! Your training purchase is complete. \nYou can now assign training seats to users in your organization.'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully',
                'redirect_url' => url("/{$path}/exit_purchased")
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Product payment processing error: ' . $e->getMessage(), [
                'payment_intent_id' => $request->input('payment_intent_id'),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update PaymentIntent amount to include shipping
     */
    public function updatePaymentIntentAmount(Request $request, $path = 'educators')
    {
        try {
            $clientSecret = $request->input('client_secret');
            $shippingCost = (float)$request->input('shipping_cost', 0);
            
            if (!$clientSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client secret required'
                ]);
            }
            
            // Extract PaymentIntent ID from client secret
            $paymentIntentId = explode('_secret_', $clientSecret)[0];
            
            $stripe = \Laravel\Cashier\Cashier::stripe();
            $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);
            
            // Calculate new amount (current amount + shipping)
            $currentAmount = $paymentIntent->amount; // Already includes subtotal + tax
            $newAmount = $currentAmount + (int)round($shippingCost * 100);
            
            // Update the PaymentIntent amount
            $updatedIntent = $stripe->paymentIntents->update($paymentIntentId, [
                'amount' => $newAmount,
                'metadata' => array_merge($paymentIntent->metadata->toArray(), [
                    'shipping_cost' => number_format($shippingCost, 2)
                ])
            ]);
            
            return response()->json([
                'success' => true,
                'amount' => $newAmount / 100
            ]);
            
        } catch (\Exception $e) {
            \Log::error('PaymentIntent update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating payment amount'
            ]);
        }
    }
    
    /**
     * Grant access to purchased product
     */
    private function grantProductAccess($user, $product, $quantity)
    {
        \Log::info('Granting product access', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'category' => $product->category,
            'quantity' => $quantity
        ]);
        
        // Category-specific logic
        switch($product->category) {
            case 1: // Curriculum subscriptions
                $this->grantCurriculumAccess($user, $product, $quantity);
                break;
            case 7: // Training products
                $this->grantTrainingAccess($user, $product, $quantity);
                break;
            default:
                // Other products (activities, books, games, swag, toolkits)
                // Purchase history tracked in purchase_carts/purchase_items - no special access grants needed
                \Log::info('Standard product purchase - access via purchase history', [
                    'category' => $product->category
                ]);
                break;
        }
    }
    
    /**
     * Grant access to training product (category=7)
     * Creates ActiveSubscriptions pool for organization assignment
     */
    private function grantTrainingAccess($user, $product, $quantity)
    {
        // STEP 1: Assign "head" role to purchaser (payment already confirmed at this point)
        if(!$user->hasRole('head')){
            $user->addRole('head');
            \Log::info('Assigned head role to user after training purchase', [
                'user_id' => $user->id
            ]);
        }
        
        // STEP 2: Ensure user has valid organization
        $orgCheckResult = $this->ensureUserHasOrganization($user);
        
        if($orgCheckResult['needs_org_creation']){
            // User needs to create an organization
            // Store pending training access in session for completion after org creation
            session([
                'pending_training_access' => [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ],
                'email_domain' => $orgCheckResult['email_domain'],
                'user_name' => $orgCheckResult['user_name'],
                'has_pending_training' => true
            ]);
            
            \Log::info('Training access pending - user needs to create organization', [
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            
            return;
        }
        
        // Reload user to get updated org_id
        $user->refresh();
        
        // STEP 3: Create ActiveSubscriptions record for training pool
        $activeSubscription = \App\Models\ActiveSubscriptions::where('org_id', $user->org_id)
            ->where('product_id', $product->id)
            ->where('category', 7)
            ->first();
        
        if($activeSubscription){
            // Add to existing training pool
            $activeSubscription->total += $quantity;
            $activeSubscription->save();
            
            \Log::info('Added to existing training pool', [
                'org_id' => $user->org_id,
                'product_id' => $product->id,
                'new_total' => $activeSubscription->total,
                'used' => $activeSubscription->used
            ]);
        } else {
            // Create new training pool
            \App\Models\ActiveSubscriptions::create([
                'org_id' => $user->org_id,
                'product_id' => $product->id,
                'category' => 7,
                'status' => 1, // active
                'total' => $quantity,
                'used' => 0,
            ]);
            
            \Log::info('Created new training pool', [
                'org_id' => $user->org_id,
                'product_id' => $product->id,
                'total' => $quantity
            ]);
        }
    }
    
    /**
     * Grant access to curriculum subscription (category=1)
     * Placeholder for future curriculum one-time purchase handling
     */
    private function grantCurriculumAccess($user, $product, $quantity)
    {
        // Curriculum is typically handled via Stripe subscriptions
        // This method is for any one-time curriculum purchases
        \Log::info('Curriculum one-time purchase - may need subscription setup', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }
    
    /**
     * Ensure user has a valid organization (copied from CartSubscriptController)
     * Returns array with 'needs_org_creation' flag and org details
     */
    private function ensureUserHasOrganization($user)
    {
        // Log initial state
        \Log::info('Checking organization for user', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'current_org_id' => $user->org_id
        ]);
        
        // Check if user already has an org_id
        if($user->org_id && $user->org_id > 0){
            \Log::info('User already has organization', ['org_id' => $user->org_id]);
            return ['needs_org_creation' => false, 'org_id' => $user->org_id];
        }
        
        // User has no org_id - check for email domain match
        $emailDomain = $this->extractEmailDomain($user->email);
        
        \Log::info('Extracted email domain', [
            'email' => $user->email,
            'domain' => $emailDomain
        ]);
        
        if($emailDomain){
            $matchingOrg = \App\Models\Organizations::where('email_match', $emailDomain)->first();
            
            if($matchingOrg){
                // Found matching organization - assign user to it
                $user->org_id = $matchingOrg->id;
                $user->save();
                
                \Log::info('Assigned user to existing organization by email domain', [
                    'user_id' => $user->id,
                    'org_id' => $matchingOrg->id,
                    'org_name' => $matchingOrg->name,
                    'email_domain' => $emailDomain
                ]);
                
                return ['needs_org_creation' => false, 'org_id' => $matchingOrg->id];
            }
        }
        
        // No matching org found - user needs to create one
        \Log::info('User needs to create organization - no match found', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_domain' => $emailDomain
        ]);
        
        return [
            'needs_org_creation' => true,
            'email_domain' => $emailDomain,
            'user_name' => $user->name
        ];
    }
    
    /**
     * Extract email domain from email address (everything after @)
     */
    private function extractEmailDomain($email)
    {
        if(!$email || !str_contains($email, '@')){
            return null;
        }
        
        $parts = explode('@', $email);
        return $parts[1] ?? null;
    }
}
