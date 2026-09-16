<?php

namespace App\Providers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\ListingType;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share navigation data with all views that include the navigation
        View::composer('layouts.navigation', function ($view) {
            // Get property types
            $propertyTypes = PropertyType::where('is_active', true)->orderBy('name')->get();
            
            // Get listing types
            $listingTypes = ListingType::where('is_active', true)->orderBy('name')->get();
            
            // Get locations for For Sale
            $saleType = ListingType::where('slug', 'sale')->first();
            $locations = Property::where('status', 'available')
                ->when($saleType, function($q) use ($saleType) {
                    return $q->where('listing_type_id', $saleType->id);
                })
                ->select('city')
                ->distinct()
                ->pluck('city')
                ->filter()
                ->values()
                ->toArray();
            
            // Get locations for For Rent
            $rentType = ListingType::where('slug', 'rent')->first();
            $rentLocations = Property::where('status', 'available')
                ->when($rentType, function($q) use ($rentType) {
                    return $q->where('listing_type_id', $rentType->id);
                })
                ->select('city')
                ->distinct()
                ->pluck('city')
                ->filter()
                ->values()
                ->toArray();
            
            $view->with([
                'propertyTypes' => $propertyTypes,
                'listingTypes' => $listingTypes,
                'locations' => $locations,
                'rentLocations' => $rentLocations,
            ]);
        });
    }

    public function register(): void
    {
        //
    }
}