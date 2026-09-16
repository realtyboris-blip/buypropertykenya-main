<?php
// app/Jobs/SendLeadAutoReply.php

namespace App\Jobs;

use App\Models\Inquiry;
use App\Mail\LeadAutoReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLeadAutoReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function handle(): void
    {
        Mail::to($this->inquiry->email)->send(new LeadAutoReply($this->inquiry));
    }
}