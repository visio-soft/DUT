<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    
    public $showImageEditor = false;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();
        
        return $data;
    }
    
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Oluştur'),
            $this->getCreateAnotherFormAction()
                ->label('Oluştur ve Yeni')
                ->icon('heroicon-m-plus')
                ->color('success'),
            $this->getCancelFormAction(),
        ];
    }
    
    // Override Filament's afterCreate method to add popup
    protected function afterCreate(): void
    {
        // Drag-drop sayfasını popup olarak aç
        $this->js("window.open('/admin/drag-droptest', 'dragdrop', 'width=1200,height=800,scrollbars=yes,resizable=yes')");
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // Image editor modal'ını açmak için
    public function openImageEditor(): void
    {
        $this->showImageEditor = true;
    }
}
