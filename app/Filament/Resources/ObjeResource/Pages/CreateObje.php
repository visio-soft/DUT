<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;

class CreateObje extends CreateRecord
{
    protected static string $resource = ObjeResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Obje başarıyla oluşturuldu!';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Oluştur')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label('Oluştur ve Yenisini Ekle')
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('gray');
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
        return 'Yeni Obje Oluştur';
    }

    protected function afterCreate(): void
    {
        // Media yükleme sonrası log kaydı
        if ($this->record) {
            $mediaCount = $this->record->getMedia('images')->count();
            Log::info('Obje oluşturuldu', [
                'obje_id' => $this->record->id,
                'obje_isim' => $this->record->isim,
                'media_sayisi' => $mediaCount
            ]);
        }
    }
}
