<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AreaGuide;
use App\Models\Property;

class AreaGuideSeeder extends Seeder
{
    public function run(): void
    {
        // Get unique cities from properties
        $cities = Property::whereNotNull('city')
            ->select('city')
            ->distinct()
            ->pluck('city');

        if ($cities->isEmpty()) {
            $this->command->warn('No cities found in properties. Please add properties first.');
            return;
        }

        $created = 0;
        foreach ($cities as $city) {
            AreaGuide::firstOrCreate(
                ['name' => $city],
                [
                    'slug' => \Illuminate\Support\Str::slug($city),
                    'description' => "Discover the vibrant neighborhood of {$city}. A thriving residential and commercial area in Kenya.",
                    'is_active' => true,
                ]
            );
            $created++;
        }

        $this->command->info("Created {$created} area guides from property locations.");
    }
}