<?php

namespace App\Filament\Resources\OneriResource\Pages;

use App\Filament\Resources\OneriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $this->getCreateFormAction()
                ->label('Oluştur')
                ->hidden(), // Normal oluştur butonunu gizle
            $this->getCreateAnotherFormAction()
                ->label('Oluştur ve Yeni')
                ->hidden(), // Normal oluştur ve yeni butonunu gizle

            // Yeni özel buton - Tasarıma Başla
            \Filament\Actions\Action::make('createAndDesign')
                ->label('Tasarıma Başla')
                ->icon('heroicon-o-paint-brush')
                ->color('success')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Öneri Oluştur ve Tasarıma Başla')
                ->modalDescription('Öneriyi oluşturup direkt tasarım aracına geçmek istediğinize emin misiniz?')
                ->modalSubmitActionLabel('Evet, Tasarıma Başla')
                ->action(function () {
                    try {
                        Log::info('Tasarıma Başla butonuna tıklandı');

                        // Form validation ve kayıt
                        $this->create();

                        Log::info('Öneri oluşturuldu', ['oneri_id' => $this->record->id]);

                        // Öneri kaydedildikten sonra direkt yönlendirme yap
                        $oneri = $this->record;

                        // Öneri resminin URL'ini al
                        $imageUrl = '';
                        if ($oneri && $oneri->hasMedia('images')) {
                            $imageUrl = $oneri->getFirstMediaUrl('images');
                        }

                        // Tasarım sayfasına yönlendir
                        $designUrl = "/admin/drag-drop-test?project_id={$oneri->id}&image=" . urlencode($imageUrl);

                        Log::info('Yönlendirme URL', ['url' => $designUrl]);

                        // Livewire redirect kullan
                        return redirect($designUrl);
                    } catch (\Exception $e) {
                        Log::error('Tasarıma başla hatası', ['error' => $e->getMessage()]);

                        \Filament\Notifications\Notification::make()
                            ->title('Hata!')
                            ->body('Öneri oluşturulurken bir hata oluştu: ' . $e->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }
                })
                ->extraAttributes([
                    'class' => 'w-full justify-center'
                ]),

            $this->getCancelFormAction(),
        ];
    }



    protected function getRedirectUrl(): string
    {
        // Normal redirect'i devre dışı bırak çünkü afterCreate'de özel yönlendirme yapıyoruz
        return $this->getResource()::getUrl('index');
    }
}
