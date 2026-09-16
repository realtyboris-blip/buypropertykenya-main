<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'video_url',
        'video_title',
        'video_type',
        'category',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper method to get embed URL
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

    private function getYoutubeEmbedUrl(): string
    {
        // Extract video ID from various YouTube URL formats
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        preg_match($pattern, $this->video_url, $matches);
        $videoId = $matches[1] ?? '';
        
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $this->video_url;
    }

    private function getVimeoEmbedUrl(): string
    {
        // Extract video ID from Vimeo URL
        $pattern = '/vimeo\.com\/(?:channels\/|groups\/[^\/]+\/videos\/|album\/\d+\/video\/|video\/|)(\d+)/i';
        preg_match($pattern, $this->video_url, $matches);
        $videoId = $matches[1] ?? '';
        
        return $videoId ? "https://player.vimeo.com/video/{$videoId}" : $this->video_url;
    }

    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }
}