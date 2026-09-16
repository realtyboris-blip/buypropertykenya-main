<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Agent;
use App\Models\PropertyType;
use App\Models\ListingType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured properties for the main slider (dynamic)
        $featuredPropertiesForSlider = Property::with(['listingType'])
            ->where('is_featured', true)
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($property, $index) {
                $badges = [
                    'LUXURY LIVING',
                    'EXCLUSIVE HOMES',
                    'FAMILY ESTATES',
                    'PRIME INVESTMENT',
                    'BEACHFRONT',
                    'COMMERCIAL'
                ];

                $catchyPhrases = [
                    'Experience Elegance Redefined',
                    'Where Luxury Meets Comfort',
                    'Your Perfect Family Haven',
                    'Smart Investment Opportunity',
                    'Paradise Found',
                    'Prime Business Location'
                ];

                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'image' => $property->getFirstImageAttribute(),
                    'badge' => $badges[$index % count($badges)],
                    'catchyPhrase' => $catchyPhrases[$index % count($catchyPhrases)],
                    'detailsUrl' => route('properties.show', $property->slug),
                    'hasVideo' => $property->hasVideo(),
                    'videoUrl' => $property->video_url,
                    'videoThumbnail' => $property->video_thumbnail,
                ];
            });

        // Get featured properties for the grid
        $featuredProperties = Property::with(['propertyType', 'listingType', 'agent.user'])
            ->where('is_featured', true)
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        // Get data for filter
        $propertyTypes = PropertyType::where('is_active', true)->orderBy('name')->get();
        $listingTypes = ListingType::where('is_active', true)->orderBy('name')->get();

        // Get distinct locations from properties
        $locations = Property::where('status', 'available')
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->values()
            ->toArray();

        // Get property count for filter
        $propertiesCount = Property::where('status', 'available')->count();

        // Statistics
        $totalProperties = Property::count();
        $totalPropertiesSold = Property::where('status', 'sold')->count();
        $totalAgents = Agent::count();
        $happyClients = $totalPropertiesSold * 2;

        return view('home', compact(
            'featuredPropertiesForSlider',
            'featuredProperties',
            'propertyTypes',
            'listingTypes',
            'locations',
            'propertiesCount',
            'totalProperties',
            'totalPropertiesSold',
            'totalAgents',
            'happyClients'
        ));
    }
}
