<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'developer',
        'starting_price',
        'max_price',
        'completion_date',
        'location',
        'city',
        'floor_plans',
        'amenities',
        'gallery',
        'status',
        'total_units',
        'available_units',
        'is_featured',
        'cover_image',
        'video_url',
    ];

    protected $casts = [
        'floor_plans' => 'array',
        'amenities' => 'array',
        'gallery' => 'array',
        'starting_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'completion_date' => 'date',
        'is_featured' => 'boolean',
    ];

    /**
     * Get amenities as array (decodes JSON)
     */
    public function getAmenitiesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return $value ?? [];
    }

    /**
     * Set amenities as JSON string
     */
    public function setAmenitiesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['amenities'] = json_encode($value);
        } else {
            $this->attributes['amenities'] = $value;
        }
    }

    /**
     * Get gallery as array (decodes JSON)
     */
    public function getGalleryAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return $value ?? [];
    }

    /**
     * Set gallery as JSON string
     */
    public function setGalleryAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['gallery'] = json_encode($value);
        } else {
            $this->attributes['gallery'] = $value;
        }
    }

    /**
     * Get floor plans as array (decodes JSON)
     */
    public function getFloorPlansAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return $value ?? [];
    }

    /**
     * Set floor plans as JSON string
     */
    public function setFloorPlansAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['floor_plans'] = json_encode($value);
        } else {
            $this->attributes['floor_plans'] = $value;
        }
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the inquiries for this project.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get formatted starting price.
     */
    public function getFormattedStartingPriceAttribute(): string
    {
        return 'KSh ' . number_format($this->starting_price, 0);
    }

    /**
     * Get formatted max price.
     */
    public function getFormattedMaxPriceAttribute(): string
    {
        if ($this->max_price) {
            return 'KSh ' . number_format($this->max_price, 0);
        }
        return 'N/A';
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'off-plan' => 'Off-Plan',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get progress percentage for ongoing projects.
     */
    public function getProgressPercentageAttribute(): int
    {
        if ($this->status === 'completed') {
            return 100;
        }
        if ($this->status === 'off-plan') {
            return 0;
        }
        if ($this->total_units > 0 && $this->available_units !== null) {
            $sold = $this->total_units - $this->available_units;
            return round(($sold / $this->total_units) * 100);
        }
        return 50;
    }

    /**
     * Get cover image URL.
     */
    public function getCoverImageAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset('storage/' . $value);
        }
        return asset('images/project-placeholder.jpg');
    }

    /**
     * Get first image from gallery.
     */
    public function getFirstImageAttribute()
    {
        $gallery = $this->gallery;
        
        if ($gallery && is_array($gallery) && count($gallery) > 0) {
            $image = $gallery[0];
            $image = str_replace('storage/', '', $image);
            return asset('storage/' . $image);
        }
        return asset('images/project-placeholder.jpg');
    }

    /**
     * Get all gallery images with full URLs.
     */
    public function getGalleryUrlsAttribute()
    {
        $gallery = $this->gallery;
        
        if ($gallery && is_array($gallery) && count($gallery) > 0) {
            return array_map(function($image) {
                $image = str_replace('storage/', '', $image);
                return asset('storage/' . $image);
            }, $gallery);
        }
        return [];
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'ongoing' => 'amber',
            'completed' => 'emerald',
            'off-plan' => 'blue',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Scopes
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOffPlan($query)
    {
        return $query->where('status', 'off-plan');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug if not set
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = \Illuminate\Support\Str::slug($project->name);
            }
        });
    }
}