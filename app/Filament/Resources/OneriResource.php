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
                            ->placeholder('Proje kategorisi seçin'),
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('estimated_duration')
                            ->label('Tahmini İşlem Süresi (Gün)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(365)
                            ->suffix('gün')
                            ->helperText('Projenin tahmini tamamlanma süresi (1-365 gün arası)'),
                        Forms\Components\TextInput::make('budget')
                            ->label('Bütçe')
                            ->numeric()
                            ->required()
                            ->prefix('₺'),
                        // Resim upload - Filament'in standart özellikleriyle
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Resim')
                            ->collection('images')
                            ->disk('media')
                            ->image()
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                            ->required()
                            ->imagePreviewHeight('150')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->helperText('Desteklenen formatlar: JPEG, JPG, PNG, WebP, Maksimum dosya boyutu: 5MB.')
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
                                ->required()
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
                                ->required()
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
                                ->required(function (callable $get) {
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
                SpatieMediaLibraryImageColumn::make('image')->label('Resim')->collection('images')
                    ->circular()->height(50)->width(50),
                Tables\Columns\TextColumn::make('title')->label('Başlık')->limit(40)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('district')->label('İlçe')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')->label('Mahalle')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('budget')->label('Bütçe')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Beğeni Sayısı')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-heart'),
                Tables\Columns\IconColumn::make('design_completed')
                    ->label('Tasarım')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(function ($record) {
                        return $record->design_completed ? 'Tasarım tamamlandı' : 'Tasarım bekleniyor';
                    }),
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
                Tables\Actions\Action::make('view_design')
                    ->label('Tasarımı Görüntüle')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->visible(fn ($record) => $record->design_completed)
                    ->url(function ($record) {
                        // Projenin tasarım kaydını bul ve view sayfasına git
                        $design = $record->design;
                        if ($design && $design->id) {
                            return url("/admin/project-designs/{$design->id}");
                        }

                        // Eğer design ilişkisi yoksa, projeye ait ilk tasarım kaydını bul
                        $projectDesign = \App\Models\ProjectDesign::where('project_id', $record->id)->first();
                        if ($projectDesign) {
                            return url("/admin/project-designs/{$projectDesign->id}");
                        }

                        // Hiç tasarım yoksa projeler sayfasında kal
                        return url("/admin/oneris");
                    })
                    ->openUrlInNewTab(false),

                Tables\Actions\Action::make('delete_design')
                    ->label('Tasarımı Sil')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => $record->design_completed)
                    ->requiresConfirmation()
                    ->modalHeading('Tasarımı Sil')
                    ->modalDescription('Bu projenin tasarımını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                    ->modalSubmitActionLabel('Evet, Sil')
                    ->action(function ($record) {
                        // Projenin tasarım kaydını bul ve sil
                        $design = $record->design;
                        if ($design) {
                            $design->delete();
                            \Filament\Notifications\Notification::make()
                                ->title('Tasarım silindi')
                                ->success()
                                ->send();
                        } else {
                            // Eğer design ilişkisi yoksa, projeye ait tüm tasarım kayıtlarını sil
                            \App\Models\ProjectDesign::where('project_id', $record->id)->delete();
                            \Filament\Notifications\Notification::make()
                                ->title('Tasarım silindi')
                                ->success()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('add_design')
                    ->label('Tasarım Ekle')
                    ->icon('heroicon-o-plus')
                    ->color('warning')
                    ->visible(fn ($record) => $record->hasMedia('images') && !$record->design_completed)
                    ->url(function ($record) {
                        $projectImage = '';
                        if ($record->hasMedia('images')) {
                            $projectImage = $record->getFirstMediaUrl('images');
                        }

                        return url('/admin/drag-drop-test?' . http_build_query([
                            'project_id' => $record->id,
                            'image' => $projectImage
                        ]));
                    })
                    ->openUrlInNewTab(false),

                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => !$record->design_completed),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

                // Ekstra grup: Tasarım durumu (var / yok) - group by boolean column
                Group::make('design_completed')
                    ->label('Tasarım')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function ($record): string {
                        return $record->design_completed ? 'Tasarımı Var' : 'Tasarımı Yok';
                    }),
            ])
            ->defaultGroup('category.name');

    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('likes');
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
            'index' => Pages\ListOneriler::route('/'),
            'create' => Pages\CreateOneri::route('/create'),
            'edit' => Pages\EditOneri::route('/{record}/edit'),
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_with_design')
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
