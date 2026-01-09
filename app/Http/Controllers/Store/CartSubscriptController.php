<?php
namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\CurriculumPrice;
use App\Models\Discounts;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;


class CartSubscriptController extends Controller
{
    //Show both carts and item counts in header and cart icon page
    public function carts($path = 'educators') {
        return view('store.cart.carts')
        ->with('path', get_path($path));
    }
    public function has_purchase_items(){
        return $this->subscription_count() > 0 ? true : false;
    }
    public function subscription_count(){
        if(!session('order_id')){
            return 0;
        }
        $order = Orders::find(session('order_id'));
        if(!$order){
            return 0;
        }
        $count = 0;
        $orderItems = OrderItems::where('order_id', $order->id)->get();
        if($orderItems){
            foreach($orderItems as $item){
                $count += $item->qty;
            }
        }
        return $count;
    }

    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
        Stripe::setApiVersion('2025-12-15.clover');
    }

    //@return \Illuminate\Contracts\Support\Renderable
    public function view_cart(Request $req, $path = 'educators'){
        if(!session('order_id')){
            $order = new Orders();
        }else{
            $order = Orders::find(session('order_id'));
        }
        $order->user_id = Auth::check() ? Auth::user()->id : 0;
        $order->status = Orders::STATUS_INITIATED;
        $order->total = 0;
        $order->save();
        $itemCount = 0;
        $req->session()->put('order_id', $order->id);
        //get total quantity for accurate pricing first
        $existing_items = OrderItems::where('order_id', $order->id)->get();
        $totalQuantity = 0;
        if($existing_items){
            foreach($existing_items as $i){
                $totalQuantity += $i->qty;
            }
        }
        if($req->input('qty')){
            foreach($req->input('qty') as $q){
                $totalQuantity += $q;
            }
        }
        $total = 0;
        if($req->input('id')){
            foreach($req->input('id') as $id){
                if($req->input('type')[$id] == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                    $package = Products::find($id);
                    if(!$package || $package->category != 1){
                        continue;
                    }
                    if($req->input('qty')[$package->id] <= 0){
                        continue;
                    }
                    //check if an order item exists for the same package
                    if($package){
                        $option = CurriculumPrice::getOptionForRange($package->id, $totalQuantity);
                        $price = $option->discount_price * $req->input('qty')[$package->id];
                        $items[] = [
                            'item_id'=>$id,
                            'qty'=>$req->input('qty')[$package->id],
                            'price'=>$price
                        ];
                        $total += $price;
                        $orderItem = OrderItems::where('item_id', $id)->where('order_id', $order->id)->where('item_type', OrderItems::ITEM_TYPE_SUBSCRIPTION)->first();
                        if(!$orderItem){
                            $orderItem = new OrderItems();
                            $orderItem->item_type = $req->input('type')[$id];
                            $orderItem->order_id = $order->id;
                            $orderItem->item_id = $package->id;
                            $orderItem->qty = $req->input('qty')[$package->id];
                            $orderItem->price = $price;
                        }else{
                            $orderItem->qty += $req->input('qty')[$package->id];
                            $orderItem->price += $price;
                        }
                        $orderItem->save();
                        $itemCount++;
                    }
                }else if($req->input('type')[$id] == OrderItems::ITEM_TYPE_TRAINING){
                    $training = Products::find($id);
                    if(!$training) continue;
                    $price = $training->price * $req->input('qty')[$training->id];
                    if($req->input('qty')[$training->id] <= 0)
                        continue;
                    $items[] = [
                        'item_id'=>$id,
                        'qty'=>$req->input('qty')[$training->id],
                        'price'=>$price
                    ];
                    $total += $price;
                    $orderItem = OrderItems::where('item_id', $id)->where('item_type', OrderItems::ITEM_TYPE_TRAINING)->where('order_id', $order->id)->first();
                    if(!$orderItem){
                        $orderItem = new OrderItems();
                        $orderItem->item_type = $req->input('type')[$id];
                        $orderItem->order_id = $order->id;
                        $orderItem->item_id = $training->id;
                        $orderItem->qty = $req->input('qty')[$training->id];
                        $orderItem->price = $price;
                    }else{
                        $orderItem->qty += $req->input('qty')[$training->id];
                        $orderItem->price += $price;
                    }
                    $orderItem->save();
                    $itemCount++;
                }
            }
        }
        //adjust existing order items
        foreach($existing_items as $i){
            if($i->item_type == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                $package = Products::find($i->item_id);
                if(!$package || $package->category != 1) continue;
                $option = CurriculumPrice::getOptionForRange($package->id, $totalQuantity);
                $price = $option->discount_price * $i->qty;
                $total += $price;
                $i->price = $price;
                $i->save();
            }
        }
        $order->total = $total;
        $order->save();
        session('order_id', $order->id);
        //compute rows
        $total = 0;
        $total_standard = 0;
        $rows = [];
        foreach(OrderItems::where('order_id', $order->id)->get() as $oi){
            $sum = $oi->price;
            $total += $sum;
            if($oi->item_type == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                $p = Products::find($oi->item_id);
                if(!$p || $p->category != 1) continue;
                $option = CurriculumPrice::getOptionForRange($p->id, $totalQuantity);
                $standard = $option->standard_price;
                $total_standard += $standard;
                $rows[] = [
                    'id'=>$oi->id,
                    'product'=>$p->name." - ".$option->package_caption,
                    'slug'=>$p->slug,
                    'price'=>$option->discount_price,
                    'total_discount'=>$standard - $sum,
                    'standard'=>$option->standard_price,
                    'discount'=>$option->standard_price - $option->discount_price,
                    'qty'=>$oi->qty,
                    'type'=> OrderItems::ITEM_TYPE_SUBSCRIPTION
                ];
            }else{
                $p = Products::find($oi->item_id);
                if(!$p) continue;
                $sum = $p->price * $oi->qty;
                $standard = $p->price * $oi->qty;
                $total_standard += $standard;
                $rows[] = [
                    'id'=>$oi->id,
                    'product'=>$p->name,
                    'slug'=>$p->slug,
                    'price'=>$p->price,
                    'total_discount'=>$standard - $sum,
                    'standard'=>$p->price,
                    'discount'=>$p->price,
                    'qty'=>$oi->qty,
                    'type'=> OrderItems::ITEM_TYPE_TRAINING
                ];
            }
            $order->total = $total;
            $order->save();
        }
        
        // Always show the cart view, regardless of whether items were just added
        return view('store.cart.cart_subscriptions')->with('rows', $rows)
        ->with('total', $total)
        ->with('section3', 'cart')
        ->with('path', get_path($path));
    }
    
    /**
     * Show subscription payment page with Stripe Elements
     */
    private function showPaymentPage($path = 'educators')
    {
        $orderId = session('order_id');
        if(!$orderId){
            return redirect()->to($path.'/cart_subscriptions')
                ->with('error', 'Order session expired.');
        }
        
        $order = Orders::find($orderId);
        if(!$order || $order->status != Orders::STATUS_INITIATED){
            return redirect()->to($path.'/cart_subscriptions')
                ->with('error', 'Order not found or already processed.');
        }
        
        $user = Auth::user();
        if(!$user){
            return redirect()->to($path.'/cart_sub_login');
        }
        
        // Prepare order items for display
        $items = [];
        $totalToday = 0;
        $recurringTotal = 0;
        
        $orderItems = OrderItems::where('order_id', $order->id)->get();
        $totalQty = $orderItems->sum('qty');
        
        foreach($orderItems as $oi){
            if($oi->item_type == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                $product = Products::where('category', 1)->find($oi->item_id);
                if(!$product) continue;
                
                $option = CurriculumPrice::getOptionForRange($product->id, $totalQty);
                if(!$option) continue;
                
                $subtotal = $option->discount_price * $oi->qty;
                $recurringSubtotal = $option->recurring_price * $oi->qty;
                
                $items[] = [
                    'name' => $product->name . " - " . $option->package_caption,
                    'qty' => $oi->qty,
                    'price' => $option->discount_price,
                    'subtotal' => $subtotal,
                    'recurring_price' => $option->recurring_price,
                    'recurring_subtotal' => $recurringSubtotal,
                ];
                
                $totalToday += $subtotal;
                $recurringTotal += $recurringSubtotal;
            }
        }
        
        // Get discount if applied
        $discount = null;
        if(session('discount')){
            $discountCode = session('discount')['code'];
            $discountModel = \App\Models\Discounts::where('code', $discountCode)->first();
            if($discountModel){
                if($discountModel->discount_type == \App\Models\Discounts::TYPE_FIXED){
                    $discountAmount = $discountModel->value;
                } else {
                    $discountAmount = ($totalToday / 100) * $discountModel->value;
                }
                $discount = [
                    'code' => $discountCode,
                    'amount' => $discountAmount
                ];
                $totalToday -= $discountAmount;
            }
        }
        
        return view('store.cart.checkout_subscriptions')
            ->with('orderId', $orderId)
            ->with('items', $items)
            ->with('total', $totalToday)
            ->with('recurringTotal', $recurringTotal)
            ->with('discount', $discount)
            ->with('clientSecret', $this->createSetupIntent())
            ->with('section3', 'payment')
            ->with('path', get_path($path));
    }
    
    /**
     * Create a SetupIntent for Payment Element
     */
    private function createSetupIntent()
    {
        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');
            
            $user = Auth::user();
            
            // Get or create Stripe customer
            if(!$user->stripe_id){
                $user->createAsStripeCustomer();
            }
            
            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $user->stripe_id,
                'payment_method_types' => ['card'],
                'usage' => 'off_session',
            ]);
            
            return $setupIntent->client_secret;
        } catch (\Exception $e) {
            Log::error('Error creating SetupIntent: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Process subscription payment with Payment Element
     */
    public function subscription_payment_process(Request $request, $path = 'educators')
    {
        $validated = $request->validate([
            'order_id' => 'required|integer',
            'setup_intent_id' => 'required|string'
        ]);
        
        $orderId = $validated['order_id'];
        $setupIntentId = $validated['setup_intent_id'];
        
        // Retrieve the SetupIntent to get the payment method
        \Stripe\Stripe::setApiKey(config('cashier.secret'));
        \Stripe\Stripe::setApiVersion('2025-12-15.clover');
        $setupIntent = \Stripe\SetupIntent::retrieve($setupIntentId);
        $paymentMethodId = $setupIntent->payment_method;
        
        if(!$paymentMethodId){
            return response()->json([
                'success' => false,
                'message' => 'Payment method not found.'
            ], 400);
        }
        
        try {
            $order = Orders::find($orderId);
            if(!$order || $order->status != Orders::STATUS_INITIATED){
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or already processed.'
                ], 400);
            }
            
            $user = Auth::user();
            if(!$user){
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }
            
            // NOTE: Organization creation moved to processSubscriptionCompletion
            // to happen AFTER successful payment confirmation
            
            $order->user_id = $user->id;
            $order->save();
            
            // Set as default payment method (already attached by SetupIntent)
            \Stripe\Customer::update($user->stripe_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId
                ]
            ]);
            
            // Get order items and create subscription items
            $orderItems = OrderItems::where('order_id', $order->id)->get();
            $totalQty = $orderItems->sum('qty');
            $subscriptionItems = [];
            
            foreach($orderItems as $oi){
                if($oi->item_type != OrderItems::ITEM_TYPE_SUBSCRIPTION){
                    continue;
                }
                
                $product = Products::where('category', 1)->find($oi->item_id);
                if(!$product) continue;
                
                $option = CurriculumPrice::getOptionForRange($product->id, $totalQty);
                if(!$option || !$option->stripe_price_id) continue;
                
                $subscriptionItems[] = [
                    'price' => $option->stripe_price_id,
                    'quantity' => $oi->qty,
                ];
            }
            
            if(empty($subscriptionItems)){
                return response()->json([
                    'success' => false,
                    'message' => 'No valid subscription items found.'
                ], 400);
            }
            
            // Create subscription with immediate payment
            $subscriptionParams = [
                'customer' => $user->stripe_id,
                'items' => $subscriptionItems,
                'default_payment_method' => $paymentMethodId,
                'payment_behavior' => 'error_if_incomplete',
                'payment_settings' => [
                    'payment_method_types' => ['card'],
                    'save_default_payment_method' => 'on_subscription'
                ],
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'org_id' => $user->org_id,
                ]
            ];
            
            $subscription = \Stripe\Subscription::create($subscriptionParams);
            
            // Store subscription ID in order
            $order->stripe_subscription_id = $subscription->id;
            $order->save();
            
            // Check if payment requires action
            $latestInvoice = $subscription->latest_invoice;
            $requiresAction = false;
            $clientSecret = null;
            
            if($latestInvoice && $latestInvoice->payment_intent){
                $paymentIntent = $latestInvoice->payment_intent;
                
                if($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_payment_method'){
                    $requiresAction = true;
                    $clientSecret = $paymentIntent->client_secret;
                }
            }
            
            $successUrl = url(get_path($path).'/dashboard/assign-access');
            
            if($requiresAction){
                return response()->json([
                    'success' => true,
                    'requires_action' => true,
                    'client_secret' => $clientSecret,
                    'success_url' => $successUrl
                ]);
            }
            
            // Process the subscription immediately if no action required
            $completionResult = $this->processSubscriptionCompletion($order, $subscription, $user, $path);
            
            // Check if org creation is needed
            if($completionResult && isset($completionResult['needs_org_creation'])){
                return response()->json([
                    'success' => true,
                    'requires_action' => false,
                    'redirect_to_org_creation' => true,
                    'success_url' => url(get_path($path).'/subscription/create-organization')
                ]);
            }
            
            return response()->json([
                'success' => true,
                'requires_action' => false,
                'success_url' => $successUrl
            ]);
            
        } catch (\Exception $e) {
            Log::error('Subscription payment error: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Helper method to process subscription completion
     * Handles all post-payment actions including role assignment, org management, and access grants
     * Returns array with status info, or null on success
     */
    private function processSubscriptionCompletion($order, $subscription, $user, $path = 'educators')
    {
        // Mark order as completed
        $order->status = Orders::STATUS_PAYMENT_SUCCES;
        $order->save();
        
        // ACTION 1: Assign "head" role to the user
        if(!$user->hasRole('head')){
            $user->addRole('head');
            Log::info('Assigned head role to user after subscription purchase', ['user_id' => $user->id]);
        }
        
        // ACTION 2 & 3: Ensure user has valid organization
        $orgCheckResult = $this->ensureUserHasOrganization($user);
        
        Log::info('Organization check result', [
            'result' => $orgCheckResult,
            'needs_creation' => $orgCheckResult['needs_org_creation'] ?? 'not set'
        ]);
        
        if($orgCheckResult['needs_org_creation']){
            // Store order info in session for post-org-creation processing
            session([
                'pending_subscription_completion' => [
                    'order_id' => $order->id,
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
                ],
                'email_domain' => $orgCheckResult['email_domain'],
                'user_name' => $orgCheckResult['user_name'],
                'has_pending_subscription' => true
            ]);
            
            Log::info('Returning needs_org_creation flag to trigger redirect', [
                'order_id' => $order->id,
                'user_id' => $user->id
            ]);
            
            // Return status to trigger redirect
            return ['needs_org_creation' => true];
        }
        
        // Reload user to get updated org_id
        $user->refresh();
        
        // Calculate quantities purchased per product
        $purchasedQuantities = [];
        $orderItems = OrderItems::where('order_id', $order->id)->get();
        foreach($orderItems as $oi){
            if($oi->item_type == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                $purchasedQuantities[$oi->item_id] = $oi->qty;
            }
        }
        
        // Grant access to subscriptions
        foreach($subscription->items->data as $item){
            // Find the product by stripe price ID
            $priceOption = \App\Models\CurriculumPrice::where('stripe_price_id', $item->price->id)->first();
            if(!$priceOption) continue;
            
            $productId = $priceOption->product_id;
            $qtyPurchased = $purchasedQuantities[$productId] ?? 1;
            
            // Calculate expiration date (1 year from now if current_period_end is not set)
            $expirationDate = $subscription->current_period_end 
                ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)
                : \Carbon\Carbon::now()->addYear();
            
            // Create subscription record for this user
            \App\Models\Subscriptions::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ],
                [
                    'subscription_id' => $subscription->id,
                    'active' => 1,
                    'exp_date' => $expirationDate,
                    'order_id' => $order->id,
                ]
            );
            
            // ACTION 4: Manage active subscription capacity for organization FIRST
            // This creates the ActiveSubscriptions record with total and used=0
            $this->manageActiveSubscriptionCapacity($user->org_id, $productId, $qtyPurchased);
            
            // Grant product access to the purchasing user AFTER capacity tracking
            // This will increment the 'used' count in ActiveSubscriptions
            \App\Models\ProductAssignments::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'category' => 1,
                ],
                [
                    'subscription_id' => $subscription->id,
                    'active' => 1,
                ]
            );
            
            // Increment the used count since we just assigned to the purchasing user
            $activeSub = \App\Models\ActiveSubscriptions::where('org_id', $user->org_id)
                ->where('product_id', $productId)
                ->where('category', 1)
                ->first();
            if($activeSub){
                $activeSub->used += 1;
                $activeSub->save();
                
                Log::info('Incremented used count after assigning to purchasing user', [
                    'org_id' => $user->org_id,
                    'product_id' => $productId,
                    'total' => $activeSub->total,
                    'used' => $activeSub->used
                ]);
            }
        }
        
        // Clear session
        session()->forget(['order_id', 'subscriber', 'discount']);
        
        // Return null to indicate successful completion
        return null;
    }
    
    /**
     * ACTION 2 & 3: Ensure user has a valid organization
     * Returns array with 'needs_org_creation' flag and org details
     */
    private function ensureUserHasOrganization($user)
    {
        // Log initial state
        Log::info('Checking organization for user', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'current_org_id' => $user->org_id
        ]);
        
        // Check if user already has an org_id
        if($user->org_id && $user->org_id > 0){
            Log::info('User already has organization', ['org_id' => $user->org_id]);
            return ['needs_org_creation' => false, 'org_id' => $user->org_id];
        }
        
        // User has no org_id - check for email domain match
        $emailDomain = $this->extractEmailDomain($user->email);
        
        Log::info('Extracted email domain', [
            'email' => $user->email,
            'domain' => $emailDomain
        ]);
        
        if($emailDomain){
            $matchingOrg = \App\Models\Organizations::where('email_match', $emailDomain)->first();
            
            if($matchingOrg){
                // Found matching organization - assign user to it
                $user->org_id = $matchingOrg->id;
                $user->save();
                
                Log::info('Assigned user to existing organization by email domain', [
                    'user_id' => $user->id,
                    'org_id' => $matchingOrg->id,
                    'org_name' => $matchingOrg->name,
                    'email_domain' => $emailDomain
                ]);
                
                return ['needs_org_creation' => false, 'org_id' => $matchingOrg->id];
            }
        }
        
        // No matching org found - user needs to create one
        Log::info('User needs to create organization - no match found', [
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
    
    /**
     * ACTION 4: Manage active subscription capacity for organization
     * Handles total/used tracking and capacity management
     */
    private function manageActiveSubscriptionCapacity($orgId, $productId, $qtyPurchased)
    {
        $activeSubscription = \App\Models\ActiveSubscriptions::where('org_id', $orgId)
            ->where('product_id', $productId)
            ->where('category', 1)
            ->first();
        
        if($activeSubscription){
            // Existing subscription - update capacity
            $currentTotal = $activeSubscription->total ?? 0;
            $currentUsed = $activeSubscription->used ?? 0;
            $newTotal = $currentTotal + $qtyPurchased;
            
            // Update status to active and increase total
            $activeSubscription->status = 1; // active
            $activeSubscription->total = $newTotal;
            $activeSubscription->save();
            
            Log::info('Updated active subscription capacity', [
                'org_id' => $orgId,
                'product_id' => $productId,
                'previous_total' => $currentTotal,
                'new_total' => $newTotal,
                'used' => $currentUsed,
                'purchased_qty' => $qtyPurchased
            ]);
            
            // Check if used exceeds total after purchase
            if($currentUsed > $newTotal){
                $excessUsers = $currentUsed - $newTotal;
                
                Log::warning('Active subscription capacity exceeded after purchase', [
                    'org_id' => $orgId,
                    'product_id' => $productId,
                    'total' => $newTotal,
                    'used' => $currentUsed,
                    'excess_users' => $excessUsers
                ]);
                
                // Store warning in session for display
                session([
                    'capacity_warning' => [
                        'product_id' => $productId,
                        'total' => $newTotal,
                        'used' => $currentUsed,
                        'excess' => $excessUsers,
                        'message' => "You have {$excessUsers} more users assigned than your total capacity. Please unassign users to match your subscription limit."
                    ]
                ]);
            }
        } else {
            // New subscription for this product - create active subscription
            \App\Models\ActiveSubscriptions::create([
                'org_id' => $orgId,
                'product_id' => $productId,
                'category' => 1,
                'status' => 1, // active
                'total' => $qtyPurchased,
                'used' => 0,
            ]);
            
            Log::info('Created new active subscription', [
                'org_id' => $orgId,
                'product_id' => $productId,
                'total' => $qtyPurchased
            ]);
        }
    }

    public function update_subs_list(Request $req){
        $totalQuantity = 0;
        if(!session('order_id') || !$req->input('qty')){
            return response()->json(['error'=>true, 'message'=>'No order or quantities.']);
        }

        $order = Orders::find(session('order_id'));
        if(!$order){
            return response()->json(['error'=>true, 'message'=>'No order or quantities.']);
        }

        $orderItems = OrderItems::where('order_id', $order->id)->get();

        foreach($orderItems as $oi){
            if(isset($req->input('qty')[$oi->id])){
                $totalQuantity += $req->input('qty')[$oi->id];
            }else{
                $totalQuantity += $oi->qty;
            }
        }
        //update quantities
        $rows = [];
        $total = 0;
        $total_standard = 0;
        foreach($orderItems as $oi){
            $qty = isset($req->input('qty')[$oi->id])? $req->input('qty')[$oi->id] : $oi->qty;
            if($qty <= 0){
                //remove this order item
                $oi->delete();
            }else{
                //adjust qty
                $sum = $oi->price;
                if($oi->item_type == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                    $package = Products::where('category', 1)->find($oi->item_id);
                    if(!$package) continue;
                    
                    $option = CurriculumPrice::getOptionForRange($package->id, $totalQuantity);
                    if(!$option) continue;
                    
                    $price = $option->discount_price * $qty;
                    $oi->qty = $qty;
                    $oi->price = $price;
                    $oi->save();
                    $total += $price;
                    $standard = $option->standard_price * $qty;
                    $total_standard += $standard;
                    $rows[] = [
                        'id'=>$oi->id,
                        'product'=>$package->name." - ".$option->package_caption,
                        'slug'=>$package->slug,
                        'price'=>$option->discount_price,
                        'total_discount'=>$standard - $price,
                        'standard'=>$option->standard_price,
                        'discount'=>$option->standard_price - $option->discount_price,
                        'qty'=>$oi->qty,
                        'type'=> OrderItems::ITEM_TYPE_SUBSCRIPTION
                    ];
                }else if($oi->item_type == OrderItems::ITEM_TYPE_TRAINING){
                    $training = Products::where('category', 7)->find($oi->item_id);
                    if(!$training) continue;
                    
                    $price = $training->price * $qty;
                    $oi->qty = $qty;
                    $oi->price = $price;
                    $oi->save();
                    $sum = $training->price * $oi->qty;
                    $standard = $training->price * $oi->qty;
                    $total += $sum;
                    $total_standard += $standard;
                    $rows[] = [
                        'id'=>$oi->id,
                        'product'=>$training->name,
                        'slug'=>$training->slug,
                        'price'=>$training->price,
                        'total_discount'=>$standard - $sum,
                        'standard'=>$training->price,
                        'discount'=>$training->price,
                        'qty'=>$oi->qty,
                        'type'=> OrderItems::ITEM_TYPE_TRAINING
                    ];
                }
            }
        }
        //update order total
        $order->total = $total;
        $order->save();
        
        Log::info('Update subs list response', [
            'total' => $total,
            'rows_count' => count($rows),
            'rows' => $rows
        ]);
        
        //return updated quantities & total price
        return response()->json(['error'=>false, 'data'=>$rows, 'total'=> $total ]);
    }

    /**
     * Handle successful subscription checkout
     */
    public function subscriptionSuccess(Request $request, $path = 'educators')
    {
        $sessionId = $request->get('session_id');
        
        if(!$sessionId){
            return redirect()->to(get_path($path).'/cart_subscriptions')
                ->with('error', 'Invalid checkout session.');
        }

        try {
            // Retrieve the Stripe session
            $session = StripeSession::retrieve($sessionId);
            Log::info('Stripe Checkout session status', [
                'session_id' => $sessionId,
                'status' => $session->status ?? null,
                'payment_status' => $session->payment_status ?? null,
            ]);
            
            // Accept completed sessions even if no immediate payment (e.g. trials)
            $isComplete = isset($session->status) && $session->status === 'complete';
            $isPaid = isset($session->payment_status) && $session->payment_status === 'paid';
            $isNoPaymentRequired = isset($session->payment_status) && $session->payment_status === 'no_payment_required';

            if(!$isComplete && !$isPaid && !$isNoPaymentRequired){
                return redirect()->to(get_path($path).'/cart_subscriptions')
                    ->with('error', 'Checkout did not complete.');
            }

            $orderId = $session->metadata->order_id ?? null;
            if(!$orderId){
                // Fallback: locate order by stored Stripe session id
                $orderBySession = Orders::where('stripe_session_id', $sessionId)->first();
                if($orderBySession){
                    $orderId = $orderBySession->id;
                } else {
                    return redirect()->to(get_path($path).'/cart_subscriptions')
                        ->with('error', 'Order information not found.');
                }
            }

            $order = Orders::find($orderId);
            if(!$order){
                return redirect()->to(get_path($path).'/cart_subscriptions')
                    ->with('error', 'Order not found.');
            }

            // Update order status
            $order->status = Orders::STATUS_PAYMENT_SUCCES;
            $order->stripe_session_id = $sessionId;
            $order->subscription_id = $session->subscription;
            $order->save();

            // Grant access to subscribed products
            $orderItems = OrderItems::where('order_id', $order->id)->get();
            foreach($orderItems as $item){
                if($item->item_type == OrderItems::ITEM_TYPE_SUBSCRIPTION){
                    // Check if subscription ID exists before granting access
                    if($session->subscription){
                        $this->grantSubscriptionAccess($order->user_id, $item->item_id, $session->subscription);
                    } else {
                        Log::warning("Subscription ID not found in Stripe session for order {$order->id}. Will be processed by webhook.");
                    }
                }
            }

            // Clear the order session
            session()->forget('order_id');

            // Redirect to assign-access page (all purchasers are assigned 'head' role)
            return redirect()->to(get_path($path).'/dashboard/assign-access')
                ->with('success', 'Thank you! Your subscription is now active.');

        } catch (\Exception $e) {
            Log::error('Subscription success handler error', [
                'message' => $e->getMessage(),
                'session_id' => $sessionId ?? null,
            ]);
            // Clear the order session anyway to avoid items lingering in cart
            session()->forget('order_id');
            // Redirect to assign-access page (all purchasers are assigned 'head' role)
            return redirect()->to(get_path($path).'/dashboard/assign-access')
                ->with('success', 'Thank you! Your payment was received. We\'re finalizing your subscription.');
        }
    }

    /**
     * Grant subscription access to a user
     */
    private function grantSubscriptionAccess($userId, $productId, $stripeSubscriptionId)
    {
        $user = User::find($userId);
        if(!$user){
            Log::error("User not found: {$userId}");
            return;
        }

        // Validate that we have a subscription ID
        if(!$stripeSubscriptionId){
            Log::error("Stripe subscription ID is null for user {$userId}, product {$productId}");
            return;
        }

        try {
            // Retrieve the Stripe subscription to get accurate expiration date
            $stripeSubscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
            
            // Validate that current_period_end exists
            if(!isset($stripeSubscription->current_period_end) || !$stripeSubscription->current_period_end){
                Log::error("Stripe subscription {$stripeSubscriptionId} has no current_period_end");
                return;
            }
            
            // Get the current period end (next renewal date)
            $expirationDate = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            
            // Create subscription record with Stripe's expiration date
            \App\Models\Subscriptions::updateOrCreate(
                [
                    'user_id' => $userId,
                    'product_id' => $productId,
                ],
                [
                    'subscription_id' => $stripeSubscriptionId,
                    'active' => 1,
                    'exp_date' => $expirationDate,
                    'order_id' => session('order_id'),
                ]
            );

            // Grant product access via product_assignments
            $existing = \App\Models\ProductAssignments::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('category', 1)
                ->first();

            if(!$existing){
                \App\Models\ProductAssignments::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'category' => 1,
                    'subscription_id' => $stripeSubscriptionId,
                    'active' => 1,
                ]);
            } else {
                $existing->subscription_id = $stripeSubscriptionId;
                $existing->active = 1;
                $existing->save();
            }

            // Create or update active subscription for organization
            \App\Models\ActiveSubscriptions::updateOrCreate(
                [
                    'org_id' => $user->org_id,
                    'product_id' => $productId,
                ],
                [
                    'category' => 1,
                    'status' => 1,
                ]
            );
            
        } catch (\Exception $e) {
            Log::error("Error granting subscription access: " . $e->getMessage());
            throw $e;
        }
    }

    //store chosen subscription for buying
    public function subscription_checkout(Request $request, $path = 'educators'){
        if(!Auth::check()){
            //show login screen
            session(['subscriber'=> true]);
            return redirect()->to($path.'/cart_sub_login')
                ->with('section3', 'cart')
                ->with('section3', 'login')
                ->with('path', get_path($path));
        }

        if(!session('order_id')){
            return redirect()->to($path.'/cart_subscriptions')
                ->with('error', 'No subscription order found.');
        }

        $order = Orders::find(session('order_id'));
        if(!$order || $order->total == 0){
            return redirect()->to($path.'/cart_subscriptions')
                ->with('error', 'Your subscription cart is empty.');
        }

        // Process discount code if provided
        if($request->input('discount')){
            $discount = Discounts::where('code', 'LIKE', $request->input('discount'))
                ->where('available', Discounts::DISCOUNT_AVAILABLE)
                ->first();
            
            if($discount){
                $available = $discount->available;
                if($available != 0){
                    $available--;
                    if($available == 0){
                        $discount->status = Discounts::DISCOUNT_APPLIED;
                    }
                }
                $discount->available = $available;
                $discount->order_id = $order->id;
                $discount->save();
                
                // Store discount in session for payment page
                session([
                    'discount' => [
                        'code' => $discount->code,
                        'type' => $discount->discount_type,
                        'value' => $discount->value
                    ]
                ]);
            }
        }
        // Show Stripe Elements payment page instead of redirecting to Checkout
        return $this->showPaymentPage($path);
    }

    //cart details (login)
    public function cart_sub_login($path = 'educators'){
        $cart_id = (int) session('cart_id');
        //if($cart_id == 0 || !$cart = Cart::find($cart_id))
        //    abort(404);
        $cart_id = (int) session('cart_id');
        $user_id = Auth::check() ? Auth::user()->id : 0;
        return view('store.cart.cart_login')
            ->with('section3', 'cart')
            ->with('section3', 'login')
            ->with('path', get_path($path));
    }

    // Public endpoint for processing order after login
    public function process_order_public($path = 'educators'){
        // Show the new Stripe Elements payment page instead of redirecting to Checkout
        return $this->showPaymentPage($path);
    }

    // LEGACY METHOD REMOVED - Now using Stripe Elements instead of Checkout
    // All subscription payments now use showPaymentPage() and subscription_payment_process()

    //checks validity of a discount code
    public function check_discount(Request $req, $path = 'educators'){
        if(!$req->input('code') || strlen($req->input('code')) == 0){
            $applied = [
                'code'=>'',
                'value'=>'',
                'new_total'=>$req->input('current')
            ];
            $req->session()->forget('discount');
            return response()->json(['error'=>false, 'discount'=>$applied]);
        }
        $discount = \App\Models\Discounts::where('code', 'LIKE', $req->input('code'))->where('status', \App\Models\Discounts::DISCOUNT_AVAILABLE)->first();
        if(!$discount){
            $req->session()->forget('discount');
            return response()->json(['error'=>true, 'message'=>'Invalid Discount Code']);
        }
        if($discount->expire_date != null && strtotime($discount->expire_date) < time()){
            $req->session()->forget('discount');
            return response()->json(['error'=>true, 'message'=>'Discount Code is Expired']);
        }
        if($discount->discount_type == \App\Models\Discounts::TYPE_FIXED){
            $value = "$".$discount->value;
            $new_total = $req->input('current') - $discount->value;
            if($new_total < 1){
                $req->session()->forget('discount');
                return response()->json(['error'=>true, 'message'=>'Discount Code Value is greater than your order!']);
            }
        }else{
            $value = $discount->value."%";
            $new_total = $req->input('current') - (($req->input('current') / 100) * $discount->value);
        }
        $applied = [
            'code'=>$discount->code,
            'value'=>$value,
            'new_total'=> number_format($new_total, 2)
        ];
        return response()->json(['error'=>false, 'discount'=>$applied]);
    }

    /**
     * Show organization creation page (for post-subscription purchase)
     */
    public function showCreateOrganization($path = 'educators')
    {
        if(!auth()->check()){
            return redirect(url($path.'/login'));
        }
        
        $user = auth()->user();
        
        // If user already has an org, redirect to dashboard
        if($user->org_id && $user->org_id > 0){
            return redirect(url($path.'/dashboard'))->with('success', 'You already have an organization.');
        }
        
        // Check if there's a pending subscription completion
        $hasPendingSubscription = session()->has('pending_subscription_completion');
        
        return view('backend.users.org-add-by-user')
            ->with('path', get_path($path))
            ->with('has_pending_subscription', $hasPendingSubscription);
    }
    
    /**
     * Store organization and continue subscription completion if pending
     */
    public function storeOrganization(Request $req, $path = 'educators')
    {
        if(!auth()->check()){
            return redirect(url($path.'/login'));
        }
        
        $user = auth()->user();
        
        // Validate organization data
        try{
            $req->validate([
                'name' => 'bail|required|min:3',
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
        
        // Validate email_match if provided (prevent public domains)
        if($req->input('email_match')){
            $emailMatch = strtolower(trim($req->input('email_match')));
            $blockedDomains = ['gmail.com', 'yahoo.com', 'yahoo.co.uk', 'hotmail.com', 'outlook.com', 'aol.com', 'icloud.com', 'protonmail.com', 'mail.com'];
            
            if(in_array($emailMatch, $blockedDomains)){
                return back()->withErrors([
                    'email_match' => 'Cannot use public email domains (' . $emailMatch . ') for organization mapping.'
                ])->withInput();
            }
            
            // Check for duplicate email_match
            $existingOrg = \App\Models\Organizations::where('email_match', $emailMatch)->first();
            if($existingOrg){
                return back()->withErrors([
                    'email_match' => 'An organization with this email domain already exists: ' . $existingOrg->name
                ])->withInput();
            }
        }
        
        // Create the organization
        $org = new \App\Models\Organizations();
        $org->name = $req->input('name');
        
        if($req->input('address')) $org->address = $req->input('address');
        if($req->input('city')) $org->city = $req->input('city');
        if($req->input('state')) $org->state = $req->input('state');
        if($req->input('province')) $org->state = $req->input('province'); // Province goes to state field
        if($req->input('zip')) $org->zip = $req->input('zip');
        if($req->input('country')) $org->country = $req->input('country');
        if($req->input('url')) $org->url = $req->input('url');
        if($req->input('email_match')) $org->email_match = $req->input('email_match');
        if($req->input('grades')) $org->grades = $req->input('grades');
        if($req->input('size')) $org->size = $req->input('size');
        if($req->input('pedagogy')) $org->pedagogy = $req->input('pedagogy');
        
        $org->save();
        
        // Assign organization to user
        $user->org_id = $org->id;
        $user->save();
        
        Log::info('User created organization after subscription purchase', [
            'user_id' => $user->id,
            'org_id' => $org->id,
            'org_name' => $org->name
        ]);
        
        // Check if there's a pending subscription completion
        if(session()->has('pending_subscription_completion')){
            $pendingData = session('pending_subscription_completion');
            
            // Retrieve the order and subscription
            $order = \App\Models\Orders::find($pendingData['order_id']);
            
            if($order){
                // Get Stripe subscription
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                
                try{
                    $subscription = $stripe->subscriptions->retrieve($pendingData['subscription_id']);
                    
                    // Now complete the subscription processing (pass path parameter)
                    $this->processSubscriptionCompletion($order, $subscription, $user, $path);
                    
                    // Clear the pending session data
                    session()->forget(['pending_subscription_completion', 'email_domain', 'user_name', 'has_pending_subscription']);
                    
                    return redirect(url($path.'/dashboard/assign-access'))
                        ->with('success', 'Organization created and subscription activated successfully!');
                        
                } catch(\Exception $e){
                    Log::error('Error completing subscription after org creation', [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id
                    ]);
                    
                    return redirect(url($path.'/dashboard'))
                        ->with('warning', 'Organization created, but there was an issue completing your subscription. Please contact support.');
                }
            }
        }
        
        // No pending subscription - just redirect to dashboard
        return redirect(url($path.'/dashboard'))
            ->with('success', 'Organization created successfully!');
    }

    //Return from Stripe
    public function subscribed($path = 'educators') {
        return view('store.cart.exit_subscribed')
        ->with('path', get_path($path));
    }
}
