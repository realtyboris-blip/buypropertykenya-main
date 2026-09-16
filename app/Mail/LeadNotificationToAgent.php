<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadNotificationToAgent extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $agent;

    public function __construct(Inquiry $inquiry, $agent = null)
    {
        $this->inquiry = $inquiry;
        $this->agent = $agent;
    }

    public function envelope(): Envelope
    {
        $subject = $this->agent 
            ? '📞 New Lead Assigned - Call Customer Within 24 Hours'
            : '📞 New Lead Received - Action Required';
            
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-notification-agent',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}