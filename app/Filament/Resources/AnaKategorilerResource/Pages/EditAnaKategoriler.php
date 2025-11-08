<?php

namespace App\Filament\Resources\AnaKategorilerResource\Pages;

use App\Filament\Resources\AnaKategorilerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnaKategoriler extends EditRecord
{
    protected static string $resource = AnaKategorilerResource::class;

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
        return 'Ana kategori başarıyla güncellendi.';
    }
}
