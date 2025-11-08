<?php

namespace App\Filament\Resources\KategorilerResource\Pages;

use App\Filament\Resources\KategorilerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKategoriler extends CreateRecord
{
    protected static string $resource = KategorilerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Kategori başarıyla oluşturuldu.';
    }
}
