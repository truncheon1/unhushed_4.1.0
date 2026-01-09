<?php
namespace App\Http\Controllers\Finance;
use App\Http\Controllers\Controller;
use App\Models\PurchaseCart;
use App\Models\PurchaseItem;
use App\Models\Orders;
use App\Models\Products;
use App\Models\PurchaseFulfillment;
use App\Models\Subscriptions;

class BillingController extends Controller
{
    public function billing($path = 'educators'){
        $details = [];
        $user = auth()->user();
        
        // ===== COMPLETED ORDERS (PURCHASE CARTS) =====
        // Get all completed purchase carts
        $completedCarts = PurchaseCart::where('user_id', $user->id)
            ->whereIn('completed', [PurchaseCart::CART_COMPLETE])
            ->orderBy('completed_at', 'DESC')
            ->with(['items.product', 'items.variant', 'fulfillment.address'])
            ->get();
        
        foreach($completedCarts as $cart){
            // Get items for this cart
            $items = $cart->items;
            if($items->isEmpty()) continue;
            
            // Determine type based on products in cart
            $hasSubscription = false;
            foreach($items as $item){
                $product = $item->product;
                if($product->category == 1) $hasSubscription = true;
            }
            
            if($hasSubscription){
                $typeLabel = 'subscription';
            } else{
                $typeLabel = 'products';
            }
            
            // Get fulfillment record for this cart
            $fulfillment = $cart->fulfillment;
            
            // Build fulfillment data
            $fulfillmentData = [];
            if($cart->shipping > 0){
                if($fulfillment){
                    $statusLabel = 'Ordered';
                    if($fulfillment->status == PurchaseFulfillment::STATUS_SHIPPED){
                        $statusLabel = 'Shipped';
                    } elseif($fulfillment->status == PurchaseFulfillment::STATUS_DIGITAL){
                        $statusLabel = 'Digital';
                    } elseif($fulfillment->status == PurchaseFulfillment::STATUS_BACKORDER){
                        $statusLabel = 'Backorder';
                    } elseif($fulfillment->status == PurchaseFulfillment::STATUS_CANCELED){
                        $statusLabel = 'Canceled';
                    }
                    
                    // Get address from fulfillment relationship
                    $shippingAddress = $fulfillment->address;
                    
                    $fulfillmentData[] = [
                        'status' => $statusLabel,
                        'shipped_date' => $fulfillment->shipped,
                        'tracking' => $fulfillment->tracking ?? '',
                        'type' => 'physical',
                        'name' => $shippingAddress->name ?? '',
                        'address1' => $shippingAddress->street ?? '',
                        'city' => $shippingAddress->city ?? '',
                        'state' => $shippingAddress->state_province ?? '',
                        'zip' => $shippingAddress->zip ?? '',
                    ];
                } else {
                    // No fulfillment record yet - still processing
                    $fulfillmentData[] = [
                        'status' => 'Ordered',
                        'shipped_date' => null,
                        'tracking' => '',
                        'type' => 'physical',
                        'name' => '',
                        'address1' => '',
                        'address2' => '',
                        'city' => '',
                        'state' => '',
                        'zip' => '',
                    ];
                }
            } else {
                // Digital product
                $fulfillmentData[] = [
                    'status' => 'Digital',
                    'shipped_date' => $fulfillment->shipped,
                    'tracking' => 'Digital Delivery',
                    'type' => 'digital',
                    'name' => '',
                    'address1' => '',
                    'city' => '',
                    'state' => '',
                    'zip' => '',
                ];
            }
            
            // Build items list from `purchase_items` with variant details
            $itemsList = [];
            foreach($items as $item){
                $variantInfo = '';
                $variantName = '';
                
                if ($item->var_id && $item->variant) {
                    $variant = $item->variant;
                    $variantName = $variant->name ?: '';
                    
                    // Get option values (size, color, etc.) from junction table
                    $optionValues = \DB::table('product_options_vars_junction')
                        ->join('product_options_values', 'product_options_vars_junction.value_id', '=', 'product_options_values.value_id')
                        ->join('product_options', 'product_options_values.options_id', '=', 'product_options.option_id')
                        ->where('product_options_vars_junction.var_id', $variant->var_id)
                        ->select('product_options.name as option_name', 'product_options_values.name as value_name')
                        ->get();
                    
                    $optionParts = [];
                    foreach($optionValues as $opt) {
                        $optionParts[] = $opt->option_name . ': ' . $opt->value_name;
                    }
                    $variantInfo = implode(', ', $optionParts);
                }
                
                $itemsList[] = [
                    'name' => $item->product->name ?? 'Unknown Product',
                    'variant_name' => $variantName,
                    'variant_info' => $variantInfo,
                    'qty' => $item->quantity
                ];
            }
            
            $details[] = [
                'order'     => $cart,
                'id'        => $cart->id,
                'total'     => $cart->total,
                'type'      => $typeLabel,
                'date'      => $cart->completed_at ?? $cart->created_at,
                'fulfillments' => $fulfillmentData,
                'items'     => $itemsList,
                'shipping'  => $cart->shipping,
                'source'    => 'stripe',
                'table'     => 'purchase_carts',
            ];
        }
        
        // ===== LEGACY SUBSCRIPTION ORDERS (OLD PAYPAL SYSTEM) =====
        // Keep legacy Orders table for historical data
        $orders = Orders::where([
            ['user_id', $user->id],
            ['status', '>', '0'],
        ])
        ->orderBy('created_at', 'DESC')
        ->get();
        
        foreach($orders as $order){
            // Get subscription details if exists
            $subscription = Subscriptions::where('order_id', $order->id)->first();
            $product = null;
            $items = [];
            
            if($subscription){
                $product = Products::find($subscription->product_id);
                if($product){
                    $items[] = [
                        'name' => $product->name,
                        'qty' => 1
                    ];
                }
            }
            
            // Determine payment source - check if order has Stripe IDs
            $paymentSource = 'paypal'; // Default to PayPal for legacy orders
            if(!empty($order->stripe_session_id) || !empty($order->stripe_subscription_id)){
                $paymentSource = 'stripe';
            }
            
            // Determine subscription status
            $subscriptionStatus = 'canceled';
            if($subscription){
                // Check subscription status: 0=INACTIVE, 1=ACTIVE/INTENT, 2=SUBSCRIPTION_ACTIVE, 3=EXPIRED/CANCELED, 4=REVIEWING
                if($subscription->active == 1 || $subscription->active == 2){
                    $subscriptionStatus = 'active';
                } elseif($subscription->active == 3){
                    $subscriptionStatus = 'canceled';
                } elseif($subscription->active == 4){
                    $subscriptionStatus = 'reviewing';
                } else {
                    $subscriptionStatus = 'canceled';
                }
            }
            
            $details[] = [
                'order'     => $order,
                'id'        => $order->id,
                'total'     => $order->total,
                'type'      => 'subscription',
                'date'      => $order->created_at,
                'fulfillments' => [[
                    'status' => $subscriptionStatus,
                    'shipped_date' => null,
                    'tracking' => '',
                    'type' => 'subscription',
                    'name' => '',
                    'address1' => '',
                    'address2' => '',
                    'city' => '',
                    'state' => '',
                    'zip' => '',
                ]],
                'items'     => $items,
                'source'    => $paymentSource,
                'table'     => 'orders',
            ];
        }
        
        // Sort all details by date (newest first)
        usort($details, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        
        return view('backend.finance.billing.billing', compact('details'))
        ->with('section', 'billing')
        ->with('sect2', 'billing')
        ->with('path', get_path($path));
    }

    public function subscriptions($path = 'educators'){
        $details = [];
        $subs = Subscriptions::where([
            ['user_id', auth()->user()->id],
            ['active', '>=', '1'],  // Changed from > 1 to >= 1 to include all active subscriptions
        ])->get();
        
        // Initialize Stripe
        \Stripe\Stripe::setApiKey(config('cashier.secret'));
        \Stripe\Stripe::setApiVersion('2025-12-15.clover');
        
        foreach($subs as $sub){
            $id = $sub->id;
            $product_id = $sub->product_id;
            $product = null;
            $product = Products::where('id', $product_id)
                ->where('category', 1)
                ->first();
            if($product){
                if($product){
                    // Get total and used from active_subscriptions
                    $activeSubscription = \App\Models\ActiveSubscriptions::where('product_id', $product_id)
                        ->where('org_id', auth()->user()->org_id)
                        ->where('category', 1)
                        ->first();
                    
                    $total = $activeSubscription ? $activeSubscription->total : 0;
                    $used = $activeSubscription ? $activeSubscription->used : 0;
                    
                    $since = date('Y', strtotime($sub->created_at));
                    
                    // Get real-time status from Stripe if subscription_id exists
                    $s_status = 'active'; // Default
                    if($sub->subscription_id){
                        try {
                            $stripeSubscription = \Stripe\Subscription::retrieve($sub->subscription_id);
                            $stripeStatus = $stripeSubscription->status;
                            $cancelAtPeriodEnd = $stripeSubscription->cancel_at_period_end ?? false;
                            
                            \Log::info('Stripe subscription status check', [
                                'subscription_id' => $sub->subscription_id,
                                'stripe_status' => $stripeStatus,
                                'cancel_at_period_end' => $cancelAtPeriodEnd,
                                'database_active' => $sub->active
                            ]);
                            
                            // Map Stripe status to our status
                            // IMPORTANT: Check cancel_at_period_end FIRST - if true, subscription is canceled
                            // even if Stripe status shows 'active' until period end
                            if($cancelAtPeriodEnd === true || in_array($stripeStatus, ['canceled', 'cancelled'])){
                                $s_status = 'canceled';
                                // Update local database if it doesn't match
                                if($sub->active != 3){
                                    \Log::info('Updating subscription to canceled', [
                                        'subscription_id' => $sub->subscription_id,
                                        'old_status' => $sub->active
                                    ]);
                                    $sub->active = 3;
                                    $sub->save();
                                }
                            } elseif(in_array($stripeStatus, ['active', 'trialing'])){
                                $s_status = 'active';
                                // Update local database if it doesn't match
                                if($sub->active != 2){
                                    $sub->active = 2;
                                    $sub->save();
                                }
                            } elseif(in_array($stripeStatus, ['incomplete', 'incomplete_expired', 'past_due', 'unpaid'])){
                                $s_status = 'reviewing';
                                // Update local database if it doesn't match
                                if($sub->active != 4){
                                    $sub->active = 4;
                                    $sub->save();
                                }
                            } else {
                                // Unknown status - log it
                                \Log::warning('Unknown Stripe subscription status', [
                                    'subscription_id' => $sub->subscription_id,
                                    'stripe_status' => $stripeStatus,
                                    'cancel_at_period_end' => $cancelAtPeriodEnd
                                ]);
                            }
                            
                            // Update renewal date from Stripe
                            if($stripeSubscription->current_period_end){
                                $renew = date('m-d-Y', $stripeSubscription->current_period_end);
                                // Update local database if different
                                $newExpDate = date('Y-m-d H:i:s', $stripeSubscription->current_period_end);
                                if($sub->exp_date != $newExpDate){
                                    $sub->exp_date = $newExpDate;
                                    $sub->save();
                                }
                            } else {
                                $renew = date('m-d-Y', strtotime($sub->exp_date));
                            }
                        } catch (\Exception $e) {
                            // If Stripe lookup fails, fall back to database status
                            \Log::warning('Failed to retrieve Stripe subscription status', [
                                'subscription_id' => $sub->subscription_id,
                                'error' => $e->getMessage()
                            ]);
                            
                            // Use database status as fallback
                            if($sub->active == 1){
                                $s_status = 'active';
                            } elseif($sub->active == 2){
                                $s_status = 'active';
                            } elseif($sub->active == 3){
                                $s_status = 'canceled';
                            } elseif($sub->active == 4){
                                $s_status = 'reviewing';
                            }
                            $renew = date('m-d-Y', strtotime($sub->exp_date));
                        }
                    } else {
                        // No Stripe subscription_id, use database status
                        if($sub->active == 1){
                            $s_status = 'active';
                        } elseif($sub->active == 2){
                            $s_status = 'active';
                        } elseif($sub->active == 3){
                            $s_status = 'canceled';
                        } elseif($sub->active == 4){
                            $s_status = 'reviewing';
                        }
                        $renew = date('m-d-Y', strtotime($sub->exp_date));
                    }
                    
                    $due = $sub->due;
                    $details[] = [
                        'id'         => $id,
                        'img'        => $product->image,
                        'name'       => $product->name,
                        'since'      => $since,
                        's_status'   => $s_status,
                        'renew'      => $renew,
                        'due'        => $due,
                        'total'      => $total,
                        'used'       => $used,
                    ];
                }
            }
        }
        return view('backend.finance.billing.my-subscriptions', compact('details'))
        ->with('section', 'billing')
        ->with('sect2', 'my-subscriptions')
        ->with('path', get_path($path));
    }

    public function shipping($path = 'educators'){
        $details = [];
        $orders = PurchaseCart::whereIn('completed', [PurchaseCart::CART_COMPLETE, PurchaseCart::CART_ORDERED])
            ->where('user_id', auth()->user()->id)
            ->where('shipping', '>', 0) // Only show orders with physical shipping
            ->orderBy('created_at', 'DESC')
            ->with(['items.product', 'fulfillment.address'])
            ->get();
        
        $completed = [
            PurchaseCart::CART_OPEN =>'Added',
            PurchaseCart::CART_ORDERED => 'Checkout',
            PurchaseCart::CART_COMPLETE => 'Completed',
            PurchaseCart::CART_CANCELED => 'Canceled'
        ];
        
        foreach($orders as $order){
            $id = $order->id;
            
            // Get fulfillment record and its address
            $fulfillment = $order->fulfillment;
            $shippingAddress = $fulfillment ? $fulfillment->address : null;
            
            // Skip if no shipping address found
            if(!$shippingAddress)
                continue;
            
            $items = $order->items;
            $ci = [];
            foreach($items as $item){
                $p = $item->product;
                if(!$p)
                    continue;
                $ci[] = [
                    "name" => $p->name,
                    "qty" => $item->quantity
                ];
            }
            
            $name       = $shippingAddress->name;
            $address1   = $shippingAddress->street;
            $address2   = '';
            $city       = $shippingAddress->city;
            $state      = $shippingAddress->state_province;
            $zip        = $shippingAddress->zip;
            $total      = $order->total;
            
            // Get fulfillment status
            $status = PurchaseCart::CART_COMPLETE;
            $shipped = $fulfillment->shipped ?? null;
            $tracking = $fulfillment->tracking ?? '';
            
            $details[]  = [
                'order'     => $order,
                'id'        => $id,
                'name'      => $name,
                'address1'  => $address1,
                'address2'  => $address2,
                'city'      => $city,
                'state'     => $state,
                'zip'       => $zip,
                'total'     => $order->total,
                'status'    => $this->get_status($status),
                'shipped'   => $shipped,
                'tracking'  => $tracking,
                'items'     => $ci
            ];
        }
        return view('backend.finance.billing.shipping', compact('details'))
        ->with('section', 'billing')
        ->with('sect2', 'shipping')
        ->with('path', get_path($path));
    }

    public function donations($path = 'educators'){
        // Get selected year from request, default to current year
        $selectedYear = request('year', date('Y'));
        
        // Get recurring donations (not filtered by year - these are active subscriptions)
        $recurringDonations = \App\Models\UserDonation::where('user_id', auth()->id())
            ->where('recurring', true)
            ->where('status', 'completed')
            ->whereNotNull('subscription_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get ALL completed donations for history filtered by selected year
        $allDonations = \App\Models\UserDonation::where('user_id', auth()->id())
            ->completed()
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate total donated for selected year
        $totalDonated = \App\Models\UserDonation::where('user_id', auth()->id())
            ->completed()
            ->whereYear('created_at', $selectedYear)
            ->sum('amount');
        
        // Get the earliest donation year for dropdown range
        $earliestDonation = \App\Models\UserDonation::where('user_id', auth()->id())
            ->completed()
            ->orderBy('created_at', 'asc')
            ->first();
        $startYear = $earliestDonation ? $earliestDonation->created_at->year : date('Y');
        
        return view('backend.finance.billing.my-donations')
            ->with('section', 'billing')
            ->with('sect2', 'my-donations')
            ->with('path', get_path($path))
            ->with('selectedYear', $selectedYear)
            ->with('recurringDonations', $recurringDonations)
            ->with('allDonations', $allDonations)
            ->with('totalDonated', $totalDonated)
            ->with('startYear', $startYear);
    }

    private function get_status($status){
        $s = "ORDERED";
        if($status == 1)
            $s = "SENT";
        if($status == 2)
            $s = "CANCELED";
        if($status == 3)
            $s = "BACK ORDERED";
        return $s;
    }
}
