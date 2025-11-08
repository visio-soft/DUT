<?php

namespace App\Filament\Resources\KategorilerResource\Pages;

use App\Filament\Resources\KategorilerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriler extends EditRecord
{
    protected static string $resource = KategorilerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Görüntüle'),
            Actions\DeleteAction::make()
                ->label('Sil'),
            Actions\ForceDeleteAction::make()
                ->label('Kalıcı Sil'),
            Actions\RestoreAction::make()
                ->label('Geri Yükle'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Kategori başarıyla güncellendi.';
    }
}
