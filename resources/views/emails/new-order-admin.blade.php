<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3338; color: white; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; border: 1px solid #e5e5e5; border-top: none; padding: 24px; border-radius: 0 0 8px 8px; }
        .info-table { width: 100%; font-size: 14px; margin: 16px 0; }
        .info-table td { padding: 6px 0; }
        .info-table td:first-child { font-weight: 600; width: 140px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Order #{{ $order->id }}</h1>
    </div>
    <div class="content">
        <p>A new order has been placed:</p>

        <table class="info-table">
            <tr><td>Customer</td><td>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</td></tr>
            <tr><td>Email</td><td>{{ $order->billing_email }}</td></tr>
            <tr><td>Items</td><td>{{ $order->items->count() }} items</td></tr>
            <tr><td>Subtotal</td><td>${{ number_format($order->subtotal, 2) }}</td></tr>
            @if($order->discount_total > 0)
            <tr><td>Discount</td><td>-${{ number_format($order->discount_total, 2) }} ({{ $order->coupon_code }})</td></tr>
            @endif
            <tr><td>Shipping</td><td>${{ number_format($order->shipping_total, 2) }}</td></tr>
            <tr><td>Tax</td><td>${{ number_format($order->tax_total, 2) }}</td></tr>
            <tr><td><strong>Total</strong></td><td><strong>${{ number_format($order->total, 2) }}</strong></td></tr>
        </table>
    </div>
</body>
</html>
