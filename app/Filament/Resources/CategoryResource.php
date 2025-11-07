<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Proje Yönetimi';
    protected static ?string $navigationLabel = 'Proje Kategorisi';
    protected static ?string $pluralModelLabel = 'Proje Kategorileri';
    protected static ?string $modelLabel = 'Proje Kategorisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Temel Bilgiler')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Üst Kategori')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Üst kategori seçin (isteğe bağlı)')
                            ->helperText('Alt kategori oluşturmak için bir üst kategori seçin.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->label('Proje Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Proje adını girin')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->label('Proje Açıklaması')
                            ->rows(3)
                            ->placeholder('Proje hakkında kısa açıklama...')
                            ->columnSpanFull(),

                        // Tarihler yan yana
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_datetime')
                                    ->label('Başlangıç Tarihi ve Saati')
                                    ->required()
                                    ->seconds(false)
                                    ->format('Y-m-d H:i')
                                    ->displayFormat('d.m.Y H:i')
                                    ->timezone('Europe/Istanbul')
                                    ->native(false),

                                Forms\Components\DateTimePicker::make('end_datetime')
                                    ->label('Bitiş Tarihi ve Saati')
                                    ->required()
                                    ->seconds(false)
                                    ->format('Y-m-d H:i')
                                    ->displayFormat('d.m.Y H:i')
                                    ->timezone('Europe/Istanbul')
                                    ->native(false)
                                    ->after('start_datetime')
                                    ->helperText('Proje bitiş tarihi geçtikten sonra beğeni yapılamaz.'),
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Konum Bilgileri')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        // Ülke ve İl yan yana
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('country')
                                    ->required()
                                    ->label('Ülke')
                                    ->default('Türkiye')
                                    ->placeholder('Ülke'),

                                Forms\Components\TextInput::make('province')
                                    ->required()
                                    ->label('İl')
                                    ->default('İstanbul')
                                    ->placeholder('İl'),
                            ]),

                        // İlçe ve Mahalle yan yana
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('district')
                                    ->label('İlçe')
                                    ->placeholder('İlçe adı'),

                                Forms\Components\TextInput::make('neighborhood')
                                    ->label('Mahalle')
                                    ->required()
                                    ->placeholder('Mahalle adı'),
                            ]),

                        Forms\Components\Textarea::make('detailed_address')
                            ->label('Detaylı Adres')
                            ->rows(2)
                            ->placeholder('Sokak, cadde, bina no vb.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                SpatieMediaLibraryFileUpload::make('files')
                    ->label('Proje Alanı Fotoğrafları')
                    ->collection('project_files')
                    ->multiple()
                    ->maxSize(20480) // 20MB limit
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                    ->panelLayout('compact')
                    ->required()
                    ->helperText('Sadece resim dosyaları. Maksimum dosya boyutu: 20MB')
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('2000')
                    ->imageResizeTargetHeight('2000')
                    ->columnSpanFull(),

            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('files')
                    ->label('Resim')
                    ->collection('project_files')
                    ->circular()
                    ->height(50)
                    ->width(50),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Üst Kategori')
                    ->searchable()
                    ->placeholder('Ana Kategori')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')->label('Proje Adı')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(100)
                    ->searchable()
                    ->tooltip(function ($record): ?string {
                        return $record->description;
                    })
                    ->placeholder('Açıklama yok'),

                Tables\Columns\TextColumn::make('start_datetime')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_datetime')
                    ->label('Bitiş')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('remaining_time')
                    ->label('Kalan Süre')
                    ->getStateUsing(function ($record) {
                        if (!$record->end_datetime) {
                            return 'Belirsiz';
                        }

                        if ($record->isExpired()) {
                            return 'Süre Dolmuş';
                        }

                        $remaining = $record->getRemainingTime();
                        return $remaining ? $remaining['formatted'] : 'Süre Dolmuş';
                    })
                    ->badge()
                    ->color(fn ($record) => $record && $record->end_datetime && $record->isExpired() ? 'danger' : 'success')
                    ->sortable(false),

                Tables\Columns\TextColumn::make('country')
                    ->label('Ülke')
                    ->searchable()
                    ->placeholder('Belirtilmemiş')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('province')
                    ->label('İl')
                    ->searchable()
                    ->placeholder('Belirtilmemiş')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('district')
                    ->label('İlçe')
                    ->searchable()
                    ->placeholder('Belirtilmemiş'),

                Tables\Columns\TextColumn::make('neighborhood')
                    ->label('Mahalle')
                    ->searchable()
                    ->placeholder('Belirtilmemiş'),

                Tables\Columns\TextColumn::make('detailed_address')
                    ->label('Detaylı Adres')
                    ->limit(50)
                    ->searchable()
                    ->placeholder('Belirtilmemiş')
                    ->tooltip(function ($record): ?string {
                        return $record->detailed_address;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('oneriler_count')
                    ->label('Öneri Sayısı')
                    ->counts('oneriler')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('name')
            ->filters([
                TrashedFilter::make(),

                // Konum filtresi: İlçe ve Mahalle dropdownları
                Filter::make('location')
                    ->label('Konum')
                    ->form([
                        Forms\Components\Select::make('district')
                            ->label('İlçe')
                            ->options(function () {
                                $keys = array_keys(config('istanbul_neighborhoods', []));
                                return array_combine($keys, $keys);
                            })
                            ->searchable(),

                        Forms\Components\Select::make('neighborhood')
                            ->label('Mahalle')
                            ->options(function (callable $get) {
                                $district = $get('district');
                                $map = config('istanbul_neighborhoods', []);
                                return $map[$district] ?? [];
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['district'])) {
                            $query->where('district', $data['district']);
                        }

                        if (!empty($data['neighborhood'])) {
                            $query->where('neighborhood', $data['neighborhood']);
                        }
                    }),

                // Tarih filtresi
                Filter::make('date_range')
                    ->label('Tarih Aralığı')
                    ->form([
                        Forms\Components\DatePicker::make('start_from')
                            ->label('Başlangıç Tarihi (Min)')
                            ->placeholder('Başlangıç')
                            ->native(false),
                        
                        Forms\Components\DatePicker::make('start_until')
                            ->label('Başlangıç Tarihi (Max)')
                            ->placeholder('Bitiş')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['start_from'])) {
                            $query->whereDate('start_datetime', '>=', $data['start_from']);
                        }

                        if (!empty($data['start_until'])) {
                            $query->whereDate('start_datetime', '<=', $data['start_until']);
                        }
                    }),

                // Durum filtresi (Oylama durumu)
                Filter::make('voting_status')
                    ->label('Oylama Durumu')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'active' => 'Aktif (Oy verilebilir)',
                                'expired' => 'Süresi Dolmuş',
                            ])
                            ->placeholder('Tümü'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['status'])) {
                            if ($data['status'] === 'active') {
                                $query->where('end_datetime', '>', now());
                            } elseif ($data['status'] === 'expired') {
                                $query->where('end_datetime', '<=', now());
                            }
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\RestoreAction::make()
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Projeyi Geri Getir')
                    ->modalDescription('Bu projeyi geri getirmek istediğinizden emin misiniz? Bu kategorideki tüm öneriler de geri getirilecektir.')
                    ->modalSubmitActionLabel('Evet, Geri Getir')
                    ->successNotificationTitle('Proje ve ilişkili öneriler başarıyla geri getirildi'),

                Tables\Actions\ForceDeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Projeyi Kalıcı Olarak Sil')
                    ->modalDescription('Bu projeyi kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve bu projeye ait tüm öneriler de silinecektir.')
                    ->modalSubmitActionLabel('Evet, Kalıcı Olarak Sil')
                    ->successNotificationTitle('Proje kalıcı olarak silindi'),

                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => !$record->trashed()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !$record->trashed())
                    ->requiresConfirmation()
                    ->modalHeading('Projeyi Sil')
                    ->modalDescription('Bu projeyi silmek istediğinizden emin misiniz? Bu kategorideki tüm öneriler de silinecektir. Silinen projeler geri getirilebilir.')
                    ->modalSubmitActionLabel('Evet, Sil')
                    ->successNotificationTitle('Proje ve ilişkili öneriler başarıyla silindi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Projeleri Sil')
                        ->modalDescription('Seçili projeleri silmek istediğinizden emin misiniz? Bu kategorilerdeki tüm öneriler de silinecektir. Silinen projeler geri getirilebilir.')
                        ->modalSubmitActionLabel('Evet, Sil')
                        ->successNotificationTitle('Seçili projeler ve ilişkili öneriler başarıyla silindi'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Projeleri Geri Getir')
                        ->modalDescription('Seçili projeleri geri getirmek istediğinizden emin misiniz? Bu kategorilerdeki tüm öneriler de geri getirilecektir.')
                        ->modalSubmitActionLabel('Evet, Geri Getir')
                        ->successNotificationTitle('Seçili projeler ve ilişkili öneriler başarıyla geri getirildi'),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Projeleri Kalıcı Olarak Sil')
                        ->modalDescription('Seçili projeleri kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve bu projelere ait tüm öneriler de silinecektir.')
                        ->modalSubmitActionLabel('Evet, Kalıcı Olarak Sil')
                        ->successNotificationTitle('Seçili projeler kalıcı olarak silindi'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Allow TrashedFilter to control deleted rows by removing the soft deleting scope
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->withCount('oneriler');
    }
}
