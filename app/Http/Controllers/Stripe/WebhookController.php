<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Subscriptions;
use App\Models\ActiveSubscriptions;
use App\Models\ProductAssignments;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('cashier.webhook.secret');

        if (!$webhookSecret) {
            Log::error('Stripe webhook secret not configured');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event based on type
        switch ($event->type) {
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            default:
                Log::info('Unhandled webhook event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle subscription created or updated
     */
    private function handleSubscriptionUpdated($stripeSubscription)
    {
        $subscriptionId = $stripeSubscription->id;
        $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
        $status = $stripeSubscription->status;

        // Update our subscriptions table
        $subscription = Subscriptions::where('subscription_id', $subscriptionId)->first();
        
        if ($subscription) {
            $subscription->exp_date = $currentPeriodEnd;
            $subscription->active = ($status === 'active' || $status === 'trialing') ? 1 : 0;
            $subscription->save();

            Log::info("Subscription updated: {$subscriptionId}, expires: {$currentPeriodEnd}");

            // Update product assignments
            $assignment = ProductAssignments::where('subscription_id', $subscriptionId)->first();
            if ($assignment) {
                $assignment->active = $subscription->active;
                $assignment->save();
            }

            // Update active subscriptions for organization
            $user = \App\Models\User::find($subscription->user_id);
            if ($user) {
                $activeSubscription = ActiveSubscriptions::where('org_id', $user->org_id)
                    ->where('product_id', $subscription->product_id)
                    ->first();
                
                if ($activeSubscription) {
                    $activeSubscription->status = $subscription->active;
                    $activeSubscription->save();
                }
            }
        } else {
            Log::warning("Subscription not found in database: {$subscriptionId}");
        }
    }

    /**
     * Handle subscription deletion/cancellation
     */
    private function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscriptionId = $stripeSubscription->id;

        $subscription = Subscriptions::where('subscription_id', $subscriptionId)->first();
        
        if ($subscription) {
            $subscription->active = 0;
            $subscription->save();

            Log::info("Subscription deleted: {$subscriptionId}");

            // Deactivate product assignments
            ProductAssignments::where('subscription_id', $subscriptionId)
                ->update(['active' => 0]);

            // Update active subscriptions
            $user = \App\Models\User::find($subscription->user_id);
            if ($user) {
                ActiveSubscriptions::where('org_id', $user->org_id)
                    ->where('product_id', $subscription->product_id)
                    ->update(['status' => 0]);
            }
        }
    }

    /**
     * Handle successful invoice payment (renewal)
     */
    private function handleInvoicePaymentSucceeded($invoice)
    {
        if (!isset($invoice->subscription)) {
            return; // Not a subscription invoice
        }

        $subscriptionId = $invoice->subscription;
        
        // Fetch the subscription to get updated period end
        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');
            $stripeSubscription = \Stripe\Subscription::retrieve($subscriptionId);
            
            $subscription = Subscriptions::where('subscription_id', $subscriptionId)->first();
            
            if ($subscription) {
                $newExpDate = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
                $subscription->exp_date = $newExpDate;
                $subscription->active = 1;
                $subscription->save();

                Log::info("Subscription renewed: {$subscriptionId}, new expiration: {$newExpDate}");
            }
        } catch (\Exception $e) {
            Log::error("Error processing renewal for subscription {$subscriptionId}: " . $e->getMessage());
        }
    }

    /**
     * Handle failed invoice payment
     */
    private function handleInvoicePaymentFailed($invoice)
    {
        if (!isset($invoice->subscription)) {
            return;
        }

        $subscriptionId = $invoice->subscription;
        
        Log::warning("Payment failed for subscription: {$subscriptionId}");
        
        // Optionally notify the user or take other actions
        // For now, we'll just log it - Stripe will handle retry logic
    }
}
