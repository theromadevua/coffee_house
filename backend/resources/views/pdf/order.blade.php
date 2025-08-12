<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .order-info div {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Details</h1>
    </div>

    <div class="order-info">
        <div><span class="label">Order ID:</span> {{ $order->id }}</div>
        <div><span class="label">User:</span> {{ $order->user ? $order->user->name : 'Unknown' }}</div>
        <div><span class="label">Email:</span> {{ $order->user ? $order->user->email : 'N/A' }}</div>
        <div><span class="label">Total Amount:</span> ${{ number_format($order->total_amount, 2) }}</div>
        <div><span class="label">Status:</span> {{ $order->status->value }}</div>
        <div><span class="label">Delivery Address:</span> {{ $order->delivery_address }}</div>
        <div><span class="label">Contact Phone:</span> {{ $order->contact_phone }}</div>
        <div><span class="label">Delivery Time:</span> {{ $order->delivery_time ? $order->delivery_time->format('Y-m-d H:i:s') : 'N/A' }}</div>
    </div>

    <h2>Items</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description ?? 'N/A' }}</td>
                <td class="text-right">{{ $item->pivot->quantity }}</td>
                <td class="text-right">${{ number_format($item->pivot->price, 2) }}</td>
                <td class="text-right">${{ number_format($item->pivot->price * $item->pivot->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right total">Total:</td>
                <td class="text-right total">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>