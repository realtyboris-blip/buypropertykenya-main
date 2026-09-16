<?php

namespace App\Http\Controllers;

use App\Models\AreaGuide;
use App\Models\Property;
use Illuminate\Http\Request;

class AreaGuideController extends Controller
{
    /**
     * Display a listing of area guides
     */
    public function index()
    {
        $areaGuides = AreaGuide::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Add property count to each guide
        foreach ($areaGuides as $guide) {
            $guide->properties_count = Property::where('city', $guide->name)->count();
        }

        return view('media.area-guides', compact('areaGuides'));
    }

    /**
     * Display the specified area guide
     */
    public function show($slug)
    {
        $areaGuide = AreaGuide::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get properties in this area
        $properties = Property::where('city', $areaGuide->name)
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        // Get related area guides (excluding current)
        $relatedGuides = AreaGuide::where('is_active', true)
            ->where('id', '!=', $areaGuide->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('media.area-guide-detail', compact('areaGuide', 'properties', 'relatedGuides'));
    }
}
