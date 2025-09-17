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
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.suggestion.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.suggestion.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.suggestion.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.navigation.group.suggestion_management');
    }
    // Ensure ordering in the Filament sidebar: lower numbers appear first within a group
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label(__('filament.resources.suggestion.fields.category_id'))
                            ->options(function () {
                                // Tüm kategorileri göster
                                return Category::all()->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(__('filament.placeholders.select_project_category')),
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament.resources.suggestion.fields.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament.resources.suggestion.fields.description'))
                            ->rows(3),
                        // Resim upload - Spatie Media Library ile
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label(__('filament.resources.suggestion.fields.images'))
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('150')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->imageResizeMode('contain')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                            ->disk('public')
                            ->directory('images')
                            ->visibility('public')
                            ->required()
                            ->helperText(__('filament.helper_texts.max_file_size'))
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make(__('filament.resources.suggestion.fields.location'))
                    ->extraAttributes(['class' => 'mx-auto max-w-2xl p-4 ml-auto'])
                    ->schema([
                        // Manual Konum Girişi
                        Forms\Components\Group::make()->schema([
                            // Şehir sabit: İstanbul (saklama için Hidden, gösterim için disabled TextInput)
                            Forms\Components\Hidden::make('city')
                                ->default(__('filament.placeholders.istanbul')),

                            Forms\Components\TextInput::make('city_display')
                                ->label(__('filament.resources.suggestion.fields.city'))
                                ->default(__('filament.placeholders.istanbul'))
                                ->disabled()
                                ->dehydrated(false)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('district')
                                ->label(__('filament.resources.suggestion.fields.district'))
                                ->options(function () {
                                    $districts = config('istanbul_neighborhoods', []);
                                    $districtNames = array_keys($districts);
                                    return array_combine($districtNames, $districtNames);
                                })
                                ->searchable()
                                ->reactive()
                                ->columnSpanFull(),

                            Forms\Components\Select::make('neighborhood')
                                ->label(__('filament.resources.suggestion.fields.neighborhood'))
                                ->options(function (callable $get) {
                                    $district = $get('district');
                                    if (!$district) {
                                        return ['__other' => __('filament.placeholders.other')];
                                    }

                                    $map = config('istanbul_neighborhoods', []);
                                    $options = $map[$district] ?? [];
                                    // '__other' seçeneği kullanıcı kendi mahalle adını yazabilsin diye
                                    return array_merge($options, ['__other' => __('filament.placeholders.other')]);
                                })
                                ->reactive()
                                ->searchable()
                                ->placeholder(function (callable $get) {
                                    return $get('district') ? __('filament.placeholders.select_neighborhood') : __('filament.placeholders.select_district_first');
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
                                ->label(__('filament.resources.suggestion.fields.neighborhood_custom'))
                                ->placeholder(__('filament.placeholders.write_neighborhood'))
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

                            // Detaylı tarif (mahallenin altına)
                            Forms\Components\Textarea::make('address_details')
                                ->label(__('filament.resources.suggestion.fields.address_details'))
                                ->placeholder(__('filament.placeholders.detailed_address'))
                                ->rows(3)
                                ->columnSpanFull(),
                        ])->hidden(function (callable $get) {
                            return $get('use_google_maps');
                        }),

                        // Google Maps Konum Seçimi (Google Maps açıkken)
                        Forms\Components\Group::make()->schema([
                            Forms\Components\TextInput::make('search_address')
                                ->label(__('filament.placeholders.search_address'))
                                ->placeholder(__('filament.placeholders.search_address_placeholder'))
                                ->live()
                                ->columnSpanFull(),

                            Forms\Components\ViewField::make('google_maps')
                                ->label(__('filament.placeholders.google_maps_picker'))
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
                SpatieMediaLibraryImageColumn::make('images')->label(__('filament.resources.suggestion.fields.images'))
                    ->collection('images')
                    ->circular()
                    ->height(50)
                    ->width(50),
                Tables\Columns\TextColumn::make('title')->label(__('filament.resources.suggestion.fields.title'))->limit(40)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('district')->label(__('filament.resources.suggestion.fields.district'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')->label(__('filament.resources.suggestion.fields.neighborhood'))->searchable()->limit(30),
                Tables\Columns\TextColumn::make('budget')->label(__('filament.resources.suggestion.fields.budget'))
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('likes_count')
                    ->label(__('filament.resources.suggestion.fields.likes_count'))
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-heart'),
                Tables\Columns\IconColumn::make('design_completed')
                    ->label(__('filament.resources.suggestion.fields.design_completed'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(function ($record) {
                        return $record->design_completed ? __('filament.status.design_completed') : __('filament.status.design_waiting');
                    }),
                Tables\Columns\TextColumn::make('estimated_duration')
                    ->label(__('filament.resources.suggestion.fields.estimated_duration'))
                    ->suffix(' ' . __('app.days'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_date')->label(__('filament.resources.suggestion.fields.end_date'))->date('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label(__('filament.resources.suggestion.fields.created_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('filament.filters.category'))
                    ->relationship('category', 'name'),

                // Konum filtresi: İlçe ve Mahalle dropdownları
                Filter::make('location')
                    ->label(__('filament.resources.suggestion.fields.location'))
                    ->form([
                        Forms\Components\Select::make('district')
                            ->label(__('filament.resources.suggestion.fields.district'))
                            ->options(function () {
                                $keys = array_keys(config('istanbul_neighborhoods', []));
                                return array_combine($keys, $keys);
                            })
                            ->searchable(),

                        Forms\Components\Select::make('neighborhood')
                            ->label(__('filament.resources.suggestion.fields.neighborhood'))
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
                    ->label(__('filament.filters.budget'))
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->label(__('filament.filters.budget'))
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Toggle::make('is_more')
                                    ->label(function (callable $get) {
                                        $amount = $get('amount');
                                        return $amount ? ($amount . " " . __('app.more_than_amount')) : __('app.set_budget');
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
                    ->label(__('filament.resources.suggestion.fields.likes_count'))
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('min_likes')
                                    ->label(__('filament.filters.min_likes'))
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('max_likes')
                                    ->label(__('filament.filters.max_likes'))
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
                    ->label(__('filament.resources.suggestion.actions.view_design'))
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
                    ->label(__('filament.resources.suggestion.actions.delete_design'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => $record->design_completed)
                    ->requiresConfirmation()
                    ->modalHeading(__('filament.resources.suggestion.actions.delete_design'))
                    ->modalDescription(__('filament.resources.suggestion.modals.delete_design_confirmation'))
                    ->modalSubmitActionLabel(__('filament.confirmations.yes_delete'))
                    ->action(function ($record) {
                        // Projenin tasarım kaydını bul ve sil
                        $design = $record->design;
                        if ($design) {
                            $design->delete();
                            \Filament\Notifications\Notification::make()
                                ->title(__('filament.notifications.design_deleted'))
                                ->success()
                                ->send();
                        } else {
                            // Eğer design ilişkisi yoksa, projeye ait tüm tasarım kayıtlarını sil
                            \App\Models\ProjectDesign::where('project_id', $record->id)->delete();
                            \Filament\Notifications\Notification::make()
                                ->title(__('filament.notifications.design_deleted'))
                                ->success()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('add_design')
                    ->label(__('app.add_design'))
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
                    ->label(__('app.project'))
                    ->getDescriptionFromRecordUsing(function ($record): string {
                        $category = $record->category;
                        $end = __('app.unspecified');

                        if ($category && $category->end_datetime) {
                            $end = Carbon::parse($category->end_datetime)->format('d.m.Y');
                        }

                        return __('app.end_date') . ": {$end}";
                    }),

                // Ekstra grup: Tasarım durumu (var / yok) - group by boolean column
                Group::make('design_completed')
                    ->label(__('app.design'))
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function ($record): string {
                        return $record->design_completed ? __('app.has_design') : __('app.no_design');
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
                ->label(__('app.new_suggestion'))
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(function (): string {
                    return static::getUrl('create');
                })
                ->openUrlInNewTab(false),
        ];
    }
}
