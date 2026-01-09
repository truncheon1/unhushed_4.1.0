<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\ActiveSubscriptions;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\User;
use App\Services\ActiveCampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        
        // Verify webhook signature if configured
        if (config('cashier.webhook.secret')) {
            try {
                \Stripe\Webhook::constructEvent(
                    $request->getContent(),
                    $request->header('Stripe-Signature'),
                    config('cashier.webhook.secret')
                );
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        }
        
        // Handle based on event type
        $method = 'handle' . str_replace('.', '', ucwords($payload['type'], '.'));
        
        if (method_exists($this, $method)) {
            return $this->$method($payload);
        }
        
        return response()->json(['status' => 'unhandled']);
    }
    
    /**
     * Handle checkout session completed (successful payment)
     */
    public function handleCheckoutSessionCompleted($payload)
    {
        $session = $payload['data']['object'];
        
        \Log::info('Webhook: checkout.session.completed', [
            'session_id' => $session['id'],
            'payment_status' => $session['payment_status'],
            'customer' => $session['customer'],
        ]);
        
        // Only process if payment succeeded
        if ($session['payment_status'] !== 'paid') {
            \Log::warning('Checkout session not paid', ['session_id' => $session['id']]);
            return;
        }
        
        // Get user from Stripe customer
        $user = User::where('stripe_id', $session['customer'])->first();
        if (!$user) {
            \Log::error('User not found for customer', ['customer_id' => $session['customer']]);
            return;
        }
        
        // Get metadata
        $metadata = $session['metadata'] ?? [];
        $productId = $metadata['product_id'] ?? null;
        $quantity = $metadata['quantity'] ?? 1;
        $type = $metadata['type'] ?? 'product';
        
        if (!$productId) {
            \Log::error('No product_id in session metadata', ['session_id' => $session['id']]);
            return;
        }
        
        $product = Products::find($productId);
        if (!$product) {
            \Log::error('Product not found', ['product_id' => $productId]);
            return;
        }
        
        // Get variant
        $variant = ProductVar::where('product_id', $product->id)->where('avail', 1)->first();
        
        // Determine purchase type
        $purchaseType = $product->category == 1 ? Purchase::TYPE_SUBSCRIPTION : 
                       ($product->category == 7 ? Purchase::TYPE_TRAINING : Purchase::TYPE_PRODUCT);
        
        // Create Purchase record
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'variant_id' => $variant ? $variant->var_id : null,
            'stripe_checkout_session_id' => $session['id'],
            'stripe_payment_intent_id' => $session['payment_intent'] ?? null,
            'type' => $purchaseType,
            'quantity' => $quantity,
            'amount' => $session['amount_total'] / 100,
            'tax' => ($session['total_details']['amount_tax'] ?? 0) / 100,
            'shipping' => ($session['total_details']['amount_shipping'] ?? 0) / 100,
            'status' => Purchase::STATUS_COMPLETED,
            'metadata' => [
                'product_name' => $product->name,
                'session_data' => $session,
            ],
            'completed_at' => now(),
        ]);
        
        \Log::info('Purchase created via webhook', ['purchase_id' => $purchase->id]);
        
        // Grant access
        if ($product->category == 1 && isset($session['subscription'])) {
            $this->grantCurriculumAccess($user, $product, $quantity, $session['subscription']);
        }
        
        // Sync to Active Campaign
        try {
            $acService = new ActiveCampaignService();
            $acService->syncUserUpdate($user, [
                'message' => 'Purchased via Stripe: ' . $product->name,
            ]);
        } catch (\Exception $e) {
            \Log::error('AC sync failed in checkout webhook', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Handle subscription created
     */
    public function handleCustomerSubscriptionCreated($payload)
    {
        $subscription = $payload['data']['object'];
        
        \Log::info('Webhook: customer.subscription.created', [
            'subscription_id' => $subscription['id'],
            'customer' => $subscription['customer'],
            'status' => $subscription['status'],
        ]);
        
        // Get user
        $user = User::where('stripe_id', $subscription['customer'])->first();
        if (!$user) {
            \Log::error('User not found for subscription', ['customer_id' => $subscription['customer']]);
            return;
        }
        
        // Get metadata
        $metadata = $subscription['metadata'] ?? [];
        $productId = $metadata['product_id'] ?? null;
        $quantity = $metadata['quantity'] ?? 1;
        
        if (!$productId) {
            \Log::warning('No product_id in subscription metadata', ['subscription_id' => $subscription['id']]);
            return;
        }
        
        // Check if already exists
        $existing = ActiveSubscriptions::where('subscription_id', $subscription['id'])->first();
        if ($existing) {
            \Log::info('ActiveSubscription already exists', ['subscription_id' => $subscription['id']]);
            return;
        }
        
        // Create ActiveSubscription record
        ActiveSubscriptions::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'subscription_id' => $subscription['id'],
            'quantity' => $quantity,
            'status' => $subscription['status'],
            'current_period_start' => \Carbon\Carbon::createFromTimestamp($subscription['current_period_start']),
            'current_period_end' => \Carbon\Carbon::createFromTimestamp($subscription['current_period_end']),
        ]);
        
        \Log::info('ActiveSubscription created', [
            'subscription_id' => $subscription['id'],
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);
        
        // Sync to Active Campaign
        try {
            $product = Products::find($productId);
            $acService = new ActiveCampaignService();
            $acService->syncUserUpdate($user, [
                'message' => 'Subscription started: ' . ($product ? $product->name : 'Product #' . $productId),
            ]);
            if ($product && $product->tag) {
                $acService->addTagByEmail($user->email, $product->tag);
            }
        } catch (\Exception $e) {
            \Log::error('AC sync failed in subscription creation webhook', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Handle invoice payment succeeded (renewals)
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        $invoice = $payload['data']['object'];
        
        \Log::info('Webhook: invoice.payment_succeeded', [
            'invoice_id' => $invoice['id'],
            'subscription' => $invoice['subscription'],
            'amount_paid' => $invoice['amount_paid'],
        ]);
        
        // Skip if not a subscription invoice
        if (!$invoice['subscription']) {
            return;
        }
        
        // Check if this is a donation subscription by fetching from Stripe
        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');
            $stripeSubscription = \Stripe\Subscription::retrieve($invoice['subscription']);
            
            // Check if this is a donation subscription
            if (isset($stripeSubscription->metadata['type']) && $stripeSubscription->metadata['type'] === 'donation') {
                // Handle donation subscription payment
                $this->handleDonationInvoicePayment($invoice, $stripeSubscription);
                return;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking subscription type: ' . $e->getMessage());
        }
        
        // Get user
        $user = User::where('stripe_id', $invoice['customer'])->first();
        if (!$user) {
            \Log::error('User not found for invoice', ['customer_id' => $invoice['customer']]);
            return;
        }
        
        // Fetch the Stripe subscription to get accurate period end
        try {
            $periodEnd = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            
            // Update our subscriptions table
            $subscription = \App\Models\Subscriptions::where('subscription_id', $invoice['subscription'])->first();
            if ($subscription) {
                $subscription->exp_date = $periodEnd;
                $subscription->active = 1;
                $subscription->save();
                
                \Log::info('Subscription exp_date updated', [
                    'subscription_id' => $invoice['subscription'],
                    'exp_date' => $periodEnd,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating subscription exp_date: ' . $e->getMessage());
        }
        
        // Update ActiveSubscription period
        $activeSubscription = ActiveSubscriptions::where('subscription_id', $invoice['subscription'])->first();
        
        if ($activeSubscription) {
            $activeSubscription->current_period_start = \Carbon\Carbon::createFromTimestamp($invoice['period_start']);
            $activeSubscription->current_period_end = \Carbon\Carbon::createFromTimestamp($invoice['period_end']);
            $activeSubscription->status = 1;
            $activeSubscription->save();
            
            \Log::info('ActiveSubscription period updated', [
                'subscription_id' => $invoice['subscription'],
                'period_end' => $activeSubscription->current_period_end,
            ]);
        }
        
        // Create Purchase record for renewal
        if ($activeSubscription) {
            Purchase::create([
                'user_id' => $user->id,
                'product_id' => $activeSubscription->product_id,
                'variant_id' => null,
                'stripe_checkout_session_id' => null,
                'stripe_payment_intent_id' => $invoice['payment_intent'],
                'type' => Purchase::TYPE_SUBSCRIPTION,
                'quantity' => $activeSubscription->quantity ?? 1,
                'amount' => $invoice['amount_paid'] / 100,
                'tax' => ($invoice['tax'] ?? 0) / 100,
                'shipping' => 0,
                'status' => Purchase::STATUS_COMPLETED,
                'metadata' => [
                    'invoice_id' => $invoice['id'],
                    'subscription_id' => $invoice['subscription'],
                    'renewal' => true,
                ],
                'completed_at' => now(),
            ]);
            
            \Log::info('Renewal Purchase created', ['invoice_id' => $invoice['id']]);
        }
    }
    
    /**
     * Handle subscription updated (cancellation, quantity changes, etc.)
     */
    public function handleCustomerSubscriptionUpdated($payload)
    {
        $subscription = $payload['data']['object'];
        
        \Log::info('Webhook: customer.subscription.updated', [
            'subscription_id' => $subscription['id'],
            'status' => $subscription['status'],
            'cancel_at_period_end' => $subscription['cancel_at_period_end'],
        ]);
        
        // Update our subscriptions table
        $dbSubscription = \App\Models\Subscriptions::where('subscription_id', $subscription['id'])->first();
        if ($dbSubscription) {
            $dbSubscription->exp_date = \Carbon\Carbon::createFromTimestamp($subscription['current_period_end']);
            $dbSubscription->active = ($subscription['status'] === 'active' && !$subscription['cancel_at_period_end']) ? 1 : 0;
            $dbSubscription->save();
        }
        
        // Update ActiveSubscription
        $activeSubscription = ActiveSubscriptions::where('subscription_id', $subscription['id'])->first();
        
        if (!$activeSubscription) {
            \Log::warning('ActiveSubscription not found for update', ['subscription_id' => $subscription['id']]);
            return;
        }
        
        // Update status
        if ($subscription['cancel_at_period_end']) {
            $activeSubscription->status = 0; // Canceling
        } else {
            $activeSubscription->status = ($subscription['status'] === 'active') ? 1 : 0;
        }
        
        // Update quantity if changed
        $quantity = $subscription['items']['data'][0]['quantity'] ?? null;
        if ($quantity && isset($activeSubscription->quantity)) {
            $activeSubscription->quantity = $quantity;
        }
        
        // Update period
        $activeSubscription->current_period_start = \Carbon\Carbon::createFromTimestamp($subscription['current_period_start']);
        $activeSubscription->current_period_end = \Carbon\Carbon::createFromTimestamp($subscription['current_period_end']);
        
        $activeSubscription->save();
        
        \Log::info('ActiveSubscription updated', [
            'subscription_id' => $subscription['id'],
            'status' => $activeSubscription->status,
            'quantity' => $activeSubscription->quantity ?? 'N/A',
        ]);
        
        // Sync to Active Campaign
        try {
            $user = User::where('stripe_id', $subscription['customer'])->first();
            if ($user) {
                $statusText = $activeSubscription->status ? 'active' : 'inactive';
                $acService = new ActiveCampaignService();
                $acService->syncUserUpdate($user, [
                    'message' => 'Subscription status updated: ' . $statusText,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('AC sync failed in subscription update webhook', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Handle subscription deleted (access revoked)
     */
    public function handleCustomerSubscriptionDeleted($payload)
    {
        $subscription = $payload['data']['object'];
        
        \Log::info('Webhook: customer.subscription.deleted', [
            'subscription_id' => $subscription['id'],
            'customer' => $subscription['customer'],
        ]);
        
        // Use cancellation service to handle all cascading updates
        $cancellationService = new \App\Services\SubscriptionCancellationService();
        $result = $cancellationService->cancelSubscription($subscription['id'], true);
        
        if ($result['success']) {
            \Log::info('Subscription deleted via webhook', $result['details']);
        } else {
            \Log::error('Failed to process subscription deletion', [
                'subscription_id' => $subscription['id'],
                'error' => $result['message']
            ]);
        }
    }
    
    /**
     * Grant curriculum access to user
     */
    private function grantCurriculumAccess($user, $product, $quantity, $subscriptionId)
    {
        \Log::info('Granting curriculum access', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'subscription_id' => $subscriptionId,
        ]);
        
        // TODO: Implement curriculum access logic
        // - Add curriculum to user's account
        // - Enable content access
        // - Send welcome email with access instructions
    }
    
    /**
     * Handle invoice payment for donation subscriptions
     */
    private function handleDonationInvoicePayment($invoice, $stripeSubscription)
    {
        $subscriptionId = $invoice['subscription'];
        $amount = $invoice['amount_paid'] / 100; // Convert from cents
        $paymentIntentId = $invoice['payment_intent'];
        
        \Log::info('Processing donation invoice payment', [
            'subscription_id' => $subscriptionId,
            'invoice_id' => $invoice['id'],
            'amount' => $amount,
        ]);
        
        // Check if this is the first invoice (existing pending donation)
        $existingDonation = UserDonation::where('subscription_id', $subscriptionId)
            ->where('status', UserDonation::STATUS_PENDING)
            ->first();
        
        if ($existingDonation) {
            // Mark the initial pending donation as completed
            $existingDonation->payment_id = $paymentIntentId;
            $existingDonation->markCompleted();
            $existingDonation->save();
            
            \Log::info('Initial donation marked as completed', [
                'donation_id' => $existingDonation->id,
                'subscription_id' => $subscriptionId,
            ]);
        } else {
            // This is a renewal - create a new donation record
            $userId = $stripeSubscription->metadata['user_id'] ?? 0;
            $anonymous = ($stripeSubscription->metadata['anonymous'] ?? 'no') === 'yes';
            
            // Get payment method details
            $paymentMethodDisplay = null;
            try {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
                if ($paymentIntent->payment_method) {
                    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentIntent->payment_method);
                    if ($paymentMethod && $paymentMethod->card) {
                        $cardBrand = ucfirst($paymentMethod->card->brand);
                        $last4 = $paymentMethod->card->last4;
                        $wallet = $paymentMethod->card->wallet ? $paymentMethod->card->wallet->type : null;
                        
                        if ($wallet) {
                            $paymentMethodDisplay = ucwords(str_replace('_', ' ', $wallet));
                        } else {
                            $paymentMethodDisplay = $cardBrand . ' •••• ' . $last4;
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to retrieve payment method for renewal', ['error' => $e->getMessage()]);
            }
            
            $donation = UserDonation::create([
                'user_id' => $anonymous ? 0 : $userId,
                'subscription_id' => $subscriptionId,
                'payment_id' => $paymentIntentId,
                'amount' => $amount,
                'recurring' => true,
                'status' => UserDonation::STATUS_COMPLETED,
                'payment_method' => $paymentMethodDisplay,
                'completed_at' => now(),
            ]);
            
            // Tag donor in ActiveCampaign
            if (!$anonymous && $userId) {
                try {
                    $user = \App\Models\User::find($userId);
                    if ($user && $user->email) {
                        $acService = new ActiveCampaignService();
                        $acService->tagDonor($user);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to tag donor in ActiveCampaign', [
                        'user_id' => $userId,
                        'donation_id' => $donation->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            \Log::info('Renewal donation created', [
                'donation_id' => $donation->id,
                'subscription_id' => $subscriptionId,
                'amount' => $amount,
            ]);
        }
    }
}
