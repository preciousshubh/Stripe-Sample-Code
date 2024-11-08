<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

// Retrieve the payment method ID and the existing customer ID from the AJAX request
$paymentMethodId = $_POST['payment_method_id'];
$customerId = $_POST['customer_id'];

try {
    if (empty($customerId)) {
        throw new \Exception("Customer ID is missing or undefined.");
    }
    if (empty($paymentMethodId)) {
        throw new \Exception("Payment method ID is missing or undefined.");
    }
    // Retrieve the customer
    $customer = \Stripe\Customer::retrieve($customerId);

    // Attach the new payment method to the customer
    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
    $paymentMethod->attach([
        'customer' => $customer->id
    ]);

    // Update the customer’s default payment method
    \Stripe\Customer::update(
        $customer->id,
        [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethod->id
            ]
        ]
    );

    echo json_encode([
        'status' => 'success',
        'customer_id' => $customer->id,
        'message' => 'Card saved successfully!'
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (\Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
