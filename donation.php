<?php
include 'config.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("location: login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Integrate with Midtrans here
    // Assuming you have a setup for Midtrans, using their PHP library
    require_once dirname(__FILE__) . '/Midtrans.php';
    \Midtrans\Config::$clientKey = 'Mid-client-mF8314ZSt8TFp6bN';
    \Midtrans\Config::$isProduction = false;

    $transaction_details = array(
        'order_id' => rand(),
        'gross_amount' => $_POST['amount'], // in IDR
    );

    $transaction = array(
        'transaction_details' => $transaction_details,
    );

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);       echo json_encode(['snapToken' => $snapToken]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-mF8314ZSt8TFp6bN"></script>
</head>
<body>
<div class="container">
    <h2>Make a Donation</h2>
    <form id="donation-form" method="post">
        <div class="mb-3">
            <label for="amount" class="form-label">Amount (IDR)</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Donate</button>
    </form>
</div>

<script>
document.getElementById('donation-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);

    fetch('donation', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.snapToken) {
            snap.pay(data.snapToken, {
                onSuccess: function(result) {
                    alert('Donation successful!');
                    console.log(result);
                },
                onPending: function(result) {
                    alert('Waiting for payment.');
                    console.log(result);
                },
                onError: function(result) {
                    alert('Payment failed.');
                    console.log(result);
                },
                onClose: function() {
                    alert('You closed the payment popup without finishing the payment.');
                }
            });
        } else {
            alert('Error: ' + data.error);
        }
    });
});
</script>
</body>
</html>
