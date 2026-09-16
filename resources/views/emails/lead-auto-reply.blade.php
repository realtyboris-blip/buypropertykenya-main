<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Inquiry</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333333;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #064e3b 0%, #0d7a5f 60%, #053d2f 100%);
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .email-header .subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 16px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #064e3b;
            margin-bottom: 16px;
        }
        .greeting span {
            color: #0d7a5f;
        }
        .message-content {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 24px;
        }
        .message-content p {
            margin-bottom: 12px;
        }
        .inquiry-card {
            background: #f8faf9;
            border-radius: 12px;
            padding: 20px 24px;
            margin: 20px 0 24px;
            border-left: 4px solid #0d7a5f;
        }
        .inquiry-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #064e3b;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .inquiry-row {
            display: flex;
            padding: 6px 0;
            font-size: 14px;
        }
        .inquiry-row .label {
            font-weight: 500;
            color: #4a5568;
            width: 120px;
            flex-shrink: 0;
        }
        .inquiry-row .value {
            color: #2d3748;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #064e3b 0%, #0d7a5f 100%);
            color: #ffffff !important;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 122, 95, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(13, 122, 95, 0.4);
        }
        .cta-container {
            text-align: center;
            margin: 28px 0 16px;
        }
        .email-footer {
            background: #f8faf9;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            font-size: 13px;
            color: #718096;
            margin-bottom: 4px;
        }
        .email-footer .contact-info {
            font-size: 14px;
            color: #4a5568;
            margin-top: 8px;
        }
        .email-footer .contact-info a {
            color: #0d7a5f;
            text-decoration: none;
        }
        @media (max-width: 480px) {
            .email-body {
                padding: 24px 16px;
            }
            .email-header {
                padding: 30px 20px 24px;
            }
            .email-header h1 {
                font-size: 22px;
            }
            .inquiry-row {
                flex-direction: column;
                padding: 4px 0;
            }
            .inquiry-row .label {
                width: auto;
                font-size: 12px;
            }
            .inquiry-row .value {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f7f7f7; padding: 40px 16px;">
        <tr>
            <td align="center">
                <div class="email-container">
                    <!-- Header -->
                    <div class="email-header">
                        <h1>Thank You for Your Inquiry</h1>
                        <p class="subtitle">We're here to help you find your dream property</p>
                    </div>
                    
                    <!-- Body -->
                    <div class="email-body">
                        <div class="greeting">
                            Hello <span>{{ $inquiry->name ?? 'Valued Customer' }}</span> 👋
                        </div>
                        
                        <div class="message-content">
                            <p>Thank you for reaching out to <strong>BuyProperty Kenya</strong>. We have received your inquiry and our team is already reviewing your request.</p>
                            
                            <p>One of our property experts will contact you within <strong>24 hours</strong> to discuss your requirements in detail and help you find the perfect property.</p>
                            
                            <p>In the meantime, here's a summary of your inquiry:</p>
                        </div>
                        
                        <!-- Inquiry Summary -->
                        <div class="inquiry-card">
                            <h3>📋 Inquiry Summary</h3>
                            <div class="inquiry-row">
                                <span class="label">Reference</span>
                                <span class="value">#{{ str_pad($inquiry->id ?? 0, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="inquiry-row">
                                <span class="label">Name</span>
                                <span class="value">{{ $inquiry->name ?? 'N/A' }}</span>
                            </div>
                            <div class="inquiry-row">
                                <span class="label">Email</span>
                                <span class="value">{{ $inquiry->email ?? 'N/A' }}</span>
                            </div>
                            <div class="inquiry-row">
                                <span class="label">Phone</span>
                                <span class="value">{{ $inquiry->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="inquiry-row">
                                <span class="label">Inquiry Type</span>
                                <span class="value">{{ $inquiry->inquiry_type_label ?? 'General Inquiry' }}</span>
                            </div>
                            @if($inquiry->message)
                            <div class="inquiry-row">
                                <span class="label">Message</span>
                                <span class="value">{{ Str::limit($inquiry->message, 100) }}</span>
                            </div>
                            @endif
                            <div class="inquiry-row">
                                <span class="label">Submitted</span>
                                <span class="value">{{ $inquiry->created_at ? $inquiry->created_at->format('F d, Y \a\t H:i') : 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <!-- Call to Action -->
                        <div class="cta-container">
                            <a href="{{ config('app.url') }}/properties" class="btn-primary">
                                Browse Properties
                            </a>
                        </div>
                        
                        <div class="message-content" style="margin-top: 20px; font-size: 14px; color: #718096; text-align: center;">
                            <p style="font-size: 13px; color: #a0aec0;">
                                Need immediate assistance? Call us at 
                                <a href="tel:{{ env('SALES_TEAM_PHONE', '+254702430127') }}" style="color: #0d7a5f; text-decoration: none; font-weight: 500;">
                                    {{ env('SALES_TEAM_PHONE', '+254 702 430 127') }}
                                </a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="email-footer">
                        <p style="font-weight: 600; color: #064e3b; font-size: 14px;">
                            BuyProperty Kenya
                        </p>
                        <p>Your Trusted Partner in Real Estate</p>
                        <div class="contact-info">
                            📞 <a href="tel:{{ env('SALES_TEAM_PHONE', '+254702430127') }}">{{ env('SALES_TEAM_PHONE', '+254 702 430 127') }}</a>
                            &nbsp;|&nbsp;
                            ✉️ <a href="mailto:{{ env('SALES_TEAM_EMAIL', 'sales@buypropertykenya.com') }}">{{ env('SALES_TEAM_EMAIL', 'sales@buypropertykenya.com') }}</a>
                        </div>
                        <p style="font-size: 12px; color: #a0aec0; margin-top: 12px;">
                            &copy; {{ date('Y') }} BuyProperty Kenya. All rights reserved.<br>
                            <small>This is an automated response. Please do not reply to this email.</small>
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>