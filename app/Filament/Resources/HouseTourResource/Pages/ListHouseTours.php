<?php

namespace App\Filament\Resources\HouseTourResource\Pages;

use App\Filament\Resources\HouseTourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHouseTours extends ListRecords
{
    protected static string $resource = HouseTourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
