<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #5eba7d; color: white; padding: 32px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { background: #fff; border: 1px solid #e5e5e5; border-top: none; padding: 24px; border-radius: 0 0 8px 8px; }
        .cta-btn { display: inline-block; padding: 12px 32px; background: #5eba7d; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 16px 0; }
        .footer { text-align: center; padding: 16px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Nepstrading!</h1>
    </div>
    <div class="content">
        <p>Hi {{ $user->name }},</p>
        <p>Welcome to Nepstrading! Your account has been created successfully.</p>
        <p>Start exploring our collection of quality products:</p>
        <p style="text-align: center;">
            <a href="{{ url('/products') }}" class="cta-btn">Start Shopping</a>
        </p>
        <p>Happy shopping!</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nepstrading. All rights reserved.</p>
    </div>
</body>
</html>
