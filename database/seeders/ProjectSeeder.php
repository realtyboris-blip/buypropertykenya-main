<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Nairobi Heights Luxury Apartments',
                'slug' => 'nairobi-heights-luxury-apartments',
                'description' => 'A premium residential development in the heart of Kilimani featuring modern 2 and 3-bedroom apartments with world-class amenities.',
                'developer' => 'Prime Developers Ltd',
                'starting_price' => 8500000,
                'max_price' => 25000000,
                'completion_date' => '2025-06-30',
                'location' => 'Kilimani, Nairobi',
                'city' => 'Nairobi',
                'status' => 'ongoing',
                'total_units' => 120,
                'available_units' => 45,
                'is_featured' => true,
                'amenities' => json_encode([
                    ['amenity' => 'Swimming Pool'],
                    ['amenity' => 'Gym'],
                    ['amenity' => '24/7 Security'],
                    ['amenity' => 'Parking'],
                ]),
            ],
            [
                'name' => 'Mombasa Beachfront Villas',
                'slug' => 'mombasa-beachfront-villas',
                'description' => 'Luxury beachfront villas with private pools, direct beach access, and breathtaking ocean views.',
                'developer' => 'Coastal Homes Ltd',
                'starting_price' => 15000000,
                'max_price' => 45000000,
                'completion_date' => '2024-12-15',
                'location' => 'Nyali, Mombasa',
                'city' => 'Mombasa',
                'status' => 'ongoing',
                'total_units' => 30,
                'available_units' => 12,
                'is_featured' => true,
                'amenities' => json_encode([
                    ['amenity' => 'Private Pool'],
                    ['amenity' => 'Beach Access'],
                    ['amenity' => '24/7 Security'],
                ]),
            ],
            [
                'name' => 'Kisumu Lakeview Estate',
                'slug' => 'kisumu-lakeview-estate',
                'description' => 'A serene residential estate overlooking Lake Victoria, featuring spacious homes with modern finishes.',
                'developer' => 'Lakeside Developers',
                'starting_price' => 5500000,
                'max_price' => 12000000,
                'completion_date' => '2026-03-20',
                'location' => 'Kisumu City',
                'city' => 'Kisumu',
                'status' => 'off-plan',
                'total_units' => 80,
                'available_units' => 80,
                'is_featured' => false,
                'amenities' => json_encode([
                    ['amenity' => 'Clubhouse'],
                    ['amenity' => 'Swimming Pool'],
                    ['amenity' => 'Security'],
                ]),
            ],
            [
                'name' => 'Nakuru Green Valley Gardens',
                'slug' => 'nakuru-green-valley-gardens',
                'description' => 'A completed residential development offering affordable homes in a tranquil setting.',
                'developer' => 'Green Valley Homes',
                'starting_price' => 3500000,
                'max_price' => 8000000,
                'completion_date' => '2023-08-10',
                'location' => 'Nakuru Town',
                'city' => 'Nakuru',
                'status' => 'completed',
                'total_units' => 60,
                'available_units' => 0,
                'is_featured' => false,
                'amenities' => json_encode([
                    ['amenity' => 'Community Hall'],
                    ['amenity' => 'Security'],
                ]),
            ],
            [
                'name' => 'Eldoret Heights Residential',
                'slug' => 'eldoret-heights-residential',
                'description' => 'A premium gated community offering spacious homes with modern amenities.',
                'developer' => 'Rift Valley Properties',
                'starting_price' => 4500000,
                'max_price' => 15000000,
                'completion_date' => '2025-09-30',
                'location' => 'Eldoret Town',
                'city' => 'Eldoret',
                'status' => 'ongoing',
                'total_units' => 45,
                'available_units' => 20,
                'is_featured' => false,
                'amenities' => json_encode([
                    ['amenity' => 'Clubhouse'],
                    ['amenity' => 'Gym'],
                    ['amenity' => 'Security'],
                ]),
            ],
            [
                'name' => 'Malindi Coastal Paradise',
                'slug' => 'malindi-coastal-paradise',
                'description' => 'A stunning coastal development with luxury homes and ocean views.',
                'developer' => 'Coastal Properties Ltd',
                'starting_price' => 8000000,
                'max_price' => 35000000,
                'completion_date' => '2026-06-30',
                'location' => 'Malindi',
                'city' => 'Malindi',
                'status' => 'off-plan',
                'total_units' => 50,
                'available_units' => 50,
                'is_featured' => true,
                'amenities' => json_encode([
                    ['amenity' => 'Private Beach'],
                    ['amenity' => 'Swimming Pool'],
                    ['amenity' => 'Security'],
                ]),
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        $this->command->info('✅ ' . count($projects) . ' projects created successfully!');
    }
}