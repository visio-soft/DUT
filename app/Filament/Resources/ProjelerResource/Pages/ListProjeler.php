<?php

namespace App\Filament\Resources\ProjelerResource\Pages;

use App\Filament\Resources\ProjelerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjeler extends ListRecords
{
    protected static string $resource = ProjelerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
