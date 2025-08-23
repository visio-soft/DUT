<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateObje extends CreateRecord
{
    protected static string $resource = ObjeResource::class;

    public function submitForm()
    {
        $this->create();
    }

    public function submitAndCreateAnother()
    {
        // Formu validate et
        $this->form->getState();
        
        // Kayıt oluştur
        $data = $this->form->getState();
        $record = $this->handleRecordCreation($data);
        
        // Form'u temizle
        $this->form->fill();
        
        // Başarı bildirimi
        Notification::make()
            ->title('Obje başarıyla oluşturuldu!')
            ->success()
            ->body('Yeni bir obje daha ekleyebilirsiniz.')
            ->send();
        
        // Sayfayı yenile
        $this->redirect(static::getResource()::getUrl('create'));
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Obje başarıyla oluşturuldu!';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Yeni Obje Oluştur';
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
