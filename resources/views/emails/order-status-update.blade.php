<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #5eba7d; color: white; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; border: 1px solid #e5e5e5; border-top: none; padding: 24px; border-radius: 0 0 8px 8px; }
        .status-badge { display: inline-block; padding: 6px 16px; border-radius: 999px; font-weight: 600; font-size: 14px; text-transform: capitalize; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #dcfce7; color: #15803d; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-shipped { background: #e0e7ff; color: #3730a3; }
        .footer { text-align: center; padding: 16px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Update</h1>
    </div>
    <div class="content">
        <p>Hi {{ $order->billing_first_name }},</p>
        <p>Your order <strong>#{{ $order->id }}</strong> status has been updated:</p>

        <p style="text-align: center; margin: 24px 0;">
            <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
            &rarr;
            <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </p>

        <p><strong>Order Total:</strong> ${{ number_format($order->total, 2) }}</p>

        @if($order->status === 'completed')
        <p>Your order has been completed! Thank you for shopping with us.</p>
        @elseif($order->status === 'shipped')
        <p>Your order is on its way! You should receive it soon.</p>
        @elseif($order->status === 'cancelled')
        <p>Your order has been cancelled. If you have questions, please contact us.</p>
        @endif

        <p>Thanks for shopping with Nepstrading!</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nepstrading. All rights reserved.</p>
    </div>
</body>
</html>
