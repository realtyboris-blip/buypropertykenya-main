<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Property extends Model
{
    use HasSlug;

    protected $table = 'properties';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'price_period',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'property_type',
        'listing_type',
        'address',
        'city',
        'state',
        'zipcode',
        'latitude',
        'longitude',
        'amenities',
        'features',
        'images',
        'video_url',
        'video_thumbnail',
        'status',
        'is_featured',
        'is_verified',
        'agent_id',
        'views_count',
        'available_from',
        'property_type_id',
        'listing_type_id',
        'currency',
    ];

    protected $casts = [
        'amenities' => 'array',
        'features' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'available_from' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'currency' => 'string',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // Relationships
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function listingType()
    {
        return $this->belongsTo(ListingType::class);
    }

    // Image helpers - FIXED to properly return asset URLs
    public function getFirstImageAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            $image = $this->images[0];
            // Remove any duplicate storage prefix
            $image = str_replace('storage/', '', $image);
            return asset('storage/' . $image);
        }
        return asset('images/placeholder.jpg');
    }

    public function getFeaturedImageAttribute()
    {
        return $this->getFirstImageAttribute();
    }

    public function getAllImagesAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return array_map(function ($image) {
                $image = str_replace('storage/', '', $image);
                return asset('storage/' . $image);
            }, $this->images);
        }
        return [asset('images/placeholder.jpg')];
    }
    /**
     * Get the WhatsApp link for this property
     */
    public function getWhatsAppLinkAttribute()
    {
        $phone = Setting::get('contact_whatsapp', '+254700000000');
        $message = Setting::get('whatsapp_message_template', 'Hi, I am interested in your property: {property_title} in {property_city}');

        // Replace placeholders
        $message = str_replace(
            ['{property_title}', '{property_city}', '{property_url}'],
            [$this->title, $this->city, route('properties.show', $this->slug)],
            $message
        );

        return "https://wa.me/" . $this->cleanPhoneNumber($phone) . "?text=" . urlencode($message);
    }

    /**
     * Get the phone number for calling
     */
    public function getCallPhoneAttribute()
    {
        return Setting::get('contact_phone', '+254700000000');
    }

    /**
     * Clean phone number for WhatsApp
     */
    private function cleanPhoneNumber($phone)
    {
        // Remove all non-numeric characters except +
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    /**
     * Check if WhatsApp button should be shown
     */
    public function getShowWhatsAppButtonAttribute()
    {
        return Setting::get('show_whatsapp_button', true);
    }

    /**
     * Check if phone button should be shown
     */
    public function getShowPhoneButtonAttribute()
    {
        return Setting::get('show_phone_button', true);
    }

    /**
     * Get phone button label
     */
    public function getPhoneButtonLabelAttribute()
    {
        return Setting::get('phone_button_label', 'Call Now');
    }

    /**
     * Get WhatsApp button label
     */
    public function getWhatsAppButtonLabelAttribute()
    {
        return Setting::get('whatsapp_button_label', 'WhatsApp');
    }

    // Price helpers
    public function getFormattedPriceAttribute(): string
    {
        $currency = $this->currency ?? 'KES';
        $symbol = $this->getCurrencySymbol($currency);
        return $symbol . ' ' . number_format($this->price, 0);
    }

    public function getPriceWithPeriodAttribute(): string
    {
        $price = $this->getFormattedPriceAttribute();
        if ($this->price_period && $this->price_period !== 'one_time') {
            $price .= '/' . $this->price_period;
        }
        return $price;
    }

    public function getPriceKesAttribute(): string
    {
        return 'KSh ' . number_format($this->price, 0);
    }

    private function getCurrencySymbol($currency): string
    {
        $symbols = [
            'KES' => 'KSh',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];
        return $symbols[$currency] ?? $currency;
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeForSale($query)
    {
        return $query->whereHas('listingType', function ($q) {
            $q->where('slug', 'sale');
        })->orWhere('listing_type', 'sale');
    }

    public function scopeForRent($query)
    {
        return $query->whereHas('listingType', function ($q) {
            $q->where('slug', 'rent');
        })->orWhere('listing_type', 'rent');
    }

    // Increment view count
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Check if property is favorited by user
    public function isFavoritedByUser($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Get Video URL
     * Supports YouTube, Vimeo, and other video platforms
     */
    public function getVideoUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // Check if it's a YouTube URL
        if (strpos($value, 'youtube.com/watch') !== false || strpos($value, 'youtu.be') !== false) {
            return $this->getYouTubeEmbedUrl($value);
        }

        // Check if it's a Vimeo URL
        if (strpos($value, 'vimeo.com') !== false) {
            return $this->getVimeoEmbedUrl($value);
        }

        // Return as-is for other platforms
        return $value;
    }

    /**
     * Get YouTube Embed URL
     */
    private function getYouTubeEmbedUrl($url)
    {
        // Extract video ID from YouTube URL
        $videoId = null;

        // Handle youtu.be short URLs
        if (strpos($url, 'youtu.be') !== false) {
            $path = parse_url($url, PHP_URL_PATH);
            $videoId = ltrim($path, '/');
        }
        // Handle youtube.com/watch?v= URLs
        elseif (strpos($url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            $videoId = $params['v'] ?? null;
        }
        // Handle youtube.com/embed/ URLs
        elseif (strpos($url, 'youtube.com/embed') !== false) {
            $path = parse_url($url, PHP_URL_PATH);
            $videoId = basename($path);
        }

        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        return $url;
    }

    /**
     * Get Vimeo Embed URL
     */
    private function getVimeoEmbedUrl($url)
    {
        $videoId = basename(parse_url($url, PHP_URL_PATH));
        if ($videoId) {
            return "https://player.vimeo.com/video/{$videoId}";
        }
        return $url;
    }

    /**
     * Get Video Thumbnail URL
     */
    public function getVideoThumbnailAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }

        // Try to get thumbnail from video URL
        if ($this->video_url) {
            return $this->getVideoThumbnailFromUrl($this->video_url);
        }

        return null;
    }

    /**
     * Get Video Thumbnail from URL (YouTube, Vimeo, etc.)
     */
    private function getVideoThumbnailFromUrl($url)
    {
        // YouTube thumbnails
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            $videoId = null;

            if (strpos($url, 'youtu.be') !== false) {
                $path = parse_url($url, PHP_URL_PATH);
                $videoId = ltrim($path, '/');
            } elseif (strpos($url, 'youtube.com/watch') !== false) {
                parse_str(parse_url($url, PHP_URL_QUERY), $params);
                $videoId = $params['v'] ?? null;
            } elseif (strpos($url, 'youtube.com/embed') !== false) {
                $path = parse_url($url, PHP_URL_PATH);
                $videoId = basename($path);
            }

            if ($videoId) {
                return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
            }
        }

        // Vimeo thumbnails (requires API call, but we'll use a placeholder)
        if (strpos($url, 'vimeo.com') !== false) {
            return asset('images/video-placeholder.jpg');
        }

        return asset('images/video-placeholder.jpg');
    }

    /**
     * Check if property has video
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * Check if video is YouTube
     */
    public function isYouTubeVideo(): bool
    {
        return $this->video_url &&
            (strpos($this->video_url, 'youtube.com') !== false ||
                strpos($this->video_url, 'youtu.be') !== false);
    }

    /**
     * Check if video is Vimeo
     */
    public function isVimeoVideo(): bool
    {
        return $this->video_url && strpos($this->video_url, 'vimeo.com') !== false;
    }




    /**
     * Boot method to auto-create area guide when property is saved
     */
    protected static function booted()
    {
        static::saved(function ($property) {
            if ($property->city) {
                // Simple check and create if doesn't exist
                $guide = AreaGuide::firstOrCreate(
                    ['name' => $property->city],
                    [
                        'slug' => \Illuminate\Support\Str::slug($property->city),
                        'description' => "Discover the vibrant neighborhood of {$property->city}.",
                        'is_active' => true,
                    ]
                );
            }
        });
    }
}
