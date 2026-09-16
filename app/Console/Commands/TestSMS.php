<?php

namespace App\Console\Commands;

use App\Services\SMSService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestSMS extends Command
{
    protected $signature = 'sms:test {phone?}';
    protected $description = 'Test SMS integration';

    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    public function handle()
    {
        $phone = $this->argument('phone') ?? env('SALES_TEAM_PHONE', '+254700000000');
        
        $this->info("Testing SMS configuration...");
        $this->info("Phone: {$phone}");
        $this->info("Username: " . env('AFRICASTALKING_USERNAME', 'not set'));
        $this->info("API Key: " . (env('AFRICASTALKING_API_KEY') ? '✓ Set' : '✗ Missing'));
        $this->info("SMS Enabled: " . (env('SMS_ENABLED') ? 'Yes' : 'No'));
        
        if (!env('SMS_ENABLED')) {
            $this->error("SMS is disabled. Set SMS_ENABLED=true in .env");
            return;
        }
        
        if (!env('AFRICASTALKING_API_KEY')) {
            $this->error("API Key is missing. Set AFRICASTALKING_API_KEY in .env");
            $this->info("\nTo get your API key:");
            $this->info("1. Login to https://account.africastalking.com");
            $this->info("2. Go to 'API Key' tab");
            $this->info("3. Copy your sandbox API key");
            $this->info("4. Add to .env file");
            return;
        }
        
        $this->info("\nSending test SMS...");
        
        // Use the SMSService to send
        $result = $this->smsService->sendTestSMS($phone);
        
        if ($result['success']) {
            $this->info('✓ SMS sent successfully!');
        } else {
            $this->error('✗ SMS failed: ' . $result['message']);
            
            $this->info("\nTroubleshooting tips:");
            $this->info("1. Use 'sandbox' as username for testing");
            $this->info("2. In sandbox mode, you can only send to numbers you've added to the sandbox");
            $this->info("3. Your API key might be incorrect - get a new one from dashboard");
            $this->info("4. Check if your account has sufficient credit");
        }
    }
}