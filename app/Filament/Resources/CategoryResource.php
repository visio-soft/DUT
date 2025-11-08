<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('common.project_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.project_categories');
    }

    public static function getModelLabel(): string
    {
        return __('common.project_category');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.project_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('common.project_name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('common.enter_project_name')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('files')
                    ->label(__('common.image'))
                    ->collection('project_files')
                    ->circular()
                    ->height(50)
                    ->width(50),

                Tables\Columns\TextColumn::make('name')->label(__('common.project_name'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('common.description'))
                    ->limit(100)
                    ->searchable()
                    ->tooltip(function ($record): ?string {
                        return $record->description;
                    })
                    ->placeholder(__('common.no_description')),

                Tables\Columns\TextColumn::make('start_datetime')
                    ->label(__('common.start'))
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_datetime')
                    ->label(__('common.end'))
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('remaining_time')
                    ->label(__('common.remaining_time'))
                    ->getStateUsing(function ($record) {
                        if (! $record->end_datetime) {
                            return __('common.indefinite');
                        }

                        if ($record->isExpired()) {
                            return __('common.expired');
                        }

                        $remaining = $record->getRemainingTime();

                        return $remaining ? $remaining['formatted'] : __('common.expired');
                    })
                    ->badge()
                    ->color(fn ($record) => $record && $record->end_datetime && $record->isExpired() ? 'danger' : 'success')
                    ->sortable(false),

                Tables\Columns\TextColumn::make('country')
                    ->label(__('common.country'))
                    ->searchable()
                    ->placeholder(__('common.not_specified'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('province')
                    ->label(__('common.province'))
                    ->searchable()
                    ->placeholder(__('common.not_specified'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('district')
                    ->label(__('common.district'))
                    ->searchable()
                    ->placeholder(__('common.not_specified')),

                Tables\Columns\TextColumn::make('neighborhood')
                    ->label(__('common.neighborhood'))
                    ->searchable()
                    ->placeholder(__('common.not_specified')),

                Tables\Columns\TextColumn::make('detailed_address')
                    ->label(__('common.detailed_address'))
                    ->limit(50)
                    ->searchable()
                    ->placeholder(__('common.not_specified'))
                    ->tooltip(function ($record): ?string {
                        return $record->detailed_address;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('suggestions_count')
                    ->label(__('common.suggestion_count'))
                    ->counts('suggestions')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('name')
            ->filters([
                TrashedFilter::make(),

                // Konum filtresi: İlçe ve Mahalle dropdownları
                Filter::make('location')
                    ->label(__('common.location'))
                    ->form([
                        Forms\Components\Select::make('district')
                            ->label(__('common.district'))
                            ->options(function () {
                                $keys = array_keys(config('istanbul_neighborhoods', []));

                                return array_combine($keys, $keys);
                            })
                            ->searchable(),

                        Forms\Components\Select::make('neighborhood')
                            ->label(__('common.neighborhood'))
                            ->options(function (callable $get) {
                                $district = $get('district');
                                $map = config('istanbul_neighborhoods', []);

                                return $map[$district] ?? [];
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['district'])) {
                            $query->where('district', $data['district']);
                        }

                        if (! empty($data['neighborhood'])) {
                            $query->where('neighborhood', $data['neighborhood']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\RestoreAction::make()
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('common.restore_project'))
                    ->modalDescription(__('common.restore_project_description'))
                    ->modalSubmitActionLabel(__('common.yes_restore'))
                    ->successNotificationTitle(__('common.project_restored')),

                Tables\Actions\ForceDeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(__('common.force_delete_project'))
                    ->modalDescription(__('common.force_delete_project_description'))
                    ->modalSubmitActionLabel(__('common.yes_force_delete'))
                    ->successNotificationTitle(__('common.project_force_deleted')),

                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed())
                    ->requiresConfirmation()
                    ->modalHeading(__('common.delete_project'))
                    ->modalDescription(__('common.delete_project_description'))
                    ->modalSubmitActionLabel(__('common.yes_delete'))
                    ->successNotificationTitle(__('common.project_deleted')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('common.delete_selected_projects'))
                        ->modalDescription(__('common.delete_selected_projects_description'))
                        ->modalSubmitActionLabel(__('common.yes_delete'))
                        ->successNotificationTitle(__('common.selected_projects_deleted')),

                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('common.restore_selected_projects'))
                        ->modalDescription(__('common.restore_selected_projects_description'))
                        ->modalSubmitActionLabel(__('common.yes_restore'))
                        ->successNotificationTitle(__('common.selected_projects_restored')),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('common.force_delete_selected_projects'))
                        ->modalDescription(__('common.force_delete_selected_projects_description'))
                        ->modalSubmitActionLabel(__('common.yes_force_delete'))
                        ->successNotificationTitle(__('common.selected_projects_force_deleted')),
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
            ->withCount('suggestions');
    }
}
