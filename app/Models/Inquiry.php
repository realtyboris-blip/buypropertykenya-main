<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $table = 'inquiries';

    protected $fillable = [
        'property_id',
        'project_id',
        'name',
        'email',
        'phone',
        'message',
        'inquiry_type',
        'preferred_date',
        'preferred_time',
        'status',
        'session_id',
        'ip_address',
        'metadata', 
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'metadata' => 'array', 

    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function getInquiryTypeLabelAttribute(): string
    {
        $types = [
            'viewing' => 'Property Viewing',
            'consultation' => 'Consultation',
            'mortgage' => 'Mortgage Info',
            'general' => 'General Inquiry'
        ];
        return $types[$this->inquiry_type] ?? 'General Inquiry';
    }
}