<?php
// database/seeders/SettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // Contact Settings
        Setting::set('contact_phone', '+254700000000', 'contact', 'text');
        Setting::set('contact_phone_2', '+254711111111', 'contact', 'text');
        Setting::set('contact_whatsapp', '+254700000000', 'contact', 'text');
        Setting::set('contact_whatsapp_2', '+254711111111', 'contact', 'text');
        Setting::set('contact_email', 'info@property.com', 'contact', 'text');
        
        // Button Settings
        Setting::set('show_phone_button', true, 'buttons', 'boolean');
        Setting::set('show_whatsapp_button', true, 'buttons', 'boolean');
        Setting::set('phone_button_label', 'Call Now', 'buttons', 'text');
        Setting::set('whatsapp_button_label', 'WhatsApp', 'buttons', 'text');
        Setting::set('whatsapp_message_template', 'Hi, I am interested in your property: {property_title} in {property_city}', 'buttons', 'text');
    }
}