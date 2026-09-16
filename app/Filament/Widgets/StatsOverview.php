<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use App\Models\Inquiry;
use App\Models\Agent;
use App\Models\ListingType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        // Get listing type IDs for sale and rent
        $saleType = ListingType::where('slug', 'sale')->first();
        $rentType = ListingType::where('slug', 'rent')->first();

        // Use the foreign keys instead of the old columns
        $totalProperties = Property::count();
        $propertiesForSale = $saleType ? Property::where('listing_type_id', $saleType->id)->count() : 0;
        $propertiesForRent = $rentType ? Property::where('listing_type_id', $rentType->id)->count() : 0;
        $pendingInquiries = Inquiry::where('status', 'pending')->count();
        $totalAgents = Agent::count();

        return [
            Stat::make('Total Properties', $totalProperties)
                ->description('Properties in system')
                ->descriptionIcon('heroicon-m-home')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->url('/buypropertyadmin/properties'),

            Stat::make('Properties for Sale', $propertiesForSale)
                ->description('Active listings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make('Properties for Rent', $propertiesForRent)
                ->description('Available rentals')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),

            Stat::make('Pending Inquiries', $pendingInquiries)
                ->description('Need response')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),

            Stat::make('Active Agents', $totalAgents)
                ->description('Real estate agents')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
