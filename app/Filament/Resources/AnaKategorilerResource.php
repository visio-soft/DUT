<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnaKategorilerResource\Pages;
use App\Models\MainCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnaKategorilerResource extends Resource
{
    protected static ?string $model = MainCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Sistem Ayarları';

    protected static ?string $navigationLabel = 'Ana Kategoriler';

    protected static ?string $pluralModelLabel = 'Ana Kategoriler';

    protected static ?string $modelLabel = 'Ana Kategori';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ana Kategori Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ana Kategori Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ana kategori adını girin')
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->placeholder('Ana kategori hakkında açıklama...')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('aktif')
                            ->label('Aktif')
                            ->helperText('Ana kategori aktif olduğunda alt kategoriler kullanılabilir')
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
                    ->label('Ana Kategori Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (MainCategory $record): ?string => $record->description),

                Tables\Columns\TextColumn::make('categories_count')
                    ->label('Alt Kategori Sayısı')
                    ->counts('categories')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),

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
            ])
            ->filters([
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
            'index' => Pages\ListAnaKategoriler::route('/'),
            'create' => Pages\CreateAnaKategoriler::route('/create'),
            'view' => Pages\ViewAnaKategoriler::route('/{record}'),
            'edit' => Pages\EditAnaKategoriler::route('/{record}/edit'),
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
