<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Stripe Customer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center ">
        <h1 class="text-primary my-5">View Customer Details</h1>
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:50vh;">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Enter Customer ID:</label>
                    <input type="text" id="name" name="customer_id" class="form-control" placeholder="Enter your name" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">View Customer</button>
            </form>


            <a class="btn btn-info m-2" href=" http://localhost/stripe-sample-code/public/save_card_information.php">Save Card</a>
            <a class="btn btn-info m-2" href=" http://localhost/stripe-sample-code/public/create-checkout-session.php">Checkout page</a>

        </div>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
session_start();
if (isset($_POST['customer_id']) || $_SESSION['customer_id']) {
    $customerId = isset($_POST['customer_id']) ? $_POST['customer_id'] : $_SESSION['customer_id'];
    try {

        $customer = \Stripe\Customer::retrieve($customerId);
?>
        <div class="container d-flex justify-content-center align-items-center" style="min-height:70vh;">
            <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
                <h5 class="card-title text-center">Customer Details</h5>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="id" class="form-label fw-bold">Customer ID:</label>
                        <p id="id" class="form-control-plaintext"><?php echo htmlspecialchars($customer->id); ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Customer Name:</label>
                        <p id="name" class="form-control-plaintext"><?php echo htmlspecialchars($customer->name); ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Customer Email:</label>
                        <p id="email" class="form-control-plaintext"><?php echo htmlspecialchars($customer->email); ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Customer Description:</label>
                        <p id="description" class="form-control-plaintext"><?php echo htmlspecialchars($customer->description); ?></p>
                    </div>
                </div>
            </div>

        </div>

    <?php
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo 'error :' . $e->getMessage();
    }
}

try {

    $paymentMethods = \Stripe\PaymentMethod::all([
        'customer' => $customerId,
        'type' => 'card',
    ]);

    ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:70vh;">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <h5 class="card-title text-center">Customer Card Details</h5>
            <?php

            foreach ($paymentMethods->data as $paymentMethod) {
            ?>


                <div class="card-body">
                    <div class="mb-3">
                        <label for="id" class="form-label fw-bold">Customer Card ending Last 4 Digits:</label>
                        <p id="id" class="form-control-plaintext"><?php echo htmlspecialchars($paymentMethod->card->last4); ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Brand:</label>
                        <p id="name" class="form-control-plaintext"><?php echo htmlspecialchars($paymentMethod->card->brand); ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Exp:</label>
                        <p id="email" class="form-control-plaintext"><?php echo htmlspecialchars($paymentMethod->card->exp_month) . "/" .  $paymentMethod->card->exp_year; ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Customer Description:</label>
                        <p id="description" class="form-control-plaintext"><?php echo htmlspecialchars($customer->description); ?></p>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>

    </div>

<?php

} catch (\Stripe\Exception\ApiErrorException $e) {

    echo 'Error: ' . $e->getMessage();
}


try {

    $amount = 5000;
    $transfer = \Stripe\Transfer::create([
        'amount' => $amount,
        'currency' => 'usd',
        'destination' => $account->id,
        'description' => 'Payment for services rendered',
    ]);
    echo "<pre>";
    print_r($transfer);
    echo "</pre>";

    // Output success message or transfer ID
    echo "Transfer successful! Transfer ID: " . $transfer->id;
} catch (\Stripe\Exception\ApiErrorException $e) {

    echo 'Error: ' . $e->getMessage();
}
