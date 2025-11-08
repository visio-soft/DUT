<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjelerResource\Pages;
use App\Models\Project;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjelerResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Sistem Ayarları';

    protected static ?string $navigationLabel = 'Projeler';

    protected static ?string $pluralModelLabel = 'Projeler';

    protected static ?string $modelLabel = 'Proje';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Proje Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name', function (Builder $query) {
                                $query->where('aktif', true);
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Kategori seçin')
                            ->helperText('Bu proje hangi kategoriye ait olacak?')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->label('Proje Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Proje adını girin')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->placeholder('Proje hakkında açıklama...')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('aktif')
                            ->label('Aktif')
                            ->helperText('Proje aktif olduğunda kullanıcılar görebilir')
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
                    ->label('Proje Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Project $record): ?string => $record->description),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('oneriler_count')
                    ->label('Öneriler')
                    ->counts('oneriler')
                    ->sortable()
                    ->badge()
                    ->color('success'),

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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategoriye Göre')
                    ->relationship('category', 'name')
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
            'index' => Pages\ListProjeler::route('/'),
            'create' => Pages\CreateProjeler::route('/create'),
            'view' => Pages\ViewProjeler::route('/{record}'),
            'edit' => Pages\EditProjeler::route('/{record}/edit'),
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
