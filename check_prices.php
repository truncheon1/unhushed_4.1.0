<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$prices = DB::table('curriculum_prices')
    ->select('id', 'product_id', 'stripe_price_id', 'min_users', 'max_users', 'standard_price', 'recurring_price')
    ->get();

echo json_encode($prices, JSON_PRETTY_PRINT);
