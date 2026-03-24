<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #5eba7d; color: white; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #fff; border: 1px solid #e5e5e5; border-top: none; padding: 24px; border-radius: 0 0 8px 8px; }
        .order-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .order-table th, .order-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        .order-table th { background: #f8f8f8; font-weight: 600; }
        .total-row { font-weight: 700; font-size: 16px; }
        .footer { text-align: center; padding: 16px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Confirmed!</h1>
    </div>
    <div class="content">
        <p>Hi {{ $order->billing_first_name }},</p>
        <p>Thank you for your order! Here's a summary:</p>

        <p><strong>Order #{{ $order->id }}</strong> &mdash; {{ $order->created_at->format('M d, Y') }}</p>

        <table class="order-table">
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width: 100%; font-size: 14px;">
            <tr><td>Subtotal</td><td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td></tr>
            @if($order->discount_total > 0)
            <tr><td>Discount ({{ $order->coupon_code }})</td><td style="text-align: right; color: #dc2626;">-${{ number_format($order->discount_total, 2) }}</td></tr>
            @endif
            <tr><td>Shipping</td><td style="text-align: right;">${{ number_format($order->shipping_total, 2) }}</td></tr>
            @if($order->tax_total > 0)
            <tr><td>Tax{{ $order->tax_name ? " ({$order->tax_name})" : '' }}</td><td style="text-align: right;">${{ number_format($order->tax_total, 2) }}</td></tr>
            @endif
            <tr class="total-row"><td>Total</td><td style="text-align: right;">${{ number_format($order->total, 2) }}</td></tr>
        </table>

        <p style="margin-top: 24px;">We'll send you another email when your order ships.</p>
        <p>Thanks for shopping with Nepstrading!</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nepstrading. All rights reserved.</p>
    </div>
</body>
</html>
