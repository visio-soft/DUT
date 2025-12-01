<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\SuggestionComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Colors\Color;

class CommentResource extends Resource
{
    protected static ?string $model = SuggestionComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getNavigationLabel(): string
    {
        return __('common.comments');
    }

    public static function getModelLabel(): string
    {
        return __('common.comment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.comments');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.suggestion_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_approved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('common.comment_owner'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('suggestion.title')
                    ->label(__('common.suggestion_title'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('comment')
                    ->label(__('common.comment_content'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('common.status'))
                    ->badge()
                    ->getStateUsing(fn (SuggestionComment $record): string => $record->deleted_at ? __('common.rejected') : ($record->is_approved ? __('common.approved') : __('common.waiting_approval')))
                    ->colors([
                        'danger' => __('common.rejected'),
                        'success' => __('common.approved'),
                        'warning' => __('common.waiting_approval'),
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_approved')
                    ->label(__('common.approval_status'))
                    ->options([
                        '0' => __('common.waiting_approval'),
                        '1' => __('common.approved'),
                    ])
                    ->default('0'),
                Tables\Filters\SelectFilter::make('project')
                    ->label(__('common.project'))
                    ->relationship('suggestion.project', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('suggestion')
                    ->label(__('common.suggestion'))
                    ->relationship('suggestion', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->label(__('common.date_range'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('common.start_date')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('common.end_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('common.view'))
                    ->button()
                    ->modalFooterActions(fn (SuggestionComment $record) => [
                        Tables\Actions\Action::make('approve_modal')
                            ->label(__('common.approve'))
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->action(fn (SuggestionComment $record) => $record->update(['is_approved' => true]))
                            ->visible(fn () => !$record->is_approved && !$record->deleted_at)
                            ->cancelParentActions(),
                        DeleteAction::make('reject_modal')
                            ->label(__('common.reject'))
                            ->icon('heroicon-o-x-mark')
                            ->color('danger')
                            ->record($record)
                            ->before(fn (SuggestionComment $record) => $record->update(['is_approved' => false]))
                            ->visible(fn () => !$record->deleted_at)
                            ->cancelParentActions(),
                        RestoreAction::make('restore_modal')
                            ->label(__('common.restore'))
                            ->record($record)
                            ->visible(fn () => $record->deleted_at)
                            ->cancelParentActions(),
                    ]),
                Tables\Actions\Action::make('approve')
                    ->label(__('common.approve'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->button()
                    ->action(fn (SuggestionComment $record) => $record->update(['is_approved' => true]))
                    ->visible(fn (SuggestionComment $record) => !$record->is_approved && !$record->deleted_at),
                DeleteAction::make()
                    ->label(__('common.reject'))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->before(fn (SuggestionComment $record) => $record->update(['is_approved' => false]))
                    ->visible(fn (SuggestionComment $record) => !$record->deleted_at)
                    ->button(),
                RestoreAction::make()
                    ->label(__('common.restore'))
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label(__('common.bulk_approve'))
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_approved' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('common.details'))
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('common.name')),
                        TextEntry::make('user.email')
                            ->label(__('common.email')),
                        TextEntry::make('suggestion.title')
                            ->label(__('common.suggestion_title')),
                        TextEntry::make('created_at')
                            ->label(__('common.date'))
                            ->dateTime('d.m.Y H:i'),
                    ])->columns(2),
                Section::make(__('common.comment_content'))
                    ->schema([
                        TextEntry::make('comment')
                            ->label(__('common.content'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            // 'create' => Pages\CreateComment::route('/create'),
            // 'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
