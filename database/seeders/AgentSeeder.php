<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'agent@realestate.com')->first();

        if ($user) {
            Agent::create([
                'user_id' => $user->id,
                'license_number' => 'LIC' . rand(100000, 999999),
                'bio' => 'Experienced real estate professional with over 10 years in the industry. Specializing in luxury properties and investment opportunities.',
                'phone' => '+1 (555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                'specialization' => 'Luxury Homes',
                'is_featured' => true,
                'rating' => 4.8,
                'total_reviews' => 127,
                'properties_sold' => 89,
            ]);
        }
    }
}
