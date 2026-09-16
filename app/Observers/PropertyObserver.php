<?php

namespace App\Observers;

use App\Models\Property;
use App\Models\HouseTour;
use Illuminate\Support\Str;

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        // Auto-create house tour if video_url is present
        if ($property->video_url) {
            $this->createHouseTourFromProperty($property);
        }
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        // Check if video_url was added, changed, or removed
        if ($property->isDirty('video_url')) {
            $oldVideo = $property->getOriginal('video_url');
            $newVideo = $property->video_url;
            
            // If video was removed, delete the house tour
            if (empty($newVideo) && !empty($oldVideo)) {
                HouseTour::where('property_id', $property->id)->delete();
                return;
            }
            
            // If video was added or changed, create/update house tour
            if (!empty($newVideo)) {
                $existingTour = HouseTour::where('property_id', $property->id)->first();
                
                if ($existingTour) {
                    // Update existing tour
                    $this->updateHouseTourFromProperty($existingTour, $property);
                } else {
                    // Create new tour
                    $this->createHouseTourFromProperty($property);
                }
            }
        }
    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        // Delete associated house tour when property is deleted
        HouseTour::where('property_id', $property->id)->delete();
    }

    /**
     * Create a house tour from property data
     */
    private function createHouseTourFromProperty(Property $property): void
    {
        // Check if house tour already exists
        if (HouseTour::where('property_id', $property->id)->exists()) {
            return;
        }

        HouseTour::create([
            'property_id' => $property->id,
            'title' => $property->title . ' - Virtual Tour',
            'description' => 'Take a virtual tour of this stunning property. Explore every corner from the comfort of your home.',
            'video_url' => $property->video_url,
            'thumbnail' => null,
            'duration' => null,
            'is_featured' => $property->is_featured,
            'status' => 'published',
            'published_at' => now(),
            'views_count' => 0,
        ]);
    }

    /**
     * Update house tour from property data
     */
    private function updateHouseTourFromProperty(HouseTour $tour, Property $property): void
    {
        $tour->update([
            'title' => $property->title . ' - Virtual Tour',
            'video_url' => $property->video_url,
            'is_featured' => $property->is_featured,
            'status' => 'published',
        ]);
    }
}