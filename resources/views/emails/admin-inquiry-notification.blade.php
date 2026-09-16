<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Inquiry Received</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f7f4;
            color: #1a2a2a;
            line-height: 1.6;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(6, 78, 59, 0.12);
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #064e3b 0%, #0d7a5f 50%, #1a9e7a 100%);
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }
        
        .logo {
            position: relative;
            z-index: 1;
        }
        
        .logo-icon {
            display: inline-block;
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .logo-icon svg {
            width: 100%;
            height: 100%;
            color: #f0d78c;
        }
        
        .header h1 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
            letter-spacing: 0.5px;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
            position: relative;
            z-index: 1;
            font-weight: 300;
        }
        
        .badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            color: #f0d78c;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }
        
        /* Body */
        .body {
            padding: 32px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #064e3b;
            margin-bottom: 4px;
        }
        
        .greeting-sub {
            color: #4a5568;
            font-size: 15px;
            margin-bottom: 24px;
        }
        
        /* Divider */
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #0d7a5f, transparent);
            margin: 20px 0;
            opacity: 0.2;
        }
        
        /* Details Card */
        .details-card {
            background: #f7faf9;
            border-radius: 12px;
            padding: 20px 24px;
            border-left: 4px solid #0d7a5f;
            margin: 16px 0 20px;
        }
        
        .details-card h3 {
            font-size: 13px;
            font-weight: 700;
            color: #064e3b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        
        .detail-row {
            display: flex;
            padding: 6px 0;
            font-size: 14px;
            border-bottom: 1px solid rgba(13, 122, 95, 0.06);
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            width: 110px;
            flex-shrink: 0;
        }
        
        .detail-value {
            color: #1a2a2a;
            word-break: break-word;
        }
        
        .detail-value .highlight {
            color: #064e3b;
            font-weight: 600;
        }
        
        .message-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 14px 16px;
            margin-top: 4px;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 14px;
            line-height: 1.7;
        }
        
        /* Action Button */
        .action-section {
            text-align: center;
            margin: 28px 0 16px;
        }
        
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #064e3b, #0d7a5f);
            color: #ffffff !important;
            padding: 14px 36px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 20px rgba(13, 122, 95, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(13, 122, 95, 0.4);
        }
        
        /* Footer */
        .footer {
            background: #f7faf9;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer p {
            font-size: 13px;
            color: #718096;
            margin-bottom: 4px;
        }
        
        .footer .brand {
            font-weight: 600;
            color: #064e3b;
            font-size: 14px;
        }
        
        .footer .contact {
            font-size: 13px;
            color: #4a5568;
            margin-top: 6px;
        }
        
        .footer .contact a {
            color: #0d7a5f;
            text-decoration: none;
        }
        
        .footer .contact a:hover {
            text-decoration: underline;
        }
        
        .footer .copyright {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 8px;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .body {
                padding: 24px 16px;
            }
            
            .header {
                padding: 30px 20px 24px;
            }
            
            .header h1 {
                font-size: 22px;
            }
            
            .details-card {
                padding: 16px;
            }
            
            .detail-row {
                flex-direction: column;
                padding: 4px 0;
            }
            
            .detail-label {
                width: auto;
                font-size: 12px;
            }
            
            .detail-value {
                font-size: 14px;
            }
            
            .btn-primary {
                padding: 12px 28px;
                font-size: 14px;
                width: 100%;
            }
            
            .footer {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <h1>New Inquiry Received</h1>
                <p>A new property inquiry has been submitted on your website</p>
                <span class="badge">Action Required</span>
            </div>
        </div>
        
        <!-- Body -->
        <div class="body">
            <div class="greeting">Hello Admin,</div>
            <div class="greeting-sub">
                A potential client has submitted a property inquiry and is waiting for your response.
            </div>
            
            <div class="divider"></div>
            
            <!-- Customer Details -->
            <div class="details-card">
                <h3>Customer Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $inquiry->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $inquiry->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">{{ $inquiry->phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Inquiry Type</span>
                    <span class="detail-value">{{ $inquiry->inquiry_type_label }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Budget</span>
                    <span class="detail-value">{{ $inquiry->budget ?? 'Not specified' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Submitted</span>
                    <span class="detail-value">{{ $inquiry->created_at->format('F d, Y H:i') }}</span>
                </div>
                @if($inquiry->message)
                <div class="detail-row" style="flex-direction: column; border-bottom: none;">
                    <span class="detail-label" style="width: 100%; margin-bottom: 4px;">Message</span>
                    <div class="message-box">{{ $inquiry->message }}</div>
                </div>
                @endif
            </div>
            
            <!-- Action Button -->
            <div class="action-section">
                <a href="{{ url('/admin/inquiries/' . $inquiry->id) }}" class="btn-primary">
                    View Full Inquiry
                </a>
            </div>
            
            <div style="text-align: center; color: #718096; font-size: 14px; margin-top: 8px;">
                Please contact the customer as soon as possible.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="brand">{{ config('app.name', 'BuyProperty Kenya') }}</p>
            <p>Your Trusted Partner in Real Estate</p>
            <div class="contact">
                <a href="tel:{{ env('SALES_TEAM_PHONE', '+254702430127') }}">{{ env('SALES_TEAM_PHONE', '+254 702 430 127') }}</a>
                &nbsp;|&nbsp;
                <a href="mailto:{{ env('SALES_TEAM_EMAIL', 'sales@buypropertykenya.com') }}">{{ env('SALES_TEAM_EMAIL', 'sales@buypropertykenya.com') }}</a>
            </div>
            <p class="copyright">
                &copy; {{ date('Y') }} {{ config('app.name', 'BuyProperty Kenya') }}. All rights reserved.
                <br>
                <span style="font-size: 11px; color: #cbd5e0;">This is an automated notification. Please do not reply to this email.</span>
            </p>
        </div>
    </div>
</body>
</html>