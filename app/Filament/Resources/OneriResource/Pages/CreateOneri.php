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
        $data['design_completed'] = false; // Başlangıçta tasarım tamamlanmamış

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            // Proje Oluştur & Tasarımı Atla butonu (tek buton)
            $this->getCreateFormAction()
                ->label('Proje Oluştur')
                ->icon('heroicon-o-folder-plus')
                ->color('primary')
                ->size('lg')
                ->action(function () {
                    try {
                        // Form validation ve kayıt
                        $this->create();

                        \Filament\Notifications\Notification::make()
                            ->title('Öneri Oluşturuldu!')
                            ->body('Öneri başarıyla oluşturuldu. Tasarımı daha sonra ekleyebilirsiniz.')
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

            // Tasarım Ekle butonu - resim varsa aktif
            \Filament\Actions\Action::make('createAndDesign')
                ->label('Tasarım Ekle')
                ->icon('heroicon-o-paint-brush')
                ->color('success')
                ->size('lg')
                ->extraAttributes([
                    'class' => 'w-full justify-center mb-2',
                ])
                ->action(function () {
                    // İlk olarak resim kontrolü yap
                    $formData = $this->form->getState();
                    if (empty($formData['images'])) {
                        \Filament\Notifications\Notification::make()
                            ->title('Resim Gerekli!')
                            ->body('Tasarım eklemek için önce bir resim yüklemelisiniz.')
                            ->warning()
                            ->send();
                        return;
                    }

                    try {
                        // Form validation ve kayıt
                        $this->create();

                        // Öneri kaydedildikten sonra direkt yönlendirme yap
                        $oneri = $this->record;

                        // Öneri resminin URL'ini al
                        $imageUrl = '';
                        if ($oneri && $oneri->hasMedia('images')) {
                            $imageUrl = $oneri->getFirstMediaUrl('images');
                        }

                        // Tasarım sayfasına yönlendir
                        $designUrl = "/admin/drag-drop-test?project_id={$oneri->id}&image=" . urlencode($imageUrl);

                        return redirect($designUrl);
                    } catch (\Illuminate\Validation\ValidationException $e) {
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
                ->requiresConfirmation()
                ->modalHeading('Öneri Oluştur ve Tasarıma Başla')
                ->modalDescription('Öneriyi oluşturup direkt tasarım aracına geçmek istediğinize emin misiniz?')
                ->modalSubmitActionLabel('Evet, Tasarıma Başla'),

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
        // Normal redirect'i devre dışı bırak çünkü afterCreate'de özel yönlendirme yapıyoruz
        return $this->getResource()::getUrl('index');
    }
}
