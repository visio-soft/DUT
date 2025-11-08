<?php

namespace App\Filament\Resources\AnaKategorilerResource\Pages;

use App\Filament\Resources\AnaKategorilerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnaKategoriler extends ListRecords
{
    protected static string $resource = AnaKategorilerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Ana Kategori'),
        ];
    }
}
