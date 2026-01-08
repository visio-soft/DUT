<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Imports\LocationImporter;
use App\Filament\Resources\LocationResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label(__('common.import_locations'))
                ->modalHeading(__('common.import_locations'))
                ->modalDescription(__('common.location_import_description'))
                ->modalContent(fn (): View => view('filament.components.location-import-hint'))
                ->icon('heroicon-o-arrow-up-tray')

                ->color('info')
                ->importer(LocationImporter::class),
            Actions\CreateAction::make()
                ->label(__('common.create_location'))
                ->icon('heroicon-o-plus')
                ->color('success'),
        ];
    }
}
