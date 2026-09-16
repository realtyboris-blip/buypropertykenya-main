<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\HouseTour;
use Illuminate\Console\Command;

class GenerateHouseTours extends Command
{
    protected $signature = 'house-tours:generate {--force : Overwrite existing tours}';
    protected $description = 'Generate house tours from existing properties with video URLs';

    public function handle()
    {
        $this->info(' Generating House Tours from Properties...');

        // Get properties with video URLs
        $properties = Property::whereNotNull('video_url')
            ->where('video_url', '!=', '')
            ->get();

        if ($properties->isEmpty()) {
            $this->error('No properties with video URLs found.');
            $this->info('Add video_url to properties first, then run this command again.');
            return 1;
        }

        $this->info("Found " . $properties->count() . " properties with video URLs.");

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($properties as $property) {
            $existingTour = HouseTour::where('property_id', $property->id)->first();

            if ($existingTour && !$this->option('force')) {
                $skipped++;
                continue;
            }

            $data = [
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
            ];

            if ($existingTour) {
                $existingTour->update($data);
                $updated++;
                $this->info("Updated tour for: {$property->title}");
            } else {
                HouseTour::create($data);
                $created++;
                $this->info("Created tour for: {$property->title}");
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("Created: {$created}");
        $this->info("Updated: {$updated}");
        $this->info("Skipped: {$skipped}");
        $this->info("Total Properties: " . $properties->count());

        return 0;
    }
}