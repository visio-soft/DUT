<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;
    
    public $showImageEditor = false;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Güncelle'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
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
