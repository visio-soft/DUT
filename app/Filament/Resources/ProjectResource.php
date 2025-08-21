<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
                            ->label('Ana Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
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
                        
                        SpatieMediaLibraryFileUpload::make('image_path')
                            ->label('Resim')
                            ->image()
                            ->directory('projects')
                            ->maxSize(2048)
                            ->required(),
                        
                        // Form butonları
                        Forms\Components\ViewField::make('form_actions')
                            ->label('')
                            ->view('custom.form-actions')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make('Konum')
                    ->schema([
                        Forms\Components\Toggle::make('use_google_maps')
                            ->label('Haritadan Seç')
                            ->default(false)
                            ->reactive()
                            ->columnSpanFull(),
                        
                        // Manual Konum Girişi (Google Maps kapalıyken)
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('city')
                                ->label('İl')
                                ->options([
                                    'İstanbul' => 'İstanbul',
                                ])
                                ->default('İstanbul')
                                ->required()
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
                                ->columnSpanFull(),
                            
                            Forms\Components\TextInput::make('address')
                                ->label('Detay Adres')
                                ->placeholder('Mahalle, sokak, bina no vb.')
                                ->maxLength(500)
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->hidden(fn (callable $get) => $get('use_google_maps')),
                        
                        // Google Maps Konum Seçimi (Google Maps açıkken)
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('search_address')
                                ->label('Adres Ara')
                                ->placeholder('Bir adres yazın ve haritada bulun...')
                                ->live()
                                ->columnSpanFull(),
                            
                            Forms\Components\ViewField::make('google_maps')
                                ->label('Harita - Tıklayarak Konum Seçin')
                                ->view('custom.google-maps-picker')
                                ->columnSpanFull(),
                        ])
                        ->visible(fn (callable $get) => $get('use_google_maps')),
                        
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
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                Tables\Columns\TextColumn::make('title')->label('Başlık'),
                Tables\Columns\TextColumn::make('city')->label('İl'),
                Tables\Columns\TextColumn::make('district')->label('İlçe'),
                Tables\Columns\TextColumn::make('address')->label('Adres')->limit(30),
                Tables\Columns\TextColumn::make('budget')->label('Bütçe'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label('Oluşturulma'),
            ])
            ->filters([
                //
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
}
