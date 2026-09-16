<?php

namespace App\Http\Controllers;

use App\Models\HouseTour;
use App\Models\Property;
use Illuminate\Http\Request;

class HouseTourController extends Controller
{
    /**
     * Display a listing of house tours
     */
    public function index()
    {
        $houseTours = HouseTour::with(['property'])
            ->where('status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('media.house-tours', compact('houseTours'));
    }

    /**
     * Display the specified house tour
     */
    public function show($id)
    {
        $tour = HouseTour::with(['property'])
            ->where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $tour->increment('views_count');

        // Get related tours (same property or similar)
        $relatedTours = HouseTour::where('status', 'published')
            ->where('id', '!=', $tour->id)
            ->where(function($query) use ($tour) {
                $query->where('property_id', $tour->property_id)
                    ->orWhereHas('property', function($q) use ($tour) {
                        $q->where('city', $tour->property?->city);
                    });
            })
            ->take(3)
            ->get();

        return view('media.house-tour-detail', compact('tour', 'relatedTours'));
    }

    /**
     * Get embed URL from video URL
     */
    private function getEmbedUrl($url)
    {
        if (!$url) return null;

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
}