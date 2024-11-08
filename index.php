<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $description = $_POST['description'] ?? null;

    if ($name && $email) {
        try {

            $customer = \Stripe\Customer::create([
                'name' => $name,
                'email' => $email,
                'description' => $description,
                'metadata' => [
                    'account_id' => 'acct_1QIPVsRq3Pbq5XLI'
                ]
            ]);
            session_start();
            $_SESSION['customer_id'] = $customer->id;
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo 'Customer created successfully!<br>';
            echo 'Customer ID: ' . htmlspecialchars($customer->id);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        } catch (\Exception $e) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo 'Error creating customer: ' . htmlspecialchars($e->getMessage());
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }
    } else {
        echo "Please provide both name and email.";
    }
}  ?>

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
        <h1 class="text-primary my-5">Create Stripe Customer</h1>
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:70vh;">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Create a New Customer</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="text">Description :</label>
                    <input type="text" id="description" name="description" class="form-control" placeholder="Enter your description" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Customer</button>
            </form>


            <a class="btn btn-info m-2" href=" http://localhost/stripe-sample-code/public/view_customer.php">View Customer</a>
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