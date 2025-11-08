<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategorilerResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategorilerResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationGroup = 'Sistem Ayarları';

    protected static ?string $navigationLabel = 'Kategoriler';

    protected static ?string $pluralModelLabel = 'Kategoriler';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kategori Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('main_category_id')
                            ->label('Ana Kategori')
                            ->relationship('mainCategory', 'name', function (Builder $query) {
                                $query->where('aktif', true);
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Ana kategori seçin')
                            ->helperText('Bu kategori hangi ana kategoriye ait olacak?')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->label('Kategori Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Kategori adını girin')
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->placeholder('Kategori hakkında açıklama...')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('aktif')
                            ->label('Aktif')
                            ->helperText('Kategori aktif olduğunda kullanıcılar görebilir')
                            ->default(true)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Category $record): ?string => $record->description),

                Tables\Columns\TextColumn::make('mainCategory.name')
                    ->label('Ana Kategori')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Ana Kategori Yok')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('oneriler_count')
                    ->label('Öneriler')
                    ->counts('oneriler')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('aktif')
                    ->label('Durum')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Silinme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('main_category_id')
                    ->label('Ana Kategoriye Göre')
                    ->relationship('mainCategory', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('aktif')
                    ->label('Durum')
                    ->placeholder('Tümü')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),

                Tables\Filters\TrashedFilter::make()
                    ->label('Silinmiş Kayıtlar'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Görüntüle'),
                Tables\Actions\EditAction::make()
                    ->label('Düzenle'),
                Tables\Actions\DeleteAction::make()
                    ->label('Sil'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Kalıcı Sil'),
                Tables\Actions\RestoreAction::make()
                    ->label('Geri Yükle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Seçilenleri Kalıcı Sil'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Seçilenleri Geri Yükle'),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->poll('30s');
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
            'index' => Pages\ListKategoriler::route('/'),
            'create' => Pages\CreateKategoriler::route('/create'),
            'view' => Pages\ViewKategoriler::route('/{record}'),
            'edit' => Pages\EditKategoriler::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
