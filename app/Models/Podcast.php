<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;

class Podcast extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'media_type',
        'platform',
        'audio_url',
        'video_url',
        'video_thumbnail',
        'embed_url',
        'cover_image',
        'duration',
        'host',
        'tags',
        'is_featured',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get tags as array safely
     */
    public function getTagsAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return $value ?? [];
    }

    /**
     * Set tags as JSON
     */
    public function setTagsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['tags'] = json_encode($value);
        } else {
            $this->attributes['tags'] = $value;
        }
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
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
     * Get the media URL (audio or video)
     */
    public function getMediaUrlAttribute()
    {
        if ($this->media_type === 'video' && $this->video_url) {
            return $this->video_url;
        }
        return $this->audio_url;
    }

    /**
     * Get embed URL (YouTube, Vimeo, etc.)
     */
    public function getEmbedUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Auto-generate embed URL from video URL
        if ($this->video_url) {
            return $this->getEmbedUrlFromVideo($this->video_url);
        }
        
        return null;
    }

    /**
     * Generate embed URL from video URL
     */
    private function getEmbedUrlFromVideo($url)
    {
        // YouTube
        if (strpos($url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                return "https://www.youtube.com/embed/{$params['v']}";
            }
        }
        
        // YouTube Short URL
        if (strpos($url, 'youtu.be') !== false) {
            $videoId = basename(parse_url($url, PHP_URL_PATH));
            return "https://www.youtube.com/embed/{$videoId}";
        }
        
        // Vimeo
        if (strpos($url, 'vimeo.com') !== false) {
            $videoId = basename(parse_url($url, PHP_URL_PATH));
            return "https://player.vimeo.com/video/{$videoId}";
        }
        
        return $url;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailAttribute()
    {
        if ($this->media_type === 'video' && $this->video_thumbnail) {
            return asset('storage/' . $this->video_thumbnail);
        }
        
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        
        // Auto-generate YouTube thumbnail
        if ($this->video_url && strpos($this->video_url, 'youtube.com') !== false) {
            $videoId = $this->getYouTubeVideoId($this->video_url);
            if ($videoId) {
                return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
            }
        }
        
        return asset('images/podcast-placeholder.jpg');
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
        
        $parts = explode(':', $this->duration);
        if (count($parts) === 3) {
            return "{$parts[0]}h {$parts[1]}m {$parts[2]}s";
        }
        if (count($parts) === 2) {
            return "{$parts[0]}m {$parts[1]}s";
        }
        return "{$this->duration}s";
    }

    /**
     * Get platform icon
     */
    public function getPlatformIconAttribute()
    {
        $icons = [
            'youtube' => 'fab fa-youtube',
            'vimeo' => 'fab fa-vimeo-v',
            'spotify' => 'fab fa-spotify',
            'apple' => 'fab fa-apple',
            'soundcloud' => 'fab fa-soundcloud',
            'google' => 'fab fa-google-podcast',
        ];
        
        $platform = strtolower($this->platform ?? '');
        return $icons[$platform] ?? 'fas fa-microphone';
    }
}