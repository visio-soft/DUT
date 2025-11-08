<?php

namespace App\Filament\Resources\SuggestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $recordTitleAttribute = 'comment';

    protected static ?string $title = 'Yorumlar';

    protected static ?string $modelLabel = 'Yorum';

    protected static ?string $pluralModelLabel = 'Yorumlar';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Kullanıcı')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Textarea::make('comment')
                            ->label('Yorum')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_approved')
                            ->label('Onaylandı')
                            ->default(false)
                            ->helperText('Yorumun yayınlanması için onaylanması gerekir'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Yorum')
                    ->limit(100)
                    ->searchable()
                    ->tooltip(function ($record): string {
                        return $record->comment;
                    }),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Onay Durumu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Yorum Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Onay Durumu')
                    ->placeholder('Tümü')
                    ->trueLabel('Onaylanmış')
                    ->falseLabel('Onay Bekleyenler'),

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
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Yeni Yorum Ekle')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Yeni Yorum Ekle')
                    ->successNotificationTitle('Yorum başarıyla eklendi'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Düzenle')
                        ->modalHeading('Yorumu Düzenle')
                        ->successNotificationTitle('Yorum başarıyla güncellendi'),

                    Tables\Actions\Action::make('toggle_approval')
                        ->label(fn ($record) => $record->is_approved ? 'Onayı Kaldır' : 'Onayla')
                        ->icon(fn ($record) => $record->is_approved ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn ($record) => $record->is_approved ? 'danger' : 'success')
                        ->action(function ($record) {
                            $record->update(['is_approved' => !$record->is_approved]);
                        })
                        ->requiresConfirmation()
                        ->modalDescription(fn ($record) => $record->is_approved
                            ? 'Bu yorumun onayını kaldırmak istediğinizden emin misiniz?'
                            : 'Bu yorumu onaylamak istediğinizden emin misiniz?')
                        ->successNotificationTitle(fn ($record) => $record->is_approved
                            ? 'Yorum onayı kaldırıldı'
                            : 'Yorum onaylandı'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Sil')
                        ->requiresConfirmation()
                        ->modalHeading('Yorumu Sil')
                        ->modalDescription('Bu yorumu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                        ->successNotificationTitle('Yorum başarıyla silindi'),
                ])
                ->label('İşlemler')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Seçilenleri Onayla')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_approved' => true]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Yorumları Onayla')
                        ->modalDescription('Seçili yorumları onaylamak istediğinizden emin misiniz?')
                        ->successNotificationTitle('Seçili yorumlar onaylandı'),

                    Tables\Actions\BulkAction::make('disapprove_all')
                        ->label('Seçilenlerin Onayını Kaldır')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_approved' => false]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Yorum Onaylarını Kaldır')
                        ->modalDescription('Seçili yorumların onayını kaldırmak istediğinizden emin misiniz?')
                        ->successNotificationTitle('Seçili yorumların onayı kaldırıldı'),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Seçilenleri Sil')
                        ->requiresConfirmation()
                        ->modalHeading('Yorumları Sil')
                        ->modalDescription('Seçili yorumları silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                        ->successNotificationTitle('Seçili yorumlar silindi'),
                ]),
            ])
            ->emptyStateHeading('Henüz yorum yok')
            ->emptyStateDescription('Bu öneriye henüz hiç yorum yapılmamış.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
