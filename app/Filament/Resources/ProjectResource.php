<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Rules\ImageFormatRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $pluralModelLabel = 'Projeler';
    protected static ?string $modelLabel = 'Proje';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Kategori seçin'),
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(3),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Başlangıç Tarihi')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Bitiş Tarihi')
                            ->required(),
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

                        // Landscape Designer Button
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('landscape_designer')
                                ->label('Tasarlamaya Başla')
                                ->color('warning')
                                ->icon('heroicon-o-paint-brush')
                                ->size('lg')
                                ->action(function ($livewire, $state) {
                                    // Önce resmin yüklenip yüklenmediğini kontrol et
                                    $images = $state['image'] ?? [];
                                    if (empty($images)) {
                                        // Notification göster
                                        \Filament\Notifications\Notification::make()
                                            ->title('Uyarı!')
                                            ->body('Önce bir resim yüklemelisiniz.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    // Geçici resim path'ini al
                                    $imagePath = is_array($images) ? reset($images) : $images;

                                    // Eğer bu bir Livewire temporary upload ise
                                    if (is_object($imagePath) && method_exists($imagePath, 'getClientOriginalName')) {
                                        // Geçici dosyayı storage'a kaydet
                                        $tempPath = $imagePath->store('temp-landscape', 'public');
                                        $imagePath = $tempPath;
                                    }

                                    // Landscape Designer sayfasına yönlendir
                                    $url = url('/admin/drag-drop-test') . '?image=' . urlencode($imagePath);

                                    // Yeni tab'da aç
                                    $livewire->js("window.open('{$url}', '_blank')");
                                })
                                ->visible(fn($state) => !empty($state['image']))
                                ->extraAttributes(['class' => 'w-full'])
                        ])
                            ->columnSpanFull()
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make('Konum')
                    ->extraAttributes(['class' => 'mx-auto max-w-2xl p-4 ml-auto'])
                    ->schema([
                        Forms\Components\Toggle::make('use_google_maps')
                            ->label('Haritadan Seç')
                            ->default(false)
                            ->reactive()
                            ->columnSpanFull(),

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
                                ->options([
                                    'Adalar' => 'Adalar',
                                    'Arnavutköy' => 'Arnavutköy',
                                    'Ataşehir' => 'Ataşehir',
                                    'Avcılar' => 'Avcılar',
                                    'Bağcılar' => 'Bağcılar',
                                    'Bahçelievler' => 'Bahçelievler',
                                    'Bakırköy' => 'Bakırköy',
                                    'Başakşehir' => 'Başakşehir',
                                    'Bayrampaşa' => 'Bayrampaşa',
                                    'Beşiktaş' => 'Beşiktaş',
                                    'Beykoz' => 'Beykoz',
                                    'Beylikdüzü' => 'Beylikdüzü',
                                    'Beyoğlu' => 'Beyoğlu',
                                    'Büyükçekmece' => 'Büyükçekmece',
                                    'Çatalca' => 'Çatalca',
                                    'Çekmeköy' => 'Çekmeköy',
                                    'Esenler' => 'Esenler',
                                    'Esenyurt' => 'Esenyurt',
                                    'Eyüpsultan' => 'Eyüpsultan',
                                    'Fatih' => 'Fatih',
                                    'Gaziosmanpaşa' => 'Gaziosmanpaşa',
                                    'Güngören' => 'Güngören',
                                    'Kadıköy' => 'Kadıköy',
                                    'Kağıthane' => 'Kağıthane',
                                    'Kartal' => 'Kartal',
                                    'Küçükçekmece' => 'Küçükçekmece',
                                    'Maltepe' => 'Maltepe',
                                    'Pendik' => 'Pendik',
                                    'Sancaktepe' => 'Sancaktepe',
                                    'Sarıyer' => 'Sarıyer',
                                    'Silivri' => 'Silivri',
                                    'Sultanbeyli' => 'Sultanbeyli',
                                    'Sultangazi' => 'Sultangazi',
                                    'Şile' => 'Şile',
                                    'Şişli' => 'Şişli',
                                    'Tuzla' => 'Tuzla',
                                    'Ümraniye' => 'Ümraniye',
                                    'Üsküdar' => 'Üsküdar',
                                    'Zeytinburnu' => 'Zeytinburnu',
                                ])
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->columnSpanFull(),

                            Forms\Components\Select::make('neighborhood')
                                ->label('Mahalle')
                                ->options(function (callable $get) {
                                    $district = $get('district');
                                    $map = config('istanbul_neighborhoods', []);

                                    $options = $map[$district] ?? [];
                                    // '__other' seçeneği kullanıcı kendi mahalle adını yazabilsin diye
                                    return array_merge($options, ['__other' => 'Diğer..']);
                                })
                                ->reactive()
                                ->searchable()
                                ->required()
                                ->placeholder(fn(callable $get) => $get('district') ? 'Mahalle seçin veya Diğer seçin' : 'Önce ilçe seçin')
                                ->disabled(fn(callable $get) => !$get('district'))
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
                                ->visible(fn(callable $get) => $get('neighborhood') === '__other')
                                ->required(fn(callable $get) => $get('neighborhood') === '__other')
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
                        ])->hidden(fn(callable $get) => $get('use_google_maps')),

                        // Google Maps Konum Seçimi (Google Maps açıkken)
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
                        ])->visible(fn(callable $get) => $get('use_google_maps')),
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
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('district')->label('İlçe')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')->label('Mahalle')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('budget')->label('Bütçe')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')->label('Başlangıç')->date('d.m.Y')->sortable()
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
                                    ->label(fn(callable $get) => $get('amount') ? ($get('amount') . "₺'dan fazla?") : 'Bütçe Belirleyin')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_with_design')
                ->label('Yeni Proje Oluştur')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(fn(): string => static::getUrl('create'))
                ->openUrlInNewTab(false),
        ];
    }
}
