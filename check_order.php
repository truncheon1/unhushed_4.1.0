<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orderId = 4442;
$order = DB::table('orders')->where('id', $orderId)->first();
echo "Order #{$orderId} Details:\n";
echo json_encode($order, JSON_PRETTY_PRINT);
echo "\n\nOrder Items:\n";
$items = DB::table('order_items')->where('order_id', $orderId)->get();
echo json_encode($items, JSON_PRETTY_PRINT);
