<?php

namespace App\Services;

use App\Models\Subscriptions;
use App\Models\ActiveSubscriptions;
use App\Models\ProductAssignments;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionCancellationService
{
    /**
     * Cancel a subscription and revoke all associated access
     * 
     * @param string $stripeSubscriptionId The Stripe subscription ID
     * @param bool $immediateRevoke Whether to revoke access immediately or at period end
     * @return array ['success' => bool, 'message' => string, 'details' => array]
     */
    public function cancelSubscription(string $stripeSubscriptionId, bool $immediateRevoke = false): array
    {
        DB::beginTransaction();
        
        try {
            // Find the subscription
            $subscription = Subscriptions::where('subscription_id', $stripeSubscriptionId)->first();
            
            if (!$subscription) {
                Log::warning("Subscription not found for cancellation", [
                    'subscription_id' => $stripeSubscriptionId
                ]);
                return [
                    'success' => false,
                    'message' => 'Subscription not found',
                    'details' => []
                ];
            }

            $user = User::find($subscription->user_id);
            if (!$user) {
                Log::error("User not found for subscription", [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id
                ]);
                return [
                    'success' => false,
                    'message' => 'User not found',
                    'details' => []
                ];
            }

            $details = [
                'subscription_id' => $subscription->id,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'user_id' => $user->id,
                'org_id' => $user->org_id,
                'product_id' => $subscription->product_id,
                'exp_date' => $subscription->exp_date,
                'immediate_revoke' => $immediateRevoke,
            ];

            // Step 1: Update subscription record
            $subscription->active = Subscriptions::INACTIVE;
            $subscription->save();
            
            Log::info("Subscription marked inactive", [
                'subscription_id' => $subscription->id,
                'stripe_subscription_id' => $stripeSubscriptionId
            ]);
            $details['subscription_updated'] = true;

            // Step 2: Update organization license pool (active_subscriptions)
            $activeSubscription = ActiveSubscriptions::where('org_id', $user->org_id)
                ->where('product_id', $subscription->product_id)
                ->first();

            if ($activeSubscription) {
                $activeSubscription->status = ActiveSubscriptions::STATUS_CANCELED;
                $activeSubscription->total = 0;
                $activeSubscription->used = 0;
                $activeSubscription->save();
                
                Log::info("Organization license pool reset", [
                    'active_subscription_id' => $activeSubscription->id,
                    'org_id' => $user->org_id,
                    'product_id' => $subscription->product_id
                ]);
                $details['active_subscription_updated'] = true;
                $details['licenses_revoked'] = $activeSubscription->used;
            } else {
                Log::warning("ActiveSubscription not found", [
                    'org_id' => $user->org_id,
                    'product_id' => $subscription->product_id
                ]);
                $details['active_subscription_updated'] = false;
            }

            // Step 3: Revoke all user access (product_assignments)
            $affectedAssignments = ProductAssignments::where('subscription_id', $stripeSubscriptionId)
                ->where('active', ProductAssignments::ACTIVE)
                ->get();

            $revokedCount = 0;
            foreach ($affectedAssignments as $assignment) {
                $assignment->active = ProductAssignments::INACTIVE;
                $assignment->save();
                $revokedCount++;
                
                Log::info("User access revoked", [
                    'assignment_id' => $assignment->id,
                    'user_id' => $assignment->user_id,
                    'product_id' => $assignment->product_id
                ]);
            }

            $details['user_access_revoked'] = $revokedCount;

            DB::commit();

            Log::info("Subscription cancellation completed successfully", $details);

            return [
                'success' => true,
                'message' => "Subscription canceled. {$revokedCount} user(s) access revoked.",
                'details' => $details
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Subscription cancellation failed", [
                'stripe_subscription_id' => $stripeSubscriptionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Cancellation failed: ' . $e->getMessage(),
                'details' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Resume a canceled subscription
     * 
     * @param string $stripeSubscriptionId
     * @return array
     */
    public function resumeSubscription(string $stripeSubscriptionId): array
    {
        DB::beginTransaction();
        
        try {
            $subscription = Subscriptions::where('subscription_id', $stripeSubscriptionId)->first();
            
            if (!$subscription) {
                return [
                    'success' => false,
                    'message' => 'Subscription not found'
                ];
            }

            $user = User::find($subscription->user_id);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }

            // Reactivate subscription
            $subscription->active = Subscriptions::ACTIVE;
            $subscription->save();

            // Reactivate organization license pool
            $activeSubscription = ActiveSubscriptions::where('org_id', $user->org_id)
                ->where('product_id', $subscription->product_id)
                ->first();

            if ($activeSubscription) {
                $activeSubscription->status = ActiveSubscriptions::STATUS_ACTIVE;
                // Note: Don't automatically restore total/used counts - org admin should manage these
                $activeSubscription->save();
            }

            // Reactivate user assignments
            $reactivatedCount = ProductAssignments::where('subscription_id', $stripeSubscriptionId)
                ->update(['active' => ProductAssignments::ACTIVE]);

            DB::commit();

            Log::info("Subscription resumed", [
                'stripe_subscription_id' => $stripeSubscriptionId,
                'users_reactivated' => $reactivatedCount
            ]);

            return [
                'success' => true,
                'message' => "Subscription resumed. {$reactivatedCount} user(s) access restored.",
                'details' => [
                    'subscription_id' => $subscription->id,
                    'users_reactivated' => $reactivatedCount
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Subscription resume failed", [
                'stripe_subscription_id' => $stripeSubscriptionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Resume failed: ' . $e->getMessage()
            ];
        }
    }
}
