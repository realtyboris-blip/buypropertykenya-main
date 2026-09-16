<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Agent extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'bio',
        'phone',
        'photo',
        'social_links',
        'rating',
        'total_reviews',
        'properties_sold',
        'specialization',
        'is_featured'
    ];

    protected $casts = [
        'social_links' => 'array',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown Agent';
    }

    public function getEmailAttribute(): string
    {
        return $this->user->email ?? '';
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    // Boot method to handle agent creation
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($agent) {
            if (!$agent->user_id) {
                // Create a user if one doesn't exist
                $user = User::create([
                    'name' => 'New Agent',
                    'email' => 'agent' . time() . '@example.com',
                    'password' => Hash::make('password123'),
                ]);
                $agent->user_id = $user->id;
            }
        });
    }
}