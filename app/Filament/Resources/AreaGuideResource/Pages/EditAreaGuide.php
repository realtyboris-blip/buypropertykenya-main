<?php

namespace App\Filament\Resources\AreaGuideResource\Pages;

use App\Filament\Resources\AreaGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAreaGuide extends EditRecord
{
    protected static string $resource = AreaGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
