<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking subscriptions table:\n\n";
$subs = DB::table('subscriptions')
    ->where('active', '>=', 1)
    ->select('id', 'user_id', 'product_id', 'subscription_id', 'active', 'created_at')
    ->get();

foreach($subs as $s) {
    echo "Sub ID: {$s->id}\n";
    echo "  User: {$s->user_id}\n";
    echo "  Product: {$s->product_id}\n";
    echo "  Stripe Sub ID: " . ($s->subscription_id ?: 'NULL') . "\n";
    echo "  Active Status: {$s->active} (" . getStatusName($s->active) . ")\n";
    echo "  Created: {$s->created_at}\n";
    echo "\n";
}

function getStatusName($active) {
    switch($active) {
        case 0: return 'INACTIVE';
        case 1: return 'ACTIVE/INTENT';
        case 2: return 'SUBSCRIPTION_ACTIVE';
        case 3: return 'EXPIRED/CANCELED';
        case 4: return 'REVIEWING';
        default: return 'UNKNOWN';
    }
}
