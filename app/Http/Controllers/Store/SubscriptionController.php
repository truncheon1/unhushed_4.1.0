<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ActiveSubscriptions;
use App\Models\Products;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display user's active curriculum subscriptions
     */
    public function index($path = 'educators')
    {
        $path = get_path($path);
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get user's subscriptions using same logic as BillingController for consistency
        $details = [];
        $subs = Subscriptions::where([
            ['user_id', $user->id],
            ['active', '>', '1'],
        ])->get();
        
        foreach($subs as $sub){
            $id = $sub->id;
            $product_id = $sub->product_id;
            $product = Products::where('id', $product_id)
                ->where('category', 1)
                ->first();
            
            if($product){
                // Get total and used from active_subscriptions
                $activeSubscription = ActiveSubscriptions::where('product_id', $product_id)
                    ->where('org_id', $user->org_id)
                    ->where('category', 1)
                    ->first();
                
                $total = $activeSubscription ? $activeSubscription->total : 0;
                $used = $activeSubscription ? $activeSubscription->used : 0;
                
                $since = date('m-d-Y', strtotime($sub->created_at));
                if($sub->active == 2){
                    $s_status = 'active';
                }
                if($sub->active == 3){
                    $s_status = 'canceled';
                }
                if($sub->active == 4){
                    $s_status = 'reviewing';
                }
                $renew = date('m-d-Y', strtotime($sub->exp_date));
                $due = $sub->due ?? 0;
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
        
        return view('backend.finance.billing.my-subscriptions', compact('details'))
            ->with('path', $path);
    }
    
    /**
     * Redirect to Stripe Customer Billing Portal
     */
    public function billingPortal(Request $request, $path = 'educators')
    {
        $path = get_path($path);
        
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Ensure user has a Stripe customer ID
        if (!$user->stripe_id) {
            return redirect("/{$path}/my-subscriptions")->with('error', 'No billing information found. Please contact support.');
        }

        try {
            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Stripe::setApiVersion('2025-12-15.clover');
            
            // Create Stripe billing portal session
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $user->stripe_id,
                'return_url' => url("/{$path}/my-subscriptions"),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Billing portal error: ' . $e->getMessage());
            return redirect("/{$path}/my-subscriptions")->with('error', 'Unable to access billing portal. Please try again or contact support.');
        }
    }
}
