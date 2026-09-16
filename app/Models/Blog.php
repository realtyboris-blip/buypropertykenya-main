<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'blog_category_id',
        'user_id',
        'status',
        'published_at',
        'is_featured',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_type',
        'schema_data',
        'reading_time', // Make sure this is here
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'schema_data' => 'array',
        'reading_time' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(255)
            ->usingSeparator('-')
            ->allowDuplicateSlugs(false);
    }

    // Add this method to ensure unique slug
    protected static function booted()
    {
        static::creating(function ($blog) {
            $baseSlug = Str::slug($blog->title);
            $slug = $baseSlug;
            $counter = 1;

            while (Blog::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $blog->slug = $slug;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_blog_tag');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getReadingTimeAttribute()
    {
        // Check if reading_time is already set in the database
        if ($this->attributes['reading_time'] ?? null) {
            return $this->attributes['reading_time'];
        }

        // Calculate reading time
        $content = strip_tags($this->content ?? '');
        $words = str_word_count($content);
        $minutes = max(1, ceil($words / 200)); // Minimum 1 minute

        // Store the calculated value
        if ($this->exists) {
            $this->update(['reading_time' => $minutes]);
        }

        return $minutes;
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return Str::limit(strip_tags($this->content ?? ''), 150);
    }

    // SEO Helper Methods
    public function getMetaTitleAttribute($value)
    {
        return $value ?? $this->title . ' | BuyProperty Kenya';
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?? Str::limit(strip_tags($this->excerpt ?? $this->content ?? ''), 160);
    }

    public function getOgImageAttribute($value)
    {
        return $value ?? $this->featured_image;
    }

    // Increment views
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
