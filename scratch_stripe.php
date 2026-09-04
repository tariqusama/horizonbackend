<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$stripeSecret = env('STRIPE_SECRET');
\Stripe\Stripe::setApiKey($stripeSecret);

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 1000,
        'currency' => 'usd',
        'payment_method' => 'pm_card_visa',
        'confirmation_method' => 'manual',
        'confirm' => true,
        'return_url' => 'http://localhost:3000/welcome?payment=success',
    ]);
    echo "Success: " . $paymentIntent->status;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
