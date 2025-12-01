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

    protected static ?string $navigationLabel = 'Yorumlar';

   public static function getPluralModelLabel(): string
    {
        return __('common.suggestion');
    }    

    protected static ?string $modelLabel = 'Yorum';

    protected static ?string $pluralModelLabel = 'Yorumlar';

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
                    ->label('Yorum Sahibi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('suggestion.title')
                    ->label('Öneri Başlığı')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('comment')
                    ->label('Yorum İçeriği')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->getStateUsing(fn (SuggestionComment $record): string => $record->deleted_at ? 'Reddedildi' : ($record->is_approved ? 'Yayında' : 'Onay Bekliyor'))
                    ->colors([
                        'danger' => 'Reddedildi',
                        'success' => 'Yayında',
                        'warning' => 'Onay Bekliyor',
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_approved')
                    ->label('Onay Durumu')
                    ->options([
                        '0' => 'Onay Bekliyor',
                        '1' => 'Yayında',
                    ])
                    ->default('0'),
                Tables\Filters\SelectFilter::make('project')
                    ->label('Proje')
                    ->relationship('suggestion.project', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('suggestion')
                    ->label('Öneri')
                    ->relationship('suggestion', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->label('Tarih Aralığı')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Başlangıç Tarihi'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Bitiş Tarihi'),
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
                    ->label('İncele')
                    ->button()
                    ->modalFooterActions(fn (SuggestionComment $record) => [
                        Tables\Actions\Action::make('approve_modal')
                            ->label('Onayla')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->action(fn (SuggestionComment $record) => $record->update(['is_approved' => true]))
                            ->visible(fn () => !$record->is_approved && !$record->deleted_at)
                            ->cancelParentActions(),
                        DeleteAction::make('reject_modal')
                            ->label('Reddet')
                            ->icon('heroicon-o-x-mark')
                            ->color('danger')
                            ->record($record)
                            ->before(fn (SuggestionComment $record) => $record->update(['is_approved' => false]))
                            ->visible(fn () => !$record->deleted_at)
                            ->cancelParentActions(),
                        RestoreAction::make('restore_modal')
                            ->label('Geri Al')
                            ->record($record)
                            ->visible(fn () => $record->deleted_at)
                            ->cancelParentActions(),
                    ]),
                Tables\Actions\Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->button()
                    ->action(fn (SuggestionComment $record) => $record->update(['is_approved' => true]))
                    ->visible(fn (SuggestionComment $record) => !$record->is_approved && !$record->deleted_at),
                DeleteAction::make()
                    ->label('Reddet')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->before(fn (SuggestionComment $record) => $record->update(['is_approved' => false]))
                    ->visible(fn (SuggestionComment $record) => !$record->deleted_at)
                    ->button(),
                RestoreAction::make()
                    ->label('Geri Al')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Seçilenleri Onayla')
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
                Section::make('Detaylar')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Kullanıcı'),
                        TextEntry::make('user.email')
                            ->label('E-posta'),
                        TextEntry::make('suggestion.title')
                            ->label('Öneri Başlığı'),
                        TextEntry::make('created_at')
                            ->label('Tarih')
                            ->dateTime('d.m.Y H:i'),
                    ])->columns(2),
                Section::make('Yorum İçeriği')
                    ->schema([
                        TextEntry::make('comment')
                            ->label('İçerik')
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
