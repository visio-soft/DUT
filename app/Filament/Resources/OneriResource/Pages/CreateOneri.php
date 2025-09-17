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
                ->label(__('app.create_project'))
                ->icon('heroicon-o-folder-plus')
                ->color('primary')
                ->size('lg')
                ->action(function () {
                    try {
                        // Form validation ve kayıt
                        $this->create();

                        \Filament\Notifications\Notification::make()
                            ->title(__('filament.notifications.suggestion_created'))
                            ->body(__('filament.notifications.suggestion_created_desc'))
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
                                ->title(__('filament.notifications.file_too_large'))
                                ->body(__('filament.notifications.file_too_large_desc'))
                                ->danger()
                                ->duration(10000)
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title(__('filament.notifications.validation_error'))
                                ->body(__('filament.notifications.check_required_fields') . ' ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                        return;
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title(__('filament.notifications.error'))
                            ->body(__('filament.notifications.suggestion_create_error') . ' ' . $e->getMessage())
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
                ->label(__('app.add_design'))
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
                            ->title(__('filament.notifications.image_required'))
                            ->body(__('filament.notifications.image_required_desc'))
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
                                ->title(__('filament.notifications.file_too_large'))
                                ->body(__('filament.notifications.file_too_large_desc'))
                                ->danger()
                                ->duration(10000)
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title(__('filament.notifications.validation_error'))
                                ->body(__('filament.notifications.check_required_fields') . ' ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                        return;
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title(__('filament.notifications.error'))
                            ->body(__('filament.notifications.suggestion_create_error') . ' ' . $e->getMessage())
                            ->danger()
                            ->send();
                        return;
                    }
                })
                ->requiresConfirmation()
                ->modalHeading(__('filament.modals.create_and_design'))
                ->modalDescription(__('filament.modals.create_and_design_desc'))
                ->modalSubmitActionLabel(__('filament.modals.yes_start_design')),

            // İptal butonu
            $this->getCancelFormAction()
                ->label(__('app.cancel'))
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
