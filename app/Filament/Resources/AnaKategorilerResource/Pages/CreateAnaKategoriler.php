<?php

namespace App\Filament\Resources\AnaKategorilerResource\Pages;

use App\Filament\Resources\AnaKategorilerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnaKategoriler extends CreateRecord
{
    protected static string $resource = AnaKategorilerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ana kategori başarıyla oluşturuldu.';
    }
}
