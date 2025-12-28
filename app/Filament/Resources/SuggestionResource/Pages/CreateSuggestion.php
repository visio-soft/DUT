<?php

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Helpers\NotificationHelper;
use App\Filament\Resources\SuggestionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSuggestion extends CreateRecord
{
    protected static string $resource = SuggestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Admin panelinde created_by_id manuel olarak seçilmişse onu kullan
        // Eğer seçilmemişse (null) boş bırak (anonim öneri olur)
        // Eğer hiç değer yoksa mevcut admin kullanıcısını ata
        if (! array_key_exists('created_by_id', $data)) {
            $data['created_by_id'] = Auth::id();
        }

        // Ensure category_id is set
        if (empty($data['category_id'])) {
            $firstCategory = \App\Models\Category::first();
            if ($firstCategory) {
                $data['category_id'] = $firstCategory->id;
            }
        }

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label(__('common.create_suggestion'))
                ->icon('heroicon-o-folder-plus')
                ->color('primary')
                ->size('lg')
                ->action(function () {
                    try {
                        $this->create();

                        NotificationHelper::success(
                            __('common.suggestion_created_title'),
                            __('common.suggestion_created_body')
                        );

                        return redirect($this->getResource()::getUrl('index'));
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        NotificationHelper::handleValidationException($e);

                        return;
                    } catch (\Exception $e) {
                        NotificationHelper::handleException($e, 'suggestion_create');

                        return;
                    }
                })
                ->extraAttributes([
                    'class' => 'w-full justify-center mb-2',
                ]),

            $this->getCancelFormAction()
                ->label(__('common.cancel'))
                ->color('gray')
                ->size('lg')
                ->extraAttributes([
                    'class' => 'w-full justify-center',
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
