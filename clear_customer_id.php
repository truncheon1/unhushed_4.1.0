<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Clear Stripe customer ID for the authenticated user
$user = User::find(1); // Adjust user ID if needed

if ($user) {
    echo "User: {$user->email}\n";
    echo "Old Stripe Customer ID: " . ($user->stripe_id ?? 'NULL') . "\n";
    
    $user->stripe_id = null;
    $user->save();
    
    echo "Cleared Stripe customer ID!\n";
    echo "\nNow you can checkout and a new customer will be created in Stripe.\n";
} else {
    echo "User not found\n";
}
