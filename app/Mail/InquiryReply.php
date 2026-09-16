<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReply extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $replyData;

    public function __construct(Inquiry $inquiry, array $replyData)
    {
        $this->inquiry = $inquiry;
        $this->replyData = $replyData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replyData['subject'] ?? 'Reply to your inquiry',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-reply',
            with: [
                'name' => $this->inquiry->name,
                'inquiry' => $this->inquiry,
                'reply' => $this->replyData['reply_message'],
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}