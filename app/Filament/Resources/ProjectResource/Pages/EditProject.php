<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;
    
    public $showImageEditor = false;

    protected function getFormActions(): array
    {
        return []; // Default butonları gizle - custom view kullanıyoruz
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    // Custom update method
    public function updateProject(): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->callHook('afterSave');

            $this->getSavedNotification()?->send();

            $this->redirect($this->getRedirectUrl());
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->handleRecordUpdateValidationError($exception);
        } catch (\Exception $exception) {
            $this->handleRecordUpdateError($exception);
        }
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
