<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #fcb800;
            padding-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #000;
            font-weight: 900;
            text-transform: uppercase;
        }
        .invoice-details {
            text-align: right;
        }
        .details-table {
            width: 100%;
            margin-bottom: 40px;
        }
        .details-table td {
            vertical-align: top;
        }
        .billing-info h3 {
            margin-top: 0;
            text-transform: uppercase;
            font-size: 12px;
            color: #888;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .items-table th {
            background: #f9f9f9;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            font-size: 11px;
            font-bold: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table style="width: 100%; margin-bottom: 40px;">
            <tr>
                <td class="company-info">
                    <h1>NEPSTRADING</h1>
                    <p>123 Commerce St, Tech City<br>contact@nepstrading.com<br>+1 (555) 123-4567</p>
                </td>
                <td class="invoice-details" style="text-align: right;">
                    <h2 style="margin-top: 0; color: #fcb800;">INVOICE</h2>
                    <p><strong>Order ID:</strong> #{{ $order->id }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}<br>
                    <strong>Status:</strong> {{ strtoupper($order->status) }}</p>
                </td>
            </tr>
        </table>

        <table class="details-table">
            <tr>
                <td class="billing-info" style="width: 50%;">
                    <h3>Bill To</h3>
                    <p><strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong><br>
                    {{ $order->billing_address }}<br>
                    {{ $order->billing_city }}, {{ $order->billing_postcode }}<br>
                    Phone: {{ $order->billing_phone }}<br>
                    Email: {{ $order->billing_email }}</p>
                </td>
                @if($order->shipping_address)
                <td class="shipping-info" style="width: 50%;">
                    <h3>Ship To</h3>
                    <p><strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                </td>
                @endif
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td style="font-family: monospace; font-size: 12px; color: #666;">{{ $item->product->sku ?? '-' }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->qty }}</td>
                    <td style="text-align: right;"><strong>${{ number_format($item->total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 5px 0; color: #888;">Subtotal:</td>
                    <td style="text-align: right; padding: 5px 0;">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->tax_total > 0)
                <tr>
                    <td style="padding: 5px 0; color: #888;">Tax:</td>
                    <td style="text-align: right; padding: 5px 0;">${{ number_format($order->tax_total, 2) }}</td>
                </tr>
                @endif
                @if($order->shipping_total > 0)
                <tr>
                    <td style="padding: 5px 0; color: #888;">Shipping:</td>
                    <td style="text-align: right; padding: 5px 0;">${{ number_format($order->shipping_total, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td style="padding-top: 10px; font-size: 18px; font-weight: bold;">TOTAL:</td>
                    <td style="text-align: right; padding-top: 10px; font-size: 18px; font-weight: bold; color: #fcb800;">${{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions about this invoice, please contact us.</p>
        </div>
    </div>
</body>
</html>
