<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@realestate.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create agent user
        $agentUser = User::create([
            'name' => 'John Agent',
            'email' => 'agent@realestate.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::create([
            'name' => 'Test User',
            'email' => 'user@realestate.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}