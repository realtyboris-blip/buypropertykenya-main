<?php
// app/Mail/LeadAutoReply.php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Inquiry - BuyProperty Kenya',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-auto-reply',
            with: [
                'inquiry' => $this->inquiry,
                'appName' => config('app.name', 'BuyProperty Kenya'),
            ],
        );
    }
}