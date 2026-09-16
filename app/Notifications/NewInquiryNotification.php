<?php
// app/Notifications/NewInquiryNotification.php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewInquiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📩 New Inquiry Received - BuyProperty Kenya')
            ->greeting('Hello Admin!')
            ->line('A new property inquiry has been submitted.')
            ->line('**Customer Details:**')
            ->line(" **Name:** {$this->inquiry->name}")
            ->line(" **Email:** {$this->inquiry->email}")
            ->line(" **Phone:** {$this->inquiry->phone}")
            ->line(" **Inquiry Type:** {$this->inquiry->inquiry_type_label}")
            ->line(" Budget:** " . ($this->inquiry->budget ?? 'Not specified'))
            ->line(" **Message:** " . ($this->inquiry->message ?? 'No message provided'))
            ->line(" **Submitted:** " . $this->inquiry->created_at->format('F d, Y H:i'))
            ->action('View Inquiry', url('/admin/inquiries/' . $this->inquiry->id))
            ->line('Please contact the customer as soon as possible.')
            ->line('Thank you for using BuyProperty Kenya!');
    }
}
