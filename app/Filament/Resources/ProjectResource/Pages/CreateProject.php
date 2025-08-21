<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    
    protected function getFormActions(): array
    {
        return []; // Default butonları gizle
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // Livewire method - form data ile kayıt oluştur
    public function submitForm(): void
    {
        $this->validate();
        
        $data = $this->form->getState();
        $this->record = $this->handleRecordCreation($data);
        
        $this->getCreatedNotification()?->send();
        $this->redirect($this->getRedirectUrl());
    }
    
    // Livewire method - kaydet ve yeni ekle
    public function submitAndCreateAnother(): void
    {
        $this->validate();
        
        $data = $this->form->getState();
        $this->record = $this->handleRecordCreation($data);
        
        $this->getCreatedNotification()?->send();
        $this->redirect($this->getResource()::getUrl('create'));
    }
}
