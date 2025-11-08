<?php

namespace App\Filament\Resources\KategorilerResource\Pages;

use App\Filament\Resources\KategorilerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriler extends ListRecords
{
    protected static string $resource = KategorilerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Kategori'),
        ];
    }
}
