<?php

namespace Database\Seeders;

use App\Models\ListingType;
use Illuminate\Database\Seeder;

class ListingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $listingTypes = [
            [
                'name' => 'For Sale',
                'slug' => 'sale',
                'icon' => '💰',
                'color' => 'success',
                'sort_order' => 1,
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659a1 1 0 001.495-.896v-1.5a1 1 0 00-1.495-.896L9 12.818M9 12.818l.879-.659a1 1 0 011.495.896v1.5a1 1 0 01-1.495.896L9 12.818m.879-1.818L9 10.182" /></svg>',
            ],
            [
                'name' => 'For Rent',
                'slug' => 'rent',
                'icon' => '🏠',
                'color' => 'warning',
                'sort_order' => 2,
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5h16.5M21 3.75v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5h-3.75M6.75 18.75h10.5M7.5 3.75h9M12 3.75v15" /></svg>',
            ],
            [
                'name' => 'For Lease',
                'slug' => 'lease',
                'icon' => '📋',
                'color' => 'info',
                'sort_order' => 3,
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>',
            ],
            [
                'name' => 'Short Stay',
                'slug' => 'short-stay',
                'icon' => '🏨',
                'color' => 'primary',
                'sort_order' => 4,
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5h16.5M21 3.75v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5h-3.75M6.75 18.75h10.5M7.5 3.75h9M12 3.75v15" /></svg>',
            ],
            [
                'name' => 'Auction',
                'slug' => 'auction',
                'icon' => '🔨',
                'color' => 'danger',
                'sort_order' => 5,
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728m5.008 0a6.003 6.003 0 005.27-4.972 47.861 47.861 0 00-2.916-.52m-5.01 5.492L12 12m.01.01l.01.01" /></svg>',
            ],
        ];
        
        foreach ($listingTypes as $type) {
            ListingType::updateOrCreate(
                ['slug' => $type['slug']],
                array_merge($type, ['is_active' => true])
            );
        }
        
        $this->command->info('Listing types seeded with SVG icons!');
    }
}