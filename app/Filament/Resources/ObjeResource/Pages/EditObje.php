<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditObje extends EditRecord
{
    protected static string $resource = ObjeResource::class;

    public function submitForm()
    {
        $this->save();
    }

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

    public function getTitle(): string
    {
        return 'Obje Düzenle';
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
