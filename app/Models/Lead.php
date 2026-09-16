<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_method',
        'inquiry_type',
        'budget',
        'message',
        'status',
        'source',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function getInquiryTypeLabelAttribute()
    {
        $types = [
            'buying' => 'Buying a Property',
            'selling' => 'Selling a Property',
            'renting' => 'Renting a Property',
            'valuation' => 'Property Valuation',
            'consultation' => 'General Consultation',
        ];
        return $types[$this->inquiry_type] ?? $this->inquiry_type;
    }

    public function getContactMethodLabelAttribute()
    {
        $methods = [
            'phone' => '📞 Phone Call',
            'whatsapp' => '💬 WhatsApp',
            'email' => '✉️ Email',
        ];
        return $methods[$this->contact_method] ?? $this->contact_method;
    }

    public function getBudgetLabelAttribute()
    {
        $budgets = [
            '1-5M' => 'KSh 1M - 5M',
            '5-10M' => 'KSh 5M - 10M',
            '10-20M' => 'KSh 10M - 20M',
            '20-50M' => 'KSh 20M - 50M',
            '50M+' => 'KSh 50M+',
        ];
        return $budgets[$this->budget] ?? $this->budget;
    }
}