<?php

namespace App\Filament\Resources\ProjelerResource\Pages;

use App\Filament\Resources\ProjelerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjeler extends EditRecord
{
    protected static string $resource = ProjelerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
