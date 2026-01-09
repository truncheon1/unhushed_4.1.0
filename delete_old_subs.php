<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Subscriptions;

echo "Deleting old/invalid subscriptions for User 1...\n\n";

// Delete the old Middle School subscription that doesn't exist in Stripe anymore
$deleted = Subscriptions::where('user_id', 1)
    ->where('subscription_id', 'sub_1SnPoDDIPe5Nh5YlwgG5CBD4')
    ->delete();

if($deleted > 0){
    echo "✅ Deleted old Middle School subscription (ID: 696)\n";
} else {
    echo "No subscriptions deleted\n";
}

echo "\nRemaining subscriptions for User 1:\n";
$subs = Subscriptions::where('user_id', 1)->where('active', '>=', 1)->get();
foreach($subs as $sub){
    echo "- Sub ID: {$sub->id}, Product: {$sub->product_id}, Stripe ID: {$sub->subscription_id}, Status: {$sub->active}\n";
}
