<!DOCTYPE html>
<html>
<head>
    <title>New Lead Assigned - Action Required</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .lead-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb; }
        .info-row { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .label { font-weight: bold; width: 120px; display: inline-block; }
        .action-buttons { margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .button-call { background: #10b981; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔔 New Lead Assigned</h2>
            <p>Action Required Within 24 Hours</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $agent ? $agent->user->name : 'Sales Team Member' }},</p>
            
            <p><strong>A new lead has been assigned to you. Please contact the customer within 24 hours.</strong></p>
            
            <div class="lead-box">
                <h3>Customer Information:</h3>
                
                <div class="info-row">
                    <span class="label">Name:</span> {{ $inquiry->name }}
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span> <strong style="color: #2563eb;">{{ $inquiry->phone }}</strong>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span> {{ $inquiry->email }}
                </div>
                <div class="info-row">
                    <span class="label">Inquiry Type:</span> {{ ucfirst($inquiry->inquiry_type) }}
                </div>
                @php
                    $metadata = json_decode($inquiry->metadata, true);
                @endphp
                @if(isset($metadata['budget']))
                <div class="info-row">
                    <span class="label">Budget:</span> KSh {{ $metadata['budget'] }}
                </div>
                @endif
                @if(isset($metadata['contact_method']))
                <div class="info-row">
                    <span class="label">Prefers:</span> {{ ucfirst($metadata['contact_method']) }}
                </div>
                @endif
                <div class="info-row">
                    <span class="label">Message:</span><br>
                    {{ $inquiry->message ?: 'No message provided' }}
                </div>
                <div class="info-row">
                    <span class="label">Submitted:</span> {{ $inquiry->created_at->format('F j, Y, g:i a') }}
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ url('/buypropertyadmin/inquiries/' . $inquiry->id . '/edit') }}" class="button">View in Admin</a>
                <a href="tel:{{ $inquiry->phone }}" class="button button-call">📞 Call Customer Now</a>
            </div>
            
            <p><strong>Action Checklist:</strong></p>
            <ul>
                <li>✅ Call customer within 24 hours</li>
                <li>✅ Update inquiry status in admin panel</li>
                <li>✅ Add notes about the conversation</li>
                <li>✅ Schedule follow-up if needed</li>
            </ul>
            
            <p style="margin-top: 20px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b;">
                <strong>⚠️ Reminder:</strong> The customer is expecting a call from our team. Please make contact as soon as possible.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} BuyProperty Kenya - Lead Management System</p>
            <p>This is an automated notification from your lead management system.</p>
        </div>
    </div>
</body>
</html>