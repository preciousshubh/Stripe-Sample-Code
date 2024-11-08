<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment for Existing Customer</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:70vh;">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Save Card Information</h2>
            <form id="payment-form">
                <div id="card-element" class="form-group">

                </div>
                <div class="form-group">
                    <label for="name">Enter Customer ID:</label>
                    <input type="text" id="customer_id" class="form-control" placeholder="Enter your customer id" required>
                </div>

                <div id="card-errors" role="alert"></div>
                <div class="success"></div>
                <button type="submit" id="submit" class="btn btn-primary btn-block">Save Card</button>
                <a class="btn btn-info m-2" href=" http://localhost/stripe-sample-code/public/view_customer.php">View Customer</a>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var stripe = Stripe('pk_test_51QIPVsRq3Pbq5XLIlEVwH8C7a5T3fD7ChoWzvWwttqIejFAFFavFjYS9obESI2n0sYbHGT1QbX5eBvBwgk8VZ2g800MBigbelu');
            var elements = stripe.elements();
            var card = elements.create('card');
            card.mount('#card-element');

            // Handle form submission
            $('#payment-form').on('submit', function(event) {
                event.preventDefault();
                var customer_id = $('#customer_id').val();
                stripe.createPaymentMethod('card', card).then(function(result) {
                    if (result.error) {
                        $('#card-errors').text(result.error.message);
                    } else {

                        $.ajax({
                            url: 'save_card_for_existing_customer.php',
                            method: 'POST',
                            data: {
                                payment_method_id: result.paymentMethod.id,
                                customer_id: customer_id
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                console.log('Card saved successfully: ', data);
                                $('.success').html('<div class="alert alert-success"> Satatus : ' + data.status + '<br> ' + 'Customer_id :' +
                                    data.customer_id + '<br>' + data.message + '</div>');
                            },
                            error: function(error) {
                                console.log('Error: ', error);
                                $('#card-errors').html('<div class="alert alert-danger">Card not saved: ' + error.responseText + '</div>');
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>