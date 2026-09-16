<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseTour extends Model
{
    protected $fillable = [
        'property_id',
        'title',
        'description',
        'video_url',
        'thumbnail',  // Make sure this is here
        'duration',
        'is_featured',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
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

    /**
     * Get video embed URL
     */
    public function getEmbedUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        // YouTube
        if (strpos($this->video_url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($this->video_url, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                return "https://www.youtube.com/embed/{$params['v']}";
            }
        }

        // YouTube Short URL
        if (strpos($this->video_url, 'youtu.be') !== false) {
            $videoId = basename(parse_url($this->video_url, PHP_URL_PATH));
            return "https://www.youtube.com/embed/{$videoId}";
        }

        // Vimeo
        if (strpos($this->video_url, 'vimeo.com') !== false) {
            $videoId = basename(parse_url($this->video_url, PHP_URL_PATH));
            return "https://player.vimeo.com/video/{$videoId}";
        }

        return $this->video_url;
    }

    /**
     * Get video thumbnail - FIXED
     */
    public function getThumbnailAttribute()
    {
        // Check if there's a custom thumbnail image uploaded
        if ($this->attributes['thumbnail'] ?? null) {
            return asset('storage/' . $this->attributes['thumbnail']);
        }

        // Auto-generate YouTube thumbnail
        if ($this->video_url && strpos($this->video_url, 'youtube.com') !== false) {
            $videoId = $this->getYouTubeVideoId($this->video_url);
            if ($videoId) {
                return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
            }
        }

        // Auto-generate Vimeo thumbnail (using Vimeo's thumbnail API)
        if ($this->video_url && strpos($this->video_url, 'vimeo.com') !== false) {
            $videoId = basename(parse_url($this->video_url, PHP_URL_PATH));
            return "https://vumbnail.com/{$videoId}.jpg";
        }

        // Use property image as fallback
        if ($this->property && $this->property->getFirstImageAttribute()) {
            return $this->property->getFirstImageAttribute();
        }

        return asset('images/video-placeholder.jpg');
    }

    /**
     * Extract YouTube video ID
     */
    private function getYouTubeVideoId($url)
    {
        if (strpos($url, 'youtu.be') !== false) {
            return basename(parse_url($url, PHP_URL_PATH));
        }
        if (strpos($url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            return $params['v'] ?? null;
        }
        if (strpos($url, 'youtube.com/embed') !== false) {
            return basename(parse_url($url, PHP_URL_PATH));
        }
        return null;
    }

    /**
     * Get duration in human-readable format
     */
    public function getDurationFormattedAttribute()
    {
        if (!$this->duration) {
            return null;
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }
        return "{$seconds}s";
    }
}