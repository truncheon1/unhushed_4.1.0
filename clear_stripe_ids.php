<?php
/**
 * Clear invalid Stripe product IDs from products table
 * Run this before stripe:sync-products if products were created in different Stripe account
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Clearing invalid Stripe product IDs from products table...\n\n";

$products = DB::table('products')->whereNotNull('stripe_product_id')->get();

echo "Found {$products->count()} products with Stripe product IDs\n";
echo "These will be cleared and recreated when you run: php artisan stripe:sync-products\n\n";

if (!empty($argv[1]) && $argv[1] === '--execute') {
    $updated = DB::table('products')->update(['stripe_product_id' => null]);
    echo "✅ Cleared stripe_product_id from {$updated} products\n";
    echo "\nNow run: php artisan stripe:sync-products\n";
} else {
    echo "To clear them, run: php clear_stripe_ids.php --execute\n";
}
