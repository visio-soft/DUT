<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;

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

    protected function afterSave(): void
    {
        // Media güncelleme sonrası log kaydı
        if ($this->record) {
            $mediaCount = $this->record->getMedia('images')->count();
            Log::info('Obje güncellendi', [
                'obje_id' => $this->record->id,
                'obje_isim' => $this->record->name,
                'media_sayisi' => $mediaCount
            ]);
        }
    }
}
