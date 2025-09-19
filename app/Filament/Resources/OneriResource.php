<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OneriResource\Pages;
use App\Filament\Resources\OneriResource\RelationManagers;
use App\Models\Oneri;
use App\Models\Category;
use App\Rules\ImageFormatRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class OneriResource extends Resource
{
    protected static ?string $model = Oneri::class;
    protected static ?string $pluralModelLabel = 'Öneriler';
    protected static ?string $modelLabel = 'Öneri';

    protected static ?string $navigationLabel = 'Öneriler';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Öneri Yönetimi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Proje Kategorisi')
                            ->options(function () {
                                // Tüm kategorileri göster
                                return Category::all()->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(function () {
                                // Set first category as default
                                return Category::first()?->id;
                            })
                            ->placeholder('Proje kategorisi seçin'),
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3),
                        Forms\Components\TextInput::make('estimated_duration')
                            ->label('Tahmini İşlem Süresi (Gün)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(365)
                            ->suffix('gün')
                            ->helperText('Projenin tahmini tamamlanma süresi (1-365 gün arası)'),
                        Forms\Components\TextInput::make('budget')
                            ->label('Bütçe')
                            ->numeric()
                            ->prefix('₺'),
                        // Resim upload - Spatie Media Library ile
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Resim')
                            // Match the media collection defined in the model (registerMediaCollections)
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                            ->helperText('Proje görseli. Maksimum dosya boyutu: 10MB. Desteklenen formatlar: JPEG, JPG, PNG, WebP.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make('Konum')
                    ->extraAttributes(['class' => 'mx-auto max-w-2xl p-4 ml-auto'])
                    ->schema([
                        //Forms\Components\Toggle::make('use_google_maps')
                            //->label('Haritadan Seç')
                            //->default(false)
                            //->reactive()
                            //->columnSpanFull(),

                        // Manual Konum Girişi (Google Maps kapalıyken)
                        Forms\Components\Group::make()->schema([
                            // Şehir sabit: İstanbul (saklama için Hidden, gösterim için disabled TextInput)
                            Forms\Components\Hidden::make('city')
                                ->default('İstanbul'),

                            Forms\Components\TextInput::make('city_display')
                                ->label('İl')
                                ->default('İstanbul')
                                ->disabled()
                                ->dehydrated(false)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('district')
                                ->label('İlçe')
                                ->options(function () {
                                    $districts = config('istanbul_neighborhoods', []);
                                    $districtNames = array_keys($districts);
                                    return array_combine($districtNames, $districtNames);
                                })
                                ->searchable()
                                ->reactive()
                                ->columnSpanFull(),

                            Forms\Components\Select::make('neighborhood')
                                ->label('Mahalle')
                                ->options(function (callable $get) {
                                    $district = $get('district');
                                    if (!$district) {
                                        return ['__other' => 'Diğer..'];
                                    }

                                    $map = config('istanbul_neighborhoods', []);
                                    $options = $map[$district] ?? [];
                                    // '__other' seçeneği kullanıcı kendi mahalle adını yazabilsin diye
                                    return array_merge($options, ['__other' => 'Diğer..']);
                                })
                                ->reactive()
                                ->searchable()
                                ->placeholder(function (callable $get) {
                                    return $get('district') ? 'Mahalle seçin veya Diğer seçin' : 'Önce ilçe seçin';
                                })
                                ->disabled(function (callable $get) {
                                    return !$get('district');
                                })
                                ->columnSpanFull()
                                ->dehydrateStateUsing(function ($state, callable $get) {
                                    // Eğer '__other' seçildiyse custom değeri kullan
                                    if ($state === '__other') {
                                        return $get('neighborhood_custom') ?: null;
                                    }
                                    return $state;
                                }),

                            // Kullanıcı "Diğer" seçerse kendi mahalle adını yazsın
                            Forms\Components\TextInput::make('neighborhood_custom')
                                ->label('Diğer Mahalle')
                                ->placeholder('Mahallenizi yazın')
                                ->visible(function (callable $get) {
                                    return $get('neighborhood') === '__other';
                                })
                                ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                    // Eğer kayıt düzenleniyorsa ve kayıtlı mahalle neighborhood alanında değilse
                                    // (örneğin önceki kayıt custom girilmişse), neighborhood alanını '__other' yap
                                    if ($state && $get('neighborhood') !== '__other') {
                                        $set('neighborhood', '__other');
                                    }
                                })
                                ->dehydrated(false)
                                ->columnSpanFull(),

                            // Sokak / Cadde alanları yan yana olacak şekilde (Grid ile 2 sütun)
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('street_cadde')
                                        ->label('Cadde')
                                        ->placeholder('Cadde adı')
                                        ->columnSpan(1),

                                    Forms\Components\TextInput::make('street_sokak')
                                        ->label('Sokak')
                                        ->placeholder('Sokak adı')
                                        ->columnSpan(1),
                                ])
                                ->columns(2)
                                ->extraAttributes(['class' => 'grid grid-cols-2 gap-3'])
                                ->columnSpanFull(),

                            // Detaylı tarif (mahallenin altına)
                            Forms\Components\Textarea::make('address_details')
                                ->label('Detaylı Tarif')
                                ->placeholder('Detaylı adres tarifi (ör. bina, kapı, kat, vb.)')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])->hidden(function (callable $get) {
                            return $get('use_google_maps');
                        }),

                        // Google Maps Konum Seçimi (Google Maps açıkken)
                        Forms\Components\Group::make()->schema([
                            Forms\Components\TextInput::make('search_address')
                                ->label('Adres Ara')
                                ->placeholder('Bir adres yazın ve haritada bulun...')
                                ->live()
                                ->columnSpanFull(),

                            Forms\Components\ViewField::make('google_maps')
                                ->label('Harita - Tıklayarak Konum Seçin')
                                ->view('custom.google-maps-picker')
                                ->columnSpanFull(),
                        ])->visible(function (callable $get) {
                            return $get('use_google_maps');
                        }),
                        Forms\Components\Hidden::make('latitude'),
                        Forms\Components\Hidden::make('longitude'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                SpatieMediaLibraryImageColumn::make('images')->label('Resim')
                    ->collection('images')
                    ->circular()
                    ->height(50)
                    ->width(50),
                Tables\Columns\TextColumn::make('title')->label('Başlık')->limit(40)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('district')->label('İlçe')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')->label('Mahalle')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('budget')->label('Bütçe')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Beğeni Sayısı')
                    ->counts('likes')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comments_count')
                    ->label('Yorum Sayısı')
                    ->counts('comments')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_duration')
                    ->label('Tahmini Süre')
                    ->suffix(' gün')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_date')->label('Bitiş')->date('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label('Oluşturulma')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

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

                // Bütçe filtresi: miktar + az/çok toggle
                Filter::make('budget_filter')
                    ->label('Bütçe')
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->label('Bütçe')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Toggle::make('is_more')
                                    ->label(function (callable $get) {
                                        $amount = $get('amount');
                                        return $amount ? ($amount . "₺'dan fazla?") : 'Bütçe Belirleyin';
                                    })
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['amount'])) {
                            return;
                        }

                        $amount = $data['amount'];
                        if (!empty($data['is_more'])) {
                            $query->where('budget', '>=', $amount);
                        } else {
                            $query->where('budget', '<=', $amount);
                        }
                    }),

                // Beğeni filtresi
                Filter::make('likes_filter')
                    ->label('Beğeni Sayısı')
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('min_likes')
                                    ->label('Minimum Beğeni')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('max_likes')
                                    ->label('Maksimum Beğeni')
                                    ->numeric(),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->withCount('likes');

                        if (!empty($data['min_likes'])) {
                            $query->having('likes_count', '>=', $data['min_likes']);
                        }

                        if (!empty($data['max_likes'])) {
                            $query->having('likes_count', '<=', $data['max_likes']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->visible(fn ($record) => !$record->trashed()),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn ($record) => !$record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading('Öneriyi Sil')
                        ->modalDescription('Bu öneriyi silmek istediğinizden emin misiniz? Silinen öneriler geri getirilebilir.')
                        ->modalSubmitActionLabel('Evet, Sil')
                        ->successNotificationTitle('Öneri başarıyla silindi'),

                    Tables\Actions\RestoreAction::make()
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Öneriyi Geri Getir')
                        ->modalDescription('Bu öneriyi geri getirmek istediğinizden emin misiniz?')
                        ->modalSubmitActionLabel('Evet, Geri Getir')
                        ->successNotificationTitle('Öneri başarıyla geri getirildi'),

                    Tables\Actions\ForceDeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Öneriyi Kalıcı Olarak Sil')
                        ->modalDescription('Bu öneriyi kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                        ->modalSubmitActionLabel('Evet, Kalıcı Olarak Sil')
                        ->successNotificationTitle('Öneri kalıcı olarak silindi'),
                ])
                ->label('Aksiyonlar')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Önerileri Sil')
                        ->modalDescription('Seçili önerileri silmek istediğinizden emin misiniz? Silinen öneriler geri getirilebilir.')
                        ->modalSubmitActionLabel('Evet, Sil')
                        ->successNotificationTitle('Seçili öneriler başarıyla silindi'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Önerileri Geri Getir')
                        ->modalDescription('Seçili önerileri geri getirmek istediğinizden emin misiniz?')
                        ->modalSubmitActionLabel('Evet, Geri Getir')
                        ->successNotificationTitle('Seçili öneriler başarıyla geri getirildi'),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Önerileri Kalıcı Olarak Sil')
                        ->modalDescription('Seçili önerileri kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                        ->modalSubmitActionLabel('Evet, Kalıcı Olarak Sil')
                        ->successNotificationTitle('Seçili öneriler kalıcı olarak silindi'),
                ]),
            ])
            ->groups([
                Group::make('category.name')
                    ->label('Proje')
                    ->getDescriptionFromRecordUsing(function ($record): string {
                        $category = $record->category;
                        $end = 'Belirtilmemiş';

                        if ($category && $category->end_datetime) {
                            $end = Carbon::parse($category->end_datetime)->format('d.m.Y');
                        }

                        return "Bitiş: {$end}";
                    }),

            ])
            ->defaultGroup('category.name');

    }

    public static function getEloquentQuery(): Builder
    {
        // Remove the SoftDeletingScope so TrashedFilter can work (show/only trashed)
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOneriler::route('/'),
            'create' => Pages\CreateOneri::route('/create'),
            'edit' => Pages\EditOneri::route('/{record}/edit'),
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_new')
                ->label('Yeni Öneri Oluştur')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(function (): string {
                    return static::getUrl('create');
                })
                ->openUrlInNewTab(false),
        ];
    }
}
