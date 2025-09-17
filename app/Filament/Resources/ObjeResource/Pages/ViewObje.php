<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewObje extends ViewRecord
{
    protected static string $resource = ObjeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label(__('filament.resources.obje.actions.edit'))
                ->icon('heroicon-o-pencil')
                ->color('warning'),
            Actions\DeleteAction::make()
                ->label(__('filament.resources.obje.actions.delete'))
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }

    public function getTitle(): string
    {
        return __('filament.resources.obje.pages.view_title');
    }
}
