<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

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
        return []; // Default butonları gizle - custom view kullanıyoruz
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // Custom create method
    public function createProject(): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->getRecord())->saveRelationships();

            $this->callHook('afterCreate');

            $this->getCreatedNotification()?->send();
            
            // Drag-drop sayfasını popup olarak aç
            $this->js("window.open('/admin/drag-droptest', 'dragdrop', 'width=1200,height=800,scrollbars=yes,resizable=yes')");

            $this->redirect($this->getRedirectUrl());
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->handleRecordCreationValidationError($exception);
        } catch (\Exception $exception) {
            $this->handleRecordCreationError($exception);
        }
    }

    // Custom create another method
    public function createProjectAnother(): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->getRecord())->saveRelationships();

            $this->callHook('afterCreate');

            $this->getCreatedNotification()?->send();
            
            // Drag-drop sayfasını popup olarak aç
            $this->js("window.open('/admin/drag-droptest', 'dragdrop', 'width=1200,height=800,scrollbars=yes,resizable=yes')");

            $this->redirect($this->getResource()::getUrl('create'));
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->handleRecordCreationValidationError($exception);
        } catch (\Exception $exception) {
            $this->handleRecordCreationError($exception);
        }
    }
    
    // Image editor modal'ını açmak için
    public function openImageEditor(): void
    {
        $this->showImageEditor = true;
    }
}
