<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class AreaGuide extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'featured_image',
        'video_url',
        'video_title',
        'video_type',
        'gallery_images',
        'amenities',
        'nearby_places',
        'transport',
        'coordinates',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'amenities' => 'array',
        'nearby_places' => 'array',
        'transport' => 'array',
        'coordinates' => 'array',
        'gallery_images' => 'array',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get properties in this area
     */
    public function properties()
    {
        return \App\Models\Property::where('city', $this->name);
    }

    /**
     * Check if area guide has a video
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * Get the video embed URL
     */
    public function getVideoEmbedUrl(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        return match ($this->video_type) {
            'youtube' => $this->getYoutubeEmbedUrl(),
            'vimeo' => $this->getVimeoEmbedUrl(),
            default => $this->video_url,
        };
    }

    /**
     * Get YouTube embed URL
     */
    private function getYoutubeEmbedUrl(): string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        preg_match($pattern, $this->video_url, $matches);
        $videoId = $matches[1] ?? '';

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $this->video_url;
    }

    /**
     * Get Vimeo embed URL
     */
    private function getVimeoEmbedUrl(): string
    {
        $pattern = '/vimeo\.com\/(?:channels\/|groups\/[^\/]+\/videos\/|album\/\d+\/video\/|video\/|)(\d+)/i';
        preg_match($pattern, $this->video_url, $matches);
        $videoId = $matches[1] ?? '';

        return $videoId ? "https://player.vimeo.com/video/{$videoId}" : $this->video_url;
    }

    /**
     * Get YouTube video ID
     */
    public function getYoutubeVideoId(): ?string
    {
        if ($this->video_type !== 'youtube') {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        preg_match($pattern, $this->video_url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Get video thumbnail URL
     */
    public function getVideoThumbnailUrl(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        return match ($this->video_type) {
            'youtube' => "https://img.youtube.com/vi/{$this->getYoutubeVideoId()}/hqdefault.jpg",
            'vimeo' => $this->getVimeoThumbnailUrl(),
            default => $this->featured_image ?? null,
        };
    }

    private function getVimeoThumbnailUrl(): ?string
    {
        // For Vimeo, you'd need to use their API
        // This is a simplified approach - you may want to cache this
        try {
            $videoId = $this->getVimeoVideoId();
            if (!$videoId) return null;

            $response = file_get_contents("https://vimeo.com/api/v2/video/{$videoId}.json");
            $data = json_decode($response, true);
            return $data[0]['thumbnail_large'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getVimeoVideoId(): ?string
    {
        $pattern = '/vimeo\.com\/(?:channels\/|groups\/[^\/]+\/videos\/|album\/\d+\/video\/|video\/|)(\d+)/i';
        preg_match($pattern, $this->video_url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Get all images including gallery
     */
    public function getAllImages(): array
    {
        $images = [];

        if ($this->featured_image) {
            $images[] = $this->featured_image;
        }

        if ($this->gallery_images) {
            $images = array_merge($images, $this->gallery_images);
        }

        return $images;
    }

    /**
     * Get video for embedding
     */
    public function getVideoHtml(array $attributes = []): ?string
    {
        if (!$this->hasVideo()) {
            return null;
        }

        $defaultAttributes = [
            'class' => 'w-full rounded-lg shadow-lg',
            'frameborder' => '0',
            'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
            'allowfullscreen' => true,
        ];

        $attributes = array_merge($defaultAttributes, $attributes);
        $attrString = collect($attributes)
            ->map(fn($value, $key) => is_bool($value) ? $key : "{$key}=\"{$value}\"")
            ->implode(' ');

        if ($this->video_type === 'local') {
            return "<video controls {$attrString}>
                <source src=\"" . asset('storage/' . $this->video_url) . "\" type=\"video/mp4\">
                Your browser does not support the video tag.
            </video>";
        }

        return "<iframe src=\"{$this->getVideoEmbedUrl()}\" {$attrString}></iframe>";
    }
}
