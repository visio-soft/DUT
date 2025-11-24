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
use Filament\Tables\Filters\SelectFilter;
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
                    ->reactive()
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('common.parent_location'))
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.parent.name')
                    ->label(__('common.country'))
                    ->placeholder('-')
                    ->sortable()
                    ->searchable()
                    ->visible(fn ($record) => $record && $record->type === Location::TYPE_DISTRICT),
            ])
            ->defaultSort('type')
            ->groups([
                Tables\Grouping\Group::make('country')
                    ->label(__('common.country'))
                    ->getTitleFromRecordUsing(function (Location $record): string {
                        // Ülke ise kendi adı
                        if ($record->type === Location::TYPE_COUNTRY) {
                            return $record->name;
                        }
                        // Şehir ise parent'ı (ülke)
                        if ($record->type === Location::TYPE_CITY && $record->parent) {
                            return $record->parent->name;
                        }
                        // İlçe ise parent.parent (ülke)
                        if ($record->type === Location::TYPE_DISTRICT && $record->parent?->parent) {
                            return $record->parent->parent->name;
                        }
                        // Mahalle ise parent.parent.parent (ülke)
                        if ($record->type === Location::TYPE_NEIGHBORHOOD && $record->parent?->parent?->parent) {
                            return $record->parent->parent->parent->name;
                        }
                        return '-';
                    })
                    ->collapsible(),
                Tables\Grouping\Group::make('city')
                    ->label(__('common.city'))
                    ->getTitleFromRecordUsing(function (Location $record): string {
                        // Şehir ise kendi adı
                        if ($record->type === Location::TYPE_CITY) {
                            return $record->name;
                        }
                        // İlçe ise parent'ı (şehir)
                        if ($record->type === Location::TYPE_DISTRICT && $record->parent) {
                            return $record->parent->name;
                        }
                        // Mahalle ise parent.parent (şehir)
                        if ($record->type === Location::TYPE_NEIGHBORHOOD && $record->parent?->parent) {
                            return $record->parent->parent->name;
                        }
                        return '-';
                    })
                    ->collapsible(),
                Tables\Grouping\Group::make('type')
                    ->label(__('common.location_type'))
                    ->getTitleFromRecordUsing(fn (Location $record): string => Location::typeLabels()[$record->type] ?? $record->type)
                    ->collapsible(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('common.location_type'))
                    ->options(Location::typeLabels()),
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
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['parent.parent.parent', 'parent.parent', 'parent']);
    }

    protected static function getParentOptions(?string $type): array
    {
        return match ($type) {
            Location::TYPE_CITY => Location::query()->countries()->orderBy('name')->pluck('name', 'id')->all(),
            Location::TYPE_DISTRICT => Location::query()->cities()->orderBy('name')->pluck('name', 'id')->all(),
            Location::TYPE_NEIGHBORHOOD => Location::query()->districts()->orderBy('name')->pluck('name', 'id')->all(),
            default => [],
        };
    }
}
