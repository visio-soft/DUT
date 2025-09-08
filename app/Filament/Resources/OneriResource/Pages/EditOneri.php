<?php

namespace App\Filament\Resources\OneriResource\Pages;

use App\Filament\Resources\OneriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOneri extends EditRecord
{
    protected static string $resource = OneriResource::class;

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
                                ->body('Lütfen gerekli alanları kontrol edin: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                        return;
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Hata!')
                            ->body('Öneri güncellenirken bir hata oluştu: ' . $e->getMessage())
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
        $actions = [Actions\DeleteAction::make()];

        // Eğer öneri tasarımı tamamlandıysa tasarımı görüntüle butonu ekle
        if ($this->record && $this->record->design_completed && $this->record->design) {
            $actions[] = Actions\Action::make('viewDesign')
                ->label('Tasarımı Görüntüle')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url("/admin/project-designs/{$this->record->design->id}");
        }

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
