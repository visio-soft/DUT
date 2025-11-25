<?php

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Helpers\NotificationHelper;
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
                ->label(__('common.update'))
                ->action(function () {
                    try {
                        $this->save();

                        NotificationHelper::success(
                            __('common.suggestion_updated_title'),
                            __('common.suggestion_updated_body')
                        );

                        return redirect($this->getRedirectUrl());
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        NotificationHelper::handleValidationException($e);

                        return;
                    } catch (\Exception $e) {
                        NotificationHelper::handleException($e, 'suggestion_update');

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
