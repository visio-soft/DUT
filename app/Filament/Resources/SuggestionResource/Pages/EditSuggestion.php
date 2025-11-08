<?php

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Resources\SuggestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuggestion extends EditRecord
{
    protected static string $resource = SuggestionResource::class;

    /**
     * Preserve existing created_by_id when the edit form submits a null value.
     * This prevents attempting to write NULL into a NOT NULL DB column.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If the created_by_id key exists but is explicitly null (admin cleared the selector),
        // remove it so the existing value on the model is preserved and not overwritten with NULL.
        if (array_key_exists('created_by_id', $data) && is_null($data['created_by_id'])) {
            unset($data['created_by_id']);
        }

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Güncelle')
                ->action(function () {
                    try {
                        // Form validation ve kayıt
                        $this->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Öneri Güncellendi!')
                            ->body('Öneri başarıyla güncellendi.')
                            ->success()
                            ->send();

                        return redirect($this->getRedirectUrl());
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        // Validasyon hatalarını göster
                        $errors = $e->errors();
                        if ((isset($errors['images']) && str_contains(json_encode($errors['images']), 'max')) ||
                            (isset($errors['image']) && str_contains(json_encode($errors['image']), 'max'))) {
                            \Filament\Notifications\Notification::make()
                                ->title('Dosya Boyutu Hatası!')
                                ->body('Yüklediğiniz resim dosyası çok büyük. Maksimum 10MB boyutunda bir resim yükleyiniz.')
                                ->danger()
                                ->duration(10000)
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Validasyon Hatası!')
                                ->body('Lütfen gerekli alanları kontrol edin: '.$e->getMessage())
                                ->danger()
                                ->send();
                        }

                        return;
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Hata!')
                            ->body('Öneri güncellenirken bir hata oluştu: '.$e->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }
                }),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
