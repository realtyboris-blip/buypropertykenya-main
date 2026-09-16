<?php
// app/Providers/WhatsAppServiceProvider.php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share WhatsApp settings with all views
        view()->composer('*', function ($view) {
            try {
                // Check if settings table exists
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $whatsappSettings = Settings::getWhatsAppSettings();
                    $view->with('whatsappSettings', $whatsappSettings);
                } else {
                    // Fallback defaults if table doesn't exist
                    $view->with('whatsappSettings', [
                        'whatsapp_number' => '+254700000000',
                        'whatsapp_enabled' => true,
                        'whatsapp_message' => 'Hello! I need more information about your properties.',
                        'whatsapp_position' => 'bottom-right',
                        'whatsapp_icon_color' => '#25D366',
                        'whatsapp_background_color' => '#075E54',
                        'whatsapp_show_on_desktop' => true,
                        'whatsapp_show_on_mobile' => true,
                        'whatsapp_label' => 'Chat with us on WhatsApp',
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback on error
                $view->with('whatsappSettings', [
                    'whatsapp_number' => '+254700000000',
                    'whatsapp_enabled' => true,
                    'whatsapp_message' => 'Hello! I need more information about your properties.',
                    'whatsapp_position' => 'bottom-right',
                    'whatsapp_icon_color' => '#25D366',
                    'whatsapp_background_color' => '#075E54',
                    'whatsapp_show_on_desktop' => true,
                    'whatsapp_show_on_mobile' => true,
                    'whatsapp_label' => 'Chat with us on WhatsApp',
                ]);
            }
        });
    }
}