<?php
/**
 * Clear stripe_price_id from curriculum_prices and product_vars
 * This ensures all prices will be recreated fresh in Stripe
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Clearing Stripe price IDs from curriculum_prices and product_vars tables...\n\n";

$curriculumCount = DB::table('curriculum_prices')->whereNotNull('stripe_price_id')->count();
$variantsCount = DB::table('product_vars')->whereNotNull('stripe_price_id')->count();

echo "Found:\n";
echo "- {$curriculumCount} curriculum pricing tiers with Stripe price IDs\n";
echo "- {$variantsCount} product variants with Stripe price IDs\n\n";

if (!empty($argv[1]) && $argv[1] === '--execute') {
    $updated1 = DB::table('curriculum_prices')->update(['stripe_price_id' => null]);
    $updated2 = DB::table('product_vars')->update(['stripe_price_id' => null]);
    
    echo "✅ Cleared:\n";
    echo "- {$updated1} curriculum_prices rows\n";
    echo "- {$updated2} product_vars rows\n\n";
    echo "Now run: php artisan stripe:sync-products --force\n";
} else {
    echo "To clear them, run: php clear_price_ids.php --execute\n";
}
