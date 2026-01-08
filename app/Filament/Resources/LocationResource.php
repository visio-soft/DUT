<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('common.locations');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.locations');
    }

    public static function getModelLabel(): string
    {
        return __('common.location');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.location_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label(__('common.location_type'))
                    ->options(Location::typeLabels())
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('parent_id', null)),

                Forms\Components\TextInput::make('name')
                    ->label(__('common.location_name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->label(__('common.parent_location'))
                    ->options(function (Get $get) {
                        $type = $get('type');
                        
                        if (!$type || $type === Location::TYPE_COUNTRY) {
                            return [];
                        }

                        // Determine parent type
                        $parentType = match ($type) {
                            Location::TYPE_CITY => Location::TYPE_COUNTRY,
                            Location::TYPE_DISTRICT => Location::TYPE_CITY,
                            Location::TYPE_NEIGHBORHOOD => Location::TYPE_DISTRICT,
                            default => null,
                        };

                        if (!$parentType) {
                            return [];
                        }

                        return Location::query()
                            ->where('type', $parentType)
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder(__('common.select_parent_location'))
                    ->required(fn (Get $get): bool => filled($get('type')) && $get('type') !== Location::TYPE_COUNTRY)
                    ->visible(fn (Get $get): bool => filled($get('type')) && $get('type') !== Location::TYPE_COUNTRY),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.location_name'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        // Display full path for context if available, otherwise just name
                        // Ideally we show the hierarchy e.g. "Country > City > Dist > Name"
                        return $record->full_path ?? $record->name;
                    }),
                
                Tables\Columns\TextColumn::make('type')
                    ->label(__('common.location_type'))
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        Location::TYPE_COUNTRY => 'success',
                        Location::TYPE_CITY => 'info',
                        Location::TYPE_DISTRICT => 'warning',
                        Location::TYPE_NEIGHBORHOOD => 'danger',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => Location::typeLabels()[$state] ?? $state),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('common.parent_location'))
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('type')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('common.location_type'))
                    ->options(Location::typeLabels()),
                
                Tables\Filters\SelectFilter::make('parent')
                    ->label(__('common.parent_location'))
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
        ];
    }

    public static function getLocationFields(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('location_country_id')
                        ->label(__('common.country'))
                        ->options(Location::query()->where('type', Location::TYPE_COUNTRY)->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('location_city_id', null);
                            $set('location_district_id', null);
                            $set('location_neighborhood_id', null);
                        }),

                    Forms\Components\Select::make('location_city_id')
                        ->label(__('common.city'))
                        ->options(function (Get $get) {
                            $countryId = $get('location_country_id');
                            if (!$countryId) return [];
                            return Location::query()
                                ->where('type', Location::TYPE_CITY)
                                ->where('parent_id', $countryId)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('location_district_id', null);
                            $set('location_neighborhood_id', null);
                        }),

                    Forms\Components\Select::make('location_district_id')
                        ->label(__('common.district'))
                        ->options(function (Get $get) {
                            $cityId = $get('location_city_id');
                            if (!$cityId) return [];
                            return Location::query()
                                ->where('type', Location::TYPE_DISTRICT)
                                ->where('parent_id', $cityId)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('location_neighborhood_id', null);
                        }),

                    Forms\Components\Select::make('location_neighborhood_id')
                        ->label(__('common.neighborhood'))
                        ->options(function (Get $get) {
                            $districtId = $get('location_district_id');
                            if (!$districtId) return [];
                            return Location::query()
                                ->where('type', Location::TYPE_NEIGHBORHOOD)
                                ->where('parent_id', $districtId)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload(),
                ])
        ];
    }
}
