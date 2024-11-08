<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

$customer_id = 'cus_RAOcyhTQvEz9Sz';

if ($customer_id) {
  try {
    // Create a billing portal session
    $session = \Stripe\BillingPortal\Session::create([
      'customer' => $customer_id,
      'return_url' => 'http://localhost/stripe-sample-code/public/success.php', // Replace with your actual return URL
    ]);

    // Redirect to the billing portal
    header("Location: " . $session->url);
    exit;
  } catch (Exception $e) {
    echo 'Error creating portal session: ' . $e->getMessage();
  }
} else {
  echo 'No session ID provided.';
}
