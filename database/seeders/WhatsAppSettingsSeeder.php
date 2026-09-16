<?php
// database/seeders/WhatsAppSettingsSeeder.php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class WhatsAppSettingsSeeder extends Seeder
{
    public function run(): void
    {
        Settings::initializeWhatsAppSettings();
    }
}