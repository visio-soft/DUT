<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Location;
use App\Models\LocationView;
use App\Models\Neighborhood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LocationResource extends Resource
{
    protected static ?string $model = LocationView::class;

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
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->label(__('common.location_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label(__('common.location_type'))
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        Location::TYPE_COUNTRY => 'success',
                        Location::TYPE_CITY => 'info',
                        Location::TYPE_DISTRICT => 'warning',
                        Location::TYPE_NEIGHBORHOOD => 'danger',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => Location::typeLabels()[$state] ?? $state),
                Tables\Columns\TextColumn::make('parent')
                    ->label(__('common.parent_location'))
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label(__('common.country'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('common.city'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('district')
                    ->label(__('common.district'))
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('type')
            ->groups([
                Tables\Grouping\Group::make('country')
                    ->label(__('common.country'))
                    ->collapsible(),
                Tables\Grouping\Group::make('city')
                    ->label(__('common.city'))
                    ->collapsible(),
                Tables\Grouping\Group::make('type')
                    ->label(__('common.location_type'))
                    ->collapsible(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('common.location_type'))
                    ->options(Location::typeLabels()),
                Filter::make('location')
                    ->label(__('common.location'))
                    ->form([
                        Forms\Components\Select::make('country')
                            ->label(__('common.country'))
                            ->options(\App\Models\Country::pluck('name', 'name'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('city', null)),
                        Forms\Components\Select::make('city')
                            ->label(__('common.city'))
                            ->options(function (Get $get) {
                                $countryName = $get('country');
                                if (!$countryName) return [];
                                return \App\Models\City::whereHas('country', fn ($q) => $q->where('name', $countryName))
                                    ->pluck('name', 'name');
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('district', null)),
                        Forms\Components\Select::make('district')
                            ->label(__('common.district'))
                            ->options(function (Get $get) {
                                $cityName = $get('city');
                                if (!$cityName) return [];
                                return \App\Models\District::whereHas('city', fn ($q) => $q->where('name', $cityName))
                                    ->pluck('name', 'name');
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('neighborhood', null)),
                        Forms\Components\Select::make('neighborhood')
                            ->label(__('common.neighborhood'))
                            ->options(function (Get $get) {
                                $districtName = $get('district');
                                if (!$districtName) return [];
                                return \App\Models\Neighborhood::whereHas('district', fn ($q) => $q->where('name', $districtName))
                                    ->pluck('name', 'name');
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['country'])) {
                            $query->where('country', $data['country']);
                        }
                        if (!empty($data['city'])) {
                            $query->where('city', $data['city']);
                        }
                        if (!empty($data['district'])) {
                            $query->where('district', $data['district']);
                        }
                        if (!empty($data['neighborhood'])) {
                            $query->where('neighborhood', $data['neighborhood']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Action::make('edit')
                        ->label(__('common.edit_location'))
                        ->icon('heroicon-o-pencil')
                        ->fillForm(fn (LocationView $record): array => self::fillFormForRecord($record))
                        ->form(self::getLocationFormSchema(disableType: true))
                        ->action(fn (LocationView $record, array $data) => self::updateBase($record, $data)),
                    Action::make('delete')
                        ->label(__('common.delete'))
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->action(fn (LocationView $record) => self::deleteBase($record)),
                ])
                ->label(__('common.actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->link(),
            ])
            ->actionsPosition(\Filament\Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                BulkAction::make('bulkDelete')
                    ->label(__('common.delete_selected_locations'))
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each(
                        fn (LocationView $record) => self::deleteBase($record)
                    )),
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

    public static function getEloquentQuery(): Builder
    {
        return LocationView::query()->whereNull('deleted_at');
    }

    public static function getParentOptions(?string $type): array
    {
        return match ($type) {
            Location::TYPE_CITY => Country::query()->orderBy('name')->pluck('name', 'id')->all(),
            Location::TYPE_DISTRICT => City::query()->orderBy('name')->pluck('name', 'id')->all(),
            Location::TYPE_NEIGHBORHOOD => District::query()->orderBy('name')->pluck('name', 'id')->all(),
            default => [],
        };
    }

    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function getLocationFormSchema(bool $disableType = false): array
    {
        return [
            Forms\Components\Select::make('type')
                ->label(__('common.location_type'))
                ->options(Location::typeLabels())
                ->required()
                ->reactive()
                ->disabled($disableType)
                ->afterStateUpdated(fn (Set $set) => $set('parent_id', null)),
            Forms\Components\TextInput::make('name')
                ->label(__('common.location_name'))
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('parent_id')
                ->label(__('common.parent_location'))
                ->options(fn (Get $get): array => self::getParentOptions($get('type')))
                ->searchable()
                ->preload()
                ->placeholder(__('common.select_parent_location'))
                ->required(fn (Get $get): bool => filled($get('type')) && $get('type') !== Location::TYPE_COUNTRY)
                ->hidden(fn (Get $get): bool => blank($get('type')) || $get('type') === Location::TYPE_COUNTRY),
        ];
    }

    public static function createBase(array $data): void
    {
        $type = $data['type'];
        $name = $data['name'];
        $parentId = $data['parent_id'] ?? null;

        match ($type) {
            Location::TYPE_COUNTRY => Country::query()->create(['name' => $name]),
            Location::TYPE_CITY => City::query()->create(['name' => $name, 'country_id' => $parentId]),
            Location::TYPE_DISTRICT => District::query()->create(['name' => $name, 'city_id' => $parentId]),
            Location::TYPE_NEIGHBORHOOD => Neighborhood::query()->create(['name' => $name, 'district_id' => $parentId]),
            default => null,
        };
    }

    public static function updateBase(LocationView $record, array $data): void
    {
        $name = $data['name'];
        $parentId = $data['parent_id'] ?? null;

        switch ($record->type) {
            case Location::TYPE_COUNTRY:
                Country::query()->whereKey($record->base_id)->update(['name' => $name]);
                break;
            case Location::TYPE_CITY:
                City::query()->whereKey($record->base_id)->update([
                    'name' => $name,
                    'country_id' => $parentId,
                ]);
                break;
            case Location::TYPE_DISTRICT:
                District::query()->whereKey($record->base_id)->update([
                    'name' => $name,
                    'city_id' => $parentId,
                ]);
                break;
            case Location::TYPE_NEIGHBORHOOD:
                Neighborhood::query()->whereKey($record->base_id)->update([
                    'name' => $name,
                    'district_id' => $parentId,
                ]);
                break;
        }
    }

    public static function deleteBase(LocationView $record): void
    {
        match ($record->type) {
            Location::TYPE_COUNTRY => Country::query()->whereKey($record->base_id)->delete(),
            Location::TYPE_CITY => City::query()->whereKey($record->base_id)->delete(),
            Location::TYPE_DISTRICT => District::query()->whereKey($record->base_id)->delete(),
            Location::TYPE_NEIGHBORHOOD => Neighborhood::query()->whereKey($record->base_id)->delete(),
            default => null,
        };
    }

    protected static function fillFormForRecord(LocationView $record): array
    {
        $parentId = self::getBaseParentId($record);

        return [
            'type' => $record->type,
            'name' => $record->name,
            'parent_id' => $parentId,
        ];
    }

    protected static function getBaseParentId(LocationView $record): ?int
    {
        $base = self::baseModel($record);

        return match ($record->type) {
            Location::TYPE_CITY => $base?->country_id,
            Location::TYPE_DISTRICT => $base?->city_id,
            Location::TYPE_NEIGHBORHOOD => $base?->district_id,
            default => null,
        };
    }

    protected static function baseModel(LocationView $record): ?Model
    {
        return match ($record->type) {
            Location::TYPE_COUNTRY => Country::query()->find($record->base_id),
            Location::TYPE_CITY => City::query()->find($record->base_id),
            Location::TYPE_DISTRICT => District::query()->find($record->base_id),
            Location::TYPE_NEIGHBORHOOD => Neighborhood::query()->find($record->base_id),
            default => null,
        };
    }

    public static function getLocationFields(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('country')
                        ->label(__('common.country'))
                        ->options(\App\Models\Country::pluck('name', 'name'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('city', null)),
                    Forms\Components\Select::make('city')
                        ->label(__('common.city'))
                        ->options(function (Get $get) {
                            $countryName = $get('country');
                            if (!$countryName) return [];
                            return \App\Models\City::whereHas('country', fn ($q) => $q->where('name', $countryName))
                                ->pluck('name', 'name');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('district', null)),
                    Forms\Components\Select::make('district')
                        ->label(__('common.district'))
                        ->options(function (Get $get) {
                            $cityName = $get('city');
                            if (!$cityName) return [];
                            return \App\Models\District::whereHas('city', fn ($q) => $q->where('name', $cityName))
                                ->pluck('name', 'name');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('neighborhood', null)),
                    Forms\Components\Select::make('neighborhood')
                        ->label(__('common.neighborhood'))
                        ->options(function (Get $get) {
                            $districtName = $get('district');
                            if (!$districtName) return [];
                            return \App\Models\Neighborhood::whereHas('district', fn ($q) => $q->where('name', $districtName))
                                ->pluck('name', 'name');
                        })
                        ->searchable()
                        ->preload(),
                ])
        ];
    }
}
