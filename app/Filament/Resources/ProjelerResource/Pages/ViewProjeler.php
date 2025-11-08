<?php

namespace App\Filament\Resources\ProjelerResource\Pages;

use App\Filament\Resources\ProjelerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjeler extends ViewRecord
{
    protected static string $resource = ProjelerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
