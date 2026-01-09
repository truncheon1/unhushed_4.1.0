<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDonation;

class CartDonateController extends Controller
{
    /**
     * Show donation success page
     */
    public function donated($path = 'educators') {
        return view('store.cart.exit_donated')
            ->with('path', get_path($path));
    }

    /**
     * Handle donation checkout with Stripe
     * Creates PaymentIntent for one-time or Subscription for recurring
     */
    public function donate_checkout(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'recurring' => 'boolean',
            'payment_method_id' => 'required|string',
            'anonymous' => 'boolean',
            'message' => 'nullable|string|max:1000'
        ]);

        $amount = $validated['amount'];
        $recurring = $validated['recurring'] ?? false;
        $paymentMethodId = $validated['payment_method_id'];
        $anonymous = $validated['anonymous'] ?? false;
        $message = $validated['message'] ?? null;

        // Check authentication requirements
        // Recurring donations OR non-anonymous donations require login
        if (($recurring || !$anonymous) && !Auth::check()) {
            return response()->json([
                'success' => false,
                'requires_auth' => true,
                'message' => 'Please log in or create an account to continue.'
            ], 401);
        }

        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');

            if ($recurring) {
                // Create recurring donation subscription
                return $this->createRecurringDonation($amount, $paymentMethodId, $anonymous, $message);
            } else {
                // Create one-time donation
                return $this->createOneTimeDonation($amount, $paymentMethodId, $anonymous, $message);
            }
        } catch (\Exception $e) {
            Log::error('Donation checkout error', [
                'error' => $e->getMessage(),
                'amount' => $amount,
                'recurring' => $recurring
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to process donation. Please try again.'
            ], 500);
        }
    }

    /**
     * Create a one-time donation PaymentIntent
     */
    private function createOneTimeDonation($amount, $paymentMethodId, $anonymous = false, $message = null)
    {
        $returnUrl = url('/educators/dashboard/my-donations'); // Default to educators path
        
        // Try to get the actual path from the authenticated user or request
        if (Auth::check()) {
            $user = Auth::user();
            $userPath = session('path', 'educators'); // Get from session if available
            $returnUrl = url("/{$userPath}/dashboard/my-donations");
        }
        
        // Create donation record - user_id = 0 for anonymous, otherwise use Auth::id()
        $donation = UserDonation::create([
            'user_id' => $anonymous ? 0 : (Auth::id() ?? 0),
            'amount' => $amount,
            'recurring' => false,
            'status' => UserDonation::STATUS_PENDING,
            'payment_method' => 'card',
            'payment_method_id' => $paymentMethodId,
            'message' => $message,
        ]);
        
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => round($amount * 100), // Convert to cents
            'currency' => 'usd',
            'payment_method' => $paymentMethodId,
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => $returnUrl,
            'description' => 'UN|HUSHED One-time Donation',
            'metadata' => [
                'type' => 'one-time-donation',
                'organization' => 'UNHUSHED',
                'donation_id' => $donation->id,
                'anonymous' => $anonymous ? 'yes' : 'no'
            ],
            'receipt_email' => (!$anonymous && Auth::check()) ? Auth::user()->email : null,
        ]);

        // Update donation with payment_id
        $donation->payment_id = $paymentIntent->id;
        
        // Retrieve payment method details from Stripe
        try {
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
            if ($paymentMethod && $paymentMethod->card) {
                $cardBrand = ucfirst($paymentMethod->card->brand); // Visa, Mastercard, etc.
                $last4 = $paymentMethod->card->last4;
                $wallet = $paymentMethod->card->wallet ? $paymentMethod->card->wallet->type : null; // apple_pay, google_pay, etc.
                
                if ($wallet) {
                    $donation->payment_method = ucwords(str_replace('_', ' ', $wallet)); // "Apple Pay", "Google Pay"
                } else {
                    $donation->payment_method = $cardBrand . ' •••• ' . $last4;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to retrieve payment method details', ['error' => $e->getMessage()]);
        }
        
        // Check if payment succeeded immediately
        if ($paymentIntent->status === 'succeeded') {
            $donation->markCompleted();
            
            // Tag donor in ActiveCampaign (only if not anonymous and authenticated)
            if (!$anonymous && Auth::check()) {
                try {
                    $user = Auth::user();
                    if ($user && $user->email) {
                        $acService = new \App\Services\ActiveCampaignService();
                        $acService->tagDonor($user);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to tag donor in ActiveCampaign', [
                        'user_id' => Auth::id(),
                        'donation_id' => $donation->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        
        $donation->save();

        // Check if additional action is required (3D Secure)
        if ($paymentIntent->status === 'requires_action' && 
            $paymentIntent->next_action->type === 'use_stripe_sdk') {
            return response()->json([
                'success' => true,
                'requires_action' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'donation_id' => $donation->id
            ]);
        }

        // Payment succeeded
        return response()->json([
            'success' => true,
            'requires_action' => false,
            'payment_intent_id' => $paymentIntent->id,
            'donation_id' => $donation->id
        ]);
    }

    /**
     * Create a recurring donation subscription with Stripe Elements
     */
    private function createRecurringDonation($amount, $paymentMethodId, $anonymous = false, $message = null)
    {
        // Recurring donations require authentication
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'requires_auth' => true,
                'message' => 'You must be logged in to create a recurring donation.'
            ], 401);
        }
        
        $user = Auth::user();
        
        // Get or create Stripe customer
        $customer = null;
        if ($user->stripe_id) {
            try {
                // Try to retrieve existing customer
                $customer = \Stripe\Customer::retrieve($user->stripe_id);
                
                // Check if customer was deleted
                if (isset($customer->deleted) && $customer->deleted === true) {
                    $customer = null;
                }
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Customer not found, create new one
                Log::warning('Stripe customer not found, creating new', [
                    'user_id' => $user->id,
                    'old_stripe_id' => $user->stripe_id
                ]);
                $customer = null;
            }
        }
        
        // Create new customer if needed
        if (!$customer) {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id
                ]
            ]);
            $user->stripe_id = $customer->id;
            $user->save();
        }

        // Attach payment method to customer using instance method
        $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach([
            'customer' => $customer->id
        ]);

        // Set as default payment method
        \Stripe\Customer::update($customer->id, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId
            ]
        ]);

        // Create or retrieve donation product
        $products = \Stripe\Product::search([
            'query' => 'metadata["type"]:"donation" AND active:"true"',
        ]);

        if (count($products->data) > 0) {
            $product = $products->data[0];
        } else {
            $product = \Stripe\Product::create([
                'name' => 'UN|HUSHED Monthly Donation',
                'description' => 'Sex Ed Saves Lives - Monthly Recurring Donation',
                'metadata' => [
                    'type' => 'donation',
                    'organization' => 'UNHUSHED'
                ]
            ]);
        }

        // Create a price for this donation amount
        $price = \Stripe\Price::create([
            'product' => $product->id,
            'unit_amount' => round($amount * 100), // Convert to cents
            'currency' => 'usd',
            'recurring' => [
                'interval' => 'month',
                'interval_count' => 1,
            ],
            'metadata' => [
                'donation_amount' => $amount
            ]
        ]);

        // Create subscription
        $returnUrl = url('/educators/dashboard/my-donations'); // Default to educators path
        $userPath = session('path', 'educators'); // Get from session if available
        $returnUrl = url("/{$userPath}/dashboard/my-donations");
        
        $subscription = \Stripe\Subscription::create([
            'customer' => $customer->id,
            'items' => [[
                'price' => $price->id,
            ]],
            'default_payment_method' => $paymentMethodId,
            'payment_behavior' => 'error_if_incomplete',
            'payment_settings' => [
                'payment_method_types' => ['card'],
                'save_default_payment_method' => 'on_subscription',
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic'
                    ]
                ]
            ],
            'expand' => ['latest_invoice.payment_intent'],
            'metadata' => [
                'type' => 'donation',
                'amount' => $amount,
                'return_url' => $returnUrl,
                'anonymous' => $anonymous ? 'yes' : 'no',
                'user_id' => $user->id
            ]
        ]);

        // Create initial donation record for first payment - user_id = 0 for anonymous
        $donation = UserDonation::create([
            'user_id' => $anonymous ? 0 : $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $amount,
            'recurring' => true,
            'status' => UserDonation::STATUS_PENDING,
            'payment_method' => 'card',
            'payment_method_id' => $paymentMethodId,
            'message' => $message,
        ]);

        // Check if payment requires action (3D Secure)
        $latestInvoice = $subscription->latest_invoice;
        
        if ($latestInvoice && $latestInvoice->payment_intent) {
            $paymentIntent = $latestInvoice->payment_intent;
            
            // Update donation with payment_id
            $donation->payment_id = $paymentIntent->id;
            
            // Retrieve payment method details from Stripe
            try {
                $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
                if ($paymentMethod && $paymentMethod->card) {
                    $cardBrand = ucfirst($paymentMethod->card->brand);
                    $last4 = $paymentMethod->card->last4;
                    $wallet = $paymentMethod->card->wallet ? $paymentMethod->card->wallet->type : null;
                    
                    if ($wallet) {
                        $donation->payment_method = ucwords(str_replace('_', ' ', $wallet)); // "Apple Pay", "Google Pay"
                    } else {
                        $donation->payment_method = $cardBrand . ' •••• ' . $last4;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to retrieve payment method details', ['error' => $e->getMessage()]);
            }
            
            // Check if payment succeeded immediately
            if ($paymentIntent->status === 'succeeded') {
                $donation->markCompleted();
                
                // Tag donor in ActiveCampaign (only if not anonymous)
                if (!$anonymous) {
                    try {
                        if ($user && $user->email) {
                            $acService = new \App\Services\ActiveCampaignService();
                            $acService->tagDonor($user);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to tag donor in ActiveCampaign', [
                            'user_id' => $user->id,
                            'donation_id' => $donation->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
            
            $donation->save();
            
            // Update PaymentIntent with return_url if needed
            if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_payment_method') {
                try {
                    \Stripe\PaymentIntent::update($paymentIntent->id, [
                        'return_url' => $returnUrl
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Could not update PaymentIntent return_url', ['error' => $e->getMessage()]);
                }
                
                return response()->json([
                    'success' => true,
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                    'subscription_id' => $subscription->id,
                    'donation_id' => $donation->id
                ]);
            }
        }

        // Subscription created successfully - mark donation as completed
        $donation->markCompleted();
        $donation->save();
        
        return response()->json([
            'success' => true,
            'requires_action' => false,
            'subscription_id' => $subscription->id,
            'donation_id' => $donation->id
        ]);
    }
    
    /**
     * Cancel a recurring donation subscription
     */
    public function cancelSubscription(Request $request, $path)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|string',
            'donation_id' => 'required|integer'
        ]);
        
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }
        
        try {
            // Verify the donation belongs to the authenticated user
            $donation = UserDonation::where('id', $validated['donation_id'])
                ->where('user_id', Auth::id())
                ->where('subscription_id', $validated['subscription_id'])
                ->firstOrFail();
            
            // Cancel the subscription in Stripe
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');
            
            $subscription = \Stripe\Subscription::retrieve($validated['subscription_id']);
            $subscription->cancel();
            
            // Update donation status
            $donation->status = UserDonation::STATUS_REFUNDED;
            $donation->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription canceled successfully'
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe subscription cancellation failed', [
                'subscription_id' => $validated['subscription_id'],
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found or already canceled'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Subscription cancellation failed', [
                'subscription_id' => $validated['subscription_id'],
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription. Please contact support.'
            ], 500);
        }
    }
    
    /**
     * Redirect to Stripe Customer Portal for subscription management
     */
    public function manageSubscription($path = 'educators', $subscription_id = null)
    {
        // Validate path
        $path = get_path($path);
        
        // Verify subscription exists
        $donation = UserDonation::where('subscription_id', $subscription_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$donation) {
            return redirect(url("/{$path}/dashboard/my-donations"))
                ->with('error', 'Subscription not found');
        }
        
        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');
            
            // Get the customer
            $user = Auth::user();
            
            Log::info('User stripe_id check', [
                'user_id' => $user->id,
                'stripe_id' => $user->stripe_id,
                'subscription_id' => $subscription_id
            ]);
            
            if (!$user->stripe_id) {
                Log::error('User has no stripe_id', [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription_id
                ]);
                
                // Try to find the Stripe customer by email
                $customers = \Stripe\Customer::search([
                    'query' => 'email:"' . $user->email . '"',
                ]);
                
                if (count($customers->data) > 0) {
                    $customer = $customers->data[0];
                    $user->stripe_id = $customer->id;
                    $user->save();
                    
                    Log::info('Found and saved existing Stripe customer', [
                        'user_id' => $user->id,
                        'stripe_id' => $customer->id
                    ]);
                } else {
                    return redirect(url("/{$path}/dashboard/my-donations"))
                        ->with('error', 'Unable to access subscription management');
                }
            }
            
            // Create a Stripe billing portal session
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $user->stripe_id,
                'return_url' => url("/{$path}/dashboard/my-donations"),
            ]);
            
            Log::info('Redirecting to Stripe billing portal', [
                'user_id' => $user->id,
                'subscription_id' => $subscription_id,
                'session_id' => $session->id
            ]);
            
            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Failed to create billing portal session', [
                'subscription_id' => $subscription_id,
                'error' => $e->getMessage()
            ]);
            
            return redirect(url("/{$path}/dashboard/my-donations"))
                ->with('error', 'Unable to manage subscription. Please try again or contact support.');
        }
    }
}
