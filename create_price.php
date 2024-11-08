<?php
require_once __DIR__ . '/../vendor/autoload.php';

$stripe = new \Stripe\StripeClient("sk_test_tR3PYbcVNZZ796tH88S4VQ2u");

$product = $stripe->products->create([
    'name' => 'Starter Subscription',
    'description' => '$12/Month subscription',
]);
echo "Success! Here is your starter subscription product id: " . $product->id . "\n";

$price = $stripe->prices->create([
    'unit_amount' => 1200,
    'currency' => 'usd',
    'recurring' => ['interval' => 'month'],
    'product' => $product['id'],
]);
echo "Success! Here is your starter subscription price id: " . $price->id . "\n";
