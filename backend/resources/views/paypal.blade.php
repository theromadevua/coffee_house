<!DOCTYPE html>
<html>
<head>
    <title>PayPal Payment</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<h1>Pay with PayPal</h1>
<form action="{{ route('paypal.create') }}" method="POST">
    @csrf
    <label for="amount">Amount (USD):</label>
    <input type="number" name="amount" value="10.00" step="0.01" min="0.01" required>
    <button type="submit">Pay Now</button>
</form>
</body>
</html>
