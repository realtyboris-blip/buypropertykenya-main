<?php

namespace App\Filament\Resources\HouseTourResource\Pages;

use App\Filament\Resources\HouseTourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHouseTour extends EditRecord
{
    protected static string $resource = HouseTourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
