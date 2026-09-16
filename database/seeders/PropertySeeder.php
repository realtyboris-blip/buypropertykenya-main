<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Agent;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $agent = Agent::first();
        
        if (!$agent) {
            $this->command->error('No agent found! Run AgentSeeder first.');
            return;
        }

        $properties = [
            [
                'title' => 'Modern Luxury Villa with Ocean Views',
                'description' => 'Stunning 5-bedroom villa featuring panoramic ocean views, infinity pool, smart home technology.',
                'price' => 1850000,
                'price_period' => 'one_time',
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area_sqft' => 4200,
                'property_type' => 'villa',
                'listing_type' => 'sale',
                'address' => '123 Ocean Drive',
                'city' => 'Miami',
                'state' => 'Florida',
                'amenities' => json_encode(['Pool', 'Gym', 'Smart Home', 'Ocean View']),
                'is_featured' => true,
                'status' => 'available',
                'slug' => 'modern-luxury-villa-ocean-views',
            ],
            [
                'title' => 'Downtown Luxury Apartment',
                'description' => 'Elegant 2-bedroom apartment in the heart of downtown with city skyline views.',
                'price' => 4500,
                'price_period' => 'month',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqft' => 1350,
                'property_type' => 'apartment',
                'listing_type' => 'rent',
                'address' => '456 City Center Blvd',
                'city' => 'New York',
                'state' => 'New York',
                'amenities' => json_encode(['Concierge', 'Rooftop Pool', 'Gym']),
                'is_featured' => true,
                'status' => 'available',
                'slug' => 'downtown-luxury-apartment',
            ],
            [
                'title' => 'Suburban Family Home',
                'description' => 'Spacious 4-bedroom home in a quiet neighborhood with large backyard.',
                'price' => 625000,
                'price_period' => 'one_time',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqft' => 2800,
                'property_type' => 'townhouse',
                'listing_type' => 'sale',
                'address' => '789 Maple Street',
                'city' => 'Austin',
                'state' => 'Texas',
                'amenities' => json_encode(['Garden', 'Garage', 'Fireplace']),
                'is_featured' => false,
                'status' => 'available',
                'slug' => 'suburban-family-home',
            ],
            [
                'title' => 'Beachfront Condo',
                'description' => 'Beautiful 3-bedroom condo directly on the beach with stunning sunset views.',
                'price' => 895000,
                'price_period' => 'one_time',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 1650,
                'property_type' => 'apartment',
                'listing_type' => 'sale',
                'address' => '321 Beachfront Ave',
                'city' => 'Los Angeles',
                'state' => 'California',
                'amenities' => json_encode(['Beach Access', 'Pool', 'Security']),
                'is_featured' => true,
                'status' => 'available',
                'slug' => 'beachfront-condo',
            ],
        ];

        foreach ($properties as $property) {
            Property::create(array_merge($property, ['agent_id' => $agent->id]));
        }
        
        $this->command->info('Properties seeded successfully!');
    }
}