<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObjes extends ListRecords
{
    protected static string $resource = ObjeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('app.create_new') . ' ' . __('filament.resources.object.label'))
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->size('lg'),
        ];
    }
}
