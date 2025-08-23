<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditObje extends EditRecord
{
    protected static string $resource = ObjeResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Obje başarıyla güncellendi!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Kaydet')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('İptal')
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }

    public function getTitle(): string
    {
        return 'Obje Düzenle';
    }
}
