<?php
// app/Http/Controllers/LeadController.php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Mail\LeadAutoReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendLeadAutoReply; 

class LeadController extends Controller
{
    /**
     * Show the lead form
     */
    public function create()
    {
        return view('lead.lead-form');
    }

    /**
     * Store a new lead
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'contact_method' => 'nullable|string|in:phone,whatsapp,email',
            'inquiry_type' => 'required|string|in:buying,selling,renting,valuation,consultation',
            'budget' => 'nullable|string|max:50',
            'message' => 'nullable|string',
            'agree' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Map inquiry type from lead form to inquiry resource
            $inquiryTypeMap = [
                'buying' => 'viewing',
                'selling' => 'valuation',
                'renting' => 'viewing',
                'valuation' => 'valuation',
                'consultation' => 'consultation',
            ];

            // Create inquiry
            $inquiry = Inquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'inquiry_type' => $inquiryTypeMap[$request->inquiry_type] ?? 'general',
                'budget' => $request->budget,
                'message' => $request->message,
                'status' => 'pending',
                'source' => 'website_lead_form',
                'ip_address' => $request->ip(),
                'session_id' => session()->getId(),
                'metadata' => [
                    'contact_method' => $request->contact_method ?? 'phone',
                    'original_inquiry_type' => $request->inquiry_type,
                    'user_agent' => $request->userAgent(),
                    'referrer' => $request->header('referer'),
                ],
            ]);

            // Log that inquiry was created
            Log::info('Inquiry created with ID: ' . $inquiry->id);

            // Send auto-reply email
            $emailSent = false;
            try {
                // If you have this in your controller:
                SendLeadAutoReply::dispatch($inquiry);
                $emailSent = true;
                Log::info('Auto-reply email sent to: ' . $inquiry->email);
            } catch (\Exception $mailError) {
                Log::error('Failed to send auto-reply email: ' . $mailError->getMessage());
                Log::error('Mail error: ' . $mailError->getTraceAsString());
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Your request has been submitted successfully! A confirmation email has been sent to your email address.'
                    : 'Your request has been submitted successfully! However, we encountered an issue sending the confirmation email.',
                'email' => $inquiry->email,
                'inquiry_id' => $inquiry->id,
                'email_sent' => $emailSent
            ]);

        } catch (\Exception $e) {
            Log::error('Lead submission error: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your request. Please try again.'
            ], 500);
        }
    }
}