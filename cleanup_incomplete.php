<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Deleting incomplete subscription (ID: 697)...\n";

$deleted = DB::table('subscriptions')->where('id', 697)->delete();

if($deleted > 0){
    echo "✅ Deleted incomplete subscription\n";
} else {
    echo "Subscription not found or already deleted\n";
}
