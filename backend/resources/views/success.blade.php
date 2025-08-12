<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<h1>Payment Successful!</h1>
<p>Transaction ID: {{ $transaction_id }}</p>
<a href="{{ url('/') }}">Back to Home</a>
</body>
</html>
