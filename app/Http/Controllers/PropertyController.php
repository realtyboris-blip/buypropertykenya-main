<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\ListingType;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['propertyType', 'listingType', 'agent.user'])
            ->where('status', 'available');

        // Apply filters
        if ($request->filled('location')) {
            $query->where(function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                    ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->filled('property_type')) {
            $propertyType = PropertyType::where('slug', $request->property_type)->first();
            if ($propertyType) {
                $query->where('property_type_id', $propertyType->id);
            }
        }

        if ($request->filled('listing_type')) {
            $listingType = ListingType::where('slug', $request->listing_type)->first();
            if ($listingType) {
                $query->where('listing_type_id', $listingType->id);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        // Add price filter
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->latest()->paginate(12);

        // Get filter data
        $propertyTypes = PropertyType::where('is_active', true)->orderBy('sort_order')->get();
        $listingTypes = ListingType::where('is_active', true)->orderBy('sort_order')->get();

        // Get distinct locations from properties
        $locations = Property::where('status', 'available')
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->values()
            ->toArray();

        return view('properties.index', compact('properties', 'propertyTypes', 'listingTypes', 'locations'));
    }

    public function show($slug)
    {
        $property = Property::with(['propertyType', 'listingType', 'agent.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $property->incrementViews();

        $similarProperties = Property::where('city', $property->city)
            ->where('id', '!=', $property->id)
            ->where('status', 'available')
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }
}
