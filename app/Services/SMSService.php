<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $client;
    protected $sms;
    protected $enabled;
    
    public function __construct()
    {
        $this->enabled = env('SMS_ENABLED', false);
        
        if ($this->enabled) {
            try {
                $username = env('AFRICASTALKING_USERNAME', 'sandbox');
                $apiKey = env('AFRICASTALKING_API_KEY');
                
                if ($username && $apiKey) {
                    $this->client = new AfricasTalking($username, $apiKey);
                    $this->sms = $this->client->sms();
                    Log::info('SMS Service initialized');
                } else {
                    Log::warning('SMS credentials missing');
                    $this->enabled = false;
                }
            } catch (\Exception $e) {
                Log::error('SMS Service init failed: ' . $e->getMessage());
                $this->enabled = false;
            }
        }
    }
    
    public function sendLeadNotification($lead)
    {
        if (!$this->enabled) {
            Log::info('SMS not enabled');
            return false;
        }
        
        try {
            $phoneNumber = env('SALES_TEAM_PHONE', '+254700000000');
            $metadata = json_decode($lead->metadata, true);
            
            // Format message
            $message = $this->formatLeadMessage($lead, $metadata);
            
            // Format phone number
            $to = $this->formatPhoneNumber($phoneNumber);
            
            Log::info('Sending SMS', ['to' => $to]);
            
            $result = $this->sms->send([
                'to' => $to,
                'message' => $message,
                'from' => env('SMS_SENDER_ID', 'sandbox')
            ]);
            
            Log::info('SMS sent', ['result' => $result]);
            return true;
            
        } catch (\Exception $e) {
            Log::error('SMS send failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendBulkLeadNotification($lead, array $phoneNumbers)
    {
        if (!$this->enabled || empty($phoneNumbers)) {
            return false;
        }
        
        try {
            $metadata = json_decode($lead->metadata, true);
            $message = $this->formatLeadMessage($lead, $metadata);
            
            // Format all phone numbers
            $formattedNumbers = array_map([$this, 'formatPhoneNumber'], $phoneNumbers);
            
            $result = $this->sms->send([
                'to' => $formattedNumbers,
                'message' => $message,
                'from' => env('SMS_SENDER_ID', 'sandbox')
            ]);
            
            Log::info('Bulk SMS sent');
            return true;
            
        } catch (\Exception $e) {
            Log::error('Bulk SMS failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendTestSMS($phoneNumber)
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'SMS not enabled'];
        }
        
        try {
            $to = $this->formatPhoneNumber($phoneNumber);
            
            $result = $this->sms->send([
                'to' => $to,
                'message' => 'Test SMS from BuyProperty Kenya',
                'from' => env('SMS_SENDER_ID', 'sandbox')
            ]);
            
            return ['success' => true, 'result' => $result];
            
        } catch (\Exception $e) {
            Log::error('Test SMS failed: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function formatLeadMessage($lead, $metadata = [])
    {
        $inquiryTypes = [
            'buying' => 'Buying',
            'selling' => 'Selling', 
            'renting' => 'Renting',
            'valuation' => 'Valuation',
            'consultation' => 'Consult'
        ];
        
        $type = $inquiryTypes[$lead->inquiry_type] ?? $lead->inquiry_type;
        $budget = $metadata['budget'] ?? 'N/A';
        
        return sprintf(
            "🔔 NEW LEAD!\n%s (%s)\n📞 %s\n💰 Budget: KES %s\n🆔 Lead #%d",
            $lead->name,
            $type,
            $lead->phone,
            $budget,
            $lead->id
        );
    }
    
    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If number starts with 0, replace with 254
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }
        
        // If number is 9 digits, add 254 prefix
        if (strlen($phone) === 9) {
            $phone = '254' . $phone;
        }
        
        return '+' . $phone;
    }
}