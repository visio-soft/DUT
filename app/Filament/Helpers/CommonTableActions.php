<?php

namespace App\Filament\Helpers;

use Filament\Tables;

class CommonTableActions
{
    /**
     * Create a standardized action group for soft-deletable records.
     *
     * @param  string  $resourceType  The resource type key used for translation (e.g., 'project', 'suggestion')
     */
    public static function softDeleteActionGroup(string $resourceType): Tables\Actions\ActionGroup
    {
        return Tables\Actions\ActionGroup::make([
            Tables\Actions\EditAction::make()
                ->visible(fn ($record) => ! $record->trashed()),

            Tables\Actions\DeleteAction::make()
                ->visible(fn ($record) => ! $record->trashed())
                ->requiresConfirmation()
                ->modalHeading(__("common.delete_{$resourceType}"))
                ->modalDescription(__("common.delete_{$resourceType}_description"))
                ->modalSubmitActionLabel(__('common.yes_delete'))
                ->successNotificationTitle(__("common.{$resourceType}_deleted")),

            Tables\Actions\RestoreAction::make()
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__("common.restore_{$resourceType}"))
                ->modalDescription(__("common.restore_{$resourceType}_description"))
                ->modalSubmitActionLabel(__('common.yes_restore'))
                ->successNotificationTitle(__("common.{$resourceType}_restored")),

            Tables\Actions\ForceDeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__("common.force_delete_{$resourceType}"))
                ->modalDescription(__("common.force_delete_{$resourceType}_description"))
                ->modalSubmitActionLabel(__('common.yes_force_delete'))
                ->successNotificationTitle(__("common.{$resourceType}_force_deleted")),
        ])
            ->label(__('common.actions'))
            ->icon('heroicon-m-ellipsis-vertical')
            ->size('sm')
            ->color('gray');
    }

    /**
     * Create a standardized bulk action group for soft-deletable records.
     *
     * @param  string  $resourceType  The resource type key used for translation (e.g., 'project', 'suggestion')
     */
    public static function softDeleteBulkActionGroup(string $resourceType): Tables\Actions\BulkActionGroup
    {
        $pluralType = "{$resourceType}s";

        return Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make()
                ->requiresConfirmation()
                ->modalHeading(__("common.delete_selected_{$pluralType}"))
                ->modalDescription(__("common.delete_selected_{$pluralType}_description"))
                ->modalSubmitActionLabel(__('common.yes_delete'))
                ->successNotificationTitle(__("common.selected_{$pluralType}_deleted")),

            Tables\Actions\RestoreBulkAction::make()
                ->requiresConfirmation()
                ->modalHeading(__("common.restore_selected_{$pluralType}"))
                ->modalDescription(__("common.restore_selected_{$pluralType}_description"))
                ->modalSubmitActionLabel(__('common.yes_restore'))
                ->successNotificationTitle(__("common.selected_{$pluralType}_restored")),

            Tables\Actions\ForceDeleteBulkAction::make()
                ->requiresConfirmation()
                ->modalHeading(__("common.force_delete_selected_{$pluralType}"))
                ->modalDescription(__("common.force_delete_selected_{$pluralType}_description"))
                ->modalSubmitActionLabel(__('common.yes_force_delete'))
                ->successNotificationTitle(__("common.selected_{$pluralType}_force_deleted")),
        ]);
    }

    /**
     * Create a standardized filters trigger action.
     */
    public static function filtersTriggerAction(): callable
    {
        return fn (Tables\Actions\Action $action) => $action
            ->label(__('common.filters_button'))
            ->icon('heroicon-o-funnel')
            ->color('gray')
            ->size('sm')
            ->button()
            ->tooltip(__('common.filters_button_description'));
    }
}
