<?php
/**
 * Create Stripe recurring prices for curriculum subscriptions
 * Run this once to set up all curriculum prices in Stripe
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Stripe\Stripe::setApiKey(config('cashier.secret'));
\Stripe\Stripe::setApiVersion('2025-12-15.clover');

echo "Creating Stripe prices for curriculum subscriptions...\n\n";

// Get all curriculum products
$products = DB::table('products')->where('category', 1)->get();

foreach ($products as $product) {
    echo "Product: {$product->name} (ID: {$product->id})\n";
    
    // Get or create Stripe product
    $stripeProductId = $product->stripe_product_id;
    
    if (!$stripeProductId) {
        echo "  - Creating Stripe product...\n";
        $stripeProduct = \Stripe\Product::create([
            'name' => $product->name,
            'description' => $product->short_description ?: null,
            'metadata' => [
                'product_id' => $product->id,
                'category' => 'curriculum'
            ]
        ]);
        $stripeProductId = $stripeProduct->id;
        
        // Update database
        DB::table('products')->where('id', $product->id)->update(['stripe_product_id' => $stripeProductId]);
        echo "  - Stripe product created: {$stripeProductId}\n";
    } else {
        echo "  - Using existing Stripe product: {$stripeProductId}\n";
    }
    
    // Get pricing tiers
    $pricingTiers = DB::table('curriculum_prices')
        ->where('product_id', $product->id)
        ->orderBy('min_users')
        ->get();
    
    foreach ($pricingTiers as $tier) {
        echo "  - Tier {$tier->min_users}-{$tier->max_users} users: \${$tier->recurring_price}/year\n";
        
        if ($tier->recurring_price > 0) {
            // Create recurring price in Stripe
            try {
                $stripePrice = \Stripe\Price::create([
                    'product' => $stripeProductId,
                    'currency' => 'usd',
                    'unit_amount' => $tier->recurring_price * 100, // Convert to cents
                    'recurring' => [
                        'interval' => 'year',
                        'interval_count' => 1
                    ],
                    'metadata' => [
                        'curriculum_price_id' => $tier->id,
                        'product_id' => $product->id,
                        'user_range' => "{$tier->min_users}-{$tier->max_users}"
                    ]
                ]);
                
                // Update database with new Stripe price ID
                DB::table('curriculum_prices')
                    ->where('id', $tier->id)
                    ->update(['stripe_price_id' => $stripePrice->id]);
                
                echo "    ✓ Created Stripe price: {$stripePrice->id}\n";
            } catch (\Exception $e) {
                echo "    ✗ Error: {$e->getMessage()}\n";
            }
        } else {
            echo "    - Skipped (free tier)\n";
        }
    }
    
    echo "\n";
}

echo "Done! All curriculum prices created in Stripe.\n";
