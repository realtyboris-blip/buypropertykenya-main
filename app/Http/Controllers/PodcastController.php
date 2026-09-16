<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * Display a listing of podcasts
     */
    public function index()
    {
        $podcasts = Podcast::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get featured podcast
        $featuredPodcast = Podcast::where('status', 'published')
            ->where('is_featured', true)
            ->first();

        return view('media.podcasts', compact('podcasts', 'featuredPodcast'));
    }

    /**
     * Display the specified podcast
     */
    public function show($slug)
    {
        $podcast = Podcast::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $podcast->increment('views_count');

        // Get related podcasts (same host or similar tags)
        $relatedPodcasts = Podcast::where('status', 'published')
            ->where('id', '!=', $podcast->id)
            ->where(function($query) use ($podcast) {
                $query->where('host', $podcast->host)
                    ->orWhere(function($q) use ($podcast) {
                        if ($podcast->tags && is_array($podcast->tags)) {
                            foreach ($podcast->tags as $tag) {
                                $q->orWhereJsonContains('tags', $tag);
                            }
                        }
                    });
            })
            ->take(3)
            ->get();

        return view('media.podcast-detail', compact('podcast', 'relatedPodcasts'));
    }

    /**
     * Display podcasts by category/platform
     */
    public function filter(Request $request)
    {
        $type = $request->get('type');
        $platform = $request->get('platform');

        $query = Podcast::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($type) {
            $query->where('media_type', $type);
        }

        if ($platform) {
            $query->where('platform', $platform);
        }

        $podcasts = $query->orderBy('published_at', 'desc')->paginate(12);

        $featuredPodcast = Podcast::where('status', 'published')
            ->where('is_featured', true)
            ->first();

        return view('media.podcasts', compact('podcasts', 'featuredPodcast'));
    }

    /**
     * Search podcasts (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $podcasts = Podcast::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('host', 'like', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->get();

        return response()->json($podcasts);
    }

    /**
     * Get podcast embed URL
     */
    private function getEmbedUrl($url, $platform = null)
    {
        if (!$url) {
            return null;
        }

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

        // Spotify
        if (strpos($url, 'spotify.com') !== false) {
            // Convert to embed URL
            $parts = explode('/', $url);
            $episodeId = end($parts);
            return "https://open.spotify.com/embed/episode/{$episodeId}";
        }

        return $url;
    }
}