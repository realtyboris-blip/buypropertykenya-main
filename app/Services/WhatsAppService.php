<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $enabled;
    protected $apiKey;
    protected $phoneNumberId;
    
    public function __construct()
    {
        $this->enabled = env('WHATSAPP_ENABLED', false);
        $this->apiKey = env('WHATSAPP_API_KEY');
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
        
        if ($this->enabled && (!$this->apiKey || !$this->phoneNumberId)) {
            Log::warning('WhatsApp credentials missing');
            $this->enabled = false;
        }
    }
    
    /**
     * Send lead notification via WhatsApp
     */
    public function sendLeadNotification($lead)
    {
        if (!$this->enabled) {
            Log::info('WhatsApp is disabled');
            return false;
        }
        
        try {
            $to = env('SALES_TEAM_WHATSAPP', '+254700000000');
            $metadata = json_decode($lead->metadata, true);
            
            $message = $this->formatWhatsAppMessage($lead, $metadata);
            
            Log::info('Sending WhatsApp notification', [
                'lead_id' => $lead->id,
                'to' => $to
            ]);
            
            // Facebook/Meta WhatsApp Business API
            $response = Http::withToken($this->apiKey)
                ->post("https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => ['body' => $message]
                ]);
            
            if ($response->successful()) {
                Log::info('WhatsApp notification sent', ['lead_id' => $lead->id]);
                return true;
            } else {
                Log::error('WhatsApp API error', [
                    'lead_id' => $lead->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage(), [
                'lead_id' => $lead->id ?? null
            ]);
            return false;
        }
    }
    
    /**
     * Send bulk WhatsApp notifications
     */
    public function sendBulkLeadNotification($lead, array $phoneNumbers)
    {
        if (!$this->enabled || empty($phoneNumbers)) {
            return false;
        }
        
        $results = [];
        foreach ($phoneNumbers as $phone) {
            $results[] = $this->sendLeadNotification($lead);
        }
        
        return !in_array(false, $results);
    }
    
    /**
     * Format WhatsApp message
     */
    private function formatWhatsAppMessage($lead, $metadata = [])
    {
        $inquiryTypes = [
            'buying' => ' Buying',
            'selling' => ' Selling',
            'renting' => ' Renting',
            'valuation' => 'Valuation',
            'consultation' => ' Consultation'
        ];
        
        $type = $inquiryTypes[$lead->inquiry_type] ?? $lead->inquiry_type;
        $budget = $metadata['budget'] ?? 'Not specified';
        
        return sprintf(
            "* NEW LEAD - Action Required!*\n\n" .
            "*Name:* %s\n" .
            "*Type:* %s\n" .
            "*Phone:* %s\n" .
            "*Budget:* KES %s\n" .
            "*Message:* %s\n" .
            "*Lead ID:* #%d\n\n" .
            "*Please call within 24 hours!*\n" .
            "Admin: %s",
            $lead->name,
            $type,
            $lead->phone,
            $budget,
            substr($lead->message ?? 'No message', 0, 100),
            $lead->id,
            url("/buypropertyadmin/inquiries/{$lead->id}/edit")
        );
    }
}