<!DOCTYPE html>
<html>
<head>
    <title>Reply to your inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .inquiry-details {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }
        .reply {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Property Platform</h2>
        </div>
        
        <div class="content">
            <h3>Hello {{ $name }},</h3>
            
            <p>Thank you for your inquiry. We have reviewed your request and here's our response:</p>
            
            <div class="inquiry-details">
                <strong>Your Inquiry:</strong><br>
                <strong>Type:</strong> {{ ucfirst($inquiry->inquiry_type) }}<br>
                @if($inquiry->property)
                    <strong>Property:</strong> {{ $inquiry->property->title }}<br>
                @endif
                <strong>Message:</strong><br>
                {{ $inquiry->message }}
            </div>
            
            <div class="reply">
                <strong>Our Response:</strong><br>
                {!! $reply !!}
            </div>
            
            <p>If you have any further questions, please don't hesitate to contact us.</p>
            
            <a href="{{ url('/contact') }}" class="button">Contact Us</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Property Platform. All rights reserved.</p>
            <p>This email was sent to {{ $inquiry->email }}</p>
        </div>
    </div>
</body>
</html>
