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
                Forms\Components\Section::make('Temel Bilgiler')
                    ->icon('heroicon-o-information-circle')
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
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('budget')
                                    ->label('Bütçe')
                                    ->numeric()
                                    ->prefix('₺')
                                    ->placeholder('Örn: 50000'),

                                Forms\Components\TextInput::make('estimated_duration')
                                    ->label('Tahmini Süre')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(365)
                                    ->suffix('gün')
                                    ->placeholder('Örn: 30'),
                            ]),
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make('Konum')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        // Sadece detaylı tarif alanı bırakıldı
                        Forms\Components\Textarea::make('address_details')
                            ->label('Detaylı Tarif')
                            ->placeholder('Detaylı adres tarifi (ör. bina, kapı, kat, vb.)')
                            ->rows(3)
                            ->columnSpanFull(),

                        // Resim upload
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Öneri Tasarım Görseli')
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->maxSize(20480) // 20MB limit
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                            ->helperText('Sadece resim dosyaları. Maksimum dosya boyutu: 20MB')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('2000')
                            ->imageResizeTargetHeight('2000')
                            ->columnSpanFull(),
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
