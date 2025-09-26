<?php

namespace App\Filament\Resources\OneriResource\Pages;

use App\Filament\Resources\OneriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOneri extends CreateRecord
{
    protected static string $resource = OneriResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();

        // Ensure category_id is set
        if (empty($data['category_id'])) {
            $firstCategory = \App\Models\Category::first();
            if ($firstCategory) {
                $data['category_id'] = $firstCategory->id;
            }
        }

        // Design functionality removed
        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            // Öneri Oluştur butonu
            $this->getCreateFormAction()
                ->label('Öneri Oluştur')
                ->icon('heroicon-o-folder-plus')
                ->color('primary')
                ->size('lg')
                ->action(function () {
                    try {
                        // Form validation ve kayıt
                        $this->create();

                        \Filament\Notifications\Notification::make()
                            ->title('Öneri Oluşturuldu!')
                            ->body('Öneri başarıyla oluşturuldu.')
                            ->success()
                            ->send();

                        // Öneriler listesine yönlendir
                        return redirect($this->getResource()::getUrl('index'));
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
                            ->body('Öneri oluşturulurken bir hata oluştu: ' . $e->getMessage())
                            ->danger()
                            ->send();
                        return;
                    }
                })
                ->extraAttributes([
                    'class' => 'w-full justify-center mb-2'
                ]),

            // İptal butonu
            $this->getCancelFormAction()
                ->label('İptal')
                ->color('gray')
                ->size('lg')
                ->extraAttributes([
                    'class' => 'w-full justify-center'
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
