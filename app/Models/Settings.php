<?php
// app/Models/Settings.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'is_public',
    ];

    protected $casts = [
        'value' => 'json',
        'is_public' => 'boolean',
    ];

    // Helper method to get a setting value
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper method to set a setting value
    public static function set($key, $value, $group = 'general', $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
            ]
        );
    }

    // Get all WhatsApp settings
    public static function getWhatsAppSettings()
    {
        return [
            'whatsapp_number' => self::get('whatsapp_number', '+254700000000'),
            'whatsapp_enabled' => self::get('whatsapp_enabled', true),
            'whatsapp_message' => self::get('whatsapp_message', 'Hello! I need more information about your properties.'),
            'whatsapp_position' => self::get('whatsapp_position', 'bottom-right'),
            'whatsapp_icon_color' => self::get('whatsapp_icon_color', '#25D366'),
            'whatsapp_background_color' => self::get('whatsapp_background_color', '#075E54'),
            'whatsapp_show_on_desktop' => self::get('whatsapp_show_on_desktop', true),
            'whatsapp_show_on_mobile' => self::get('whatsapp_show_on_mobile', true),
            'whatsapp_label' => self::get('whatsapp_label', 'Chat with us on WhatsApp'),
        ];
    }

    // Initialize WhatsApp settings with defaults
    public static function initializeWhatsAppSettings()
    {
        $defaults = [
            'whatsapp_number' => '+254700000000',
            'whatsapp_enabled' => true,
            'whatsapp_message' => 'Hello! I need more information about your properties.',
            'whatsapp_position' => 'bottom-right',
            'whatsapp_icon_color' => '#25D366',
            'whatsapp_background_color' => '#075E54',
            'whatsapp_show_on_desktop' => true,
            'whatsapp_show_on_mobile' => true,
            'whatsapp_label' => 'Chat with us on WhatsApp',
        ];

        foreach ($defaults as $key => $value) {
            if (!self::where('key', $key)->exists()) {
                $type = is_bool($value) ? 'boolean' : 'text';
                self::create([
                    'key' => $key,
                    'value' => $value,
                    'group' => 'whatsapp',
                    'type' => $type,
                    'is_public' => true,
                ]);
            }
        }
    }
}