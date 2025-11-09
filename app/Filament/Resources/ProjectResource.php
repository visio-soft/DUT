<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = null;

    public static function getNavigationLabel(): string
    {
        return __('common.projects');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.projects');
    }

    public static function getModelLabel(): string
    {
        return __('common.project');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.project_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.basic_information'))
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Select::make('projectGroups')
                            ->label(__('common.project_group'))
                            ->relationship('projectGroups', 'name')
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->required()
                            ->placeholder(__('common.select_project_group'))
                            ->helperText(__('common.project_groups_can_be_multiple'))
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('common.project_group'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('category_id')
                                    ->label(__('common.project_category'))
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Forms\Components\Select::make('created_by_id')
                            ->label(__('common.project_creator'))
                            ->relationship('createdBy', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder(__('common.select_user')),
                        Forms\Components\TextInput::make('title')
                            ->label(__('common.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('common.description'))
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label(__('common.start_date')),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label(__('common.end_date')),

                                Forms\Components\TextInput::make('min_budget')
                                    ->label(__('common.min_budget'))
                                    ->numeric()
                                    ->prefix('₺')
                                    ->placeholder(__('common.min_budget_example')),

                                Forms\Components\TextInput::make('max_budget')
                                    ->label(__('common.max_budget'))
                                    ->numeric()
                                    ->prefix('₺')
                                    ->placeholder(__('common.max_budget_example')),
                            ]),
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make(__('common.location'))
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Forms\Components\Textarea::make('address_details')
                            ->label(__('common.detailed_address'))
                            ->placeholder(__('common.detailed_address_example'))
                            ->rows(3)
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('images')
                            ->label(__('common.project_image'))
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->maxSize(20480)
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                            ->helperText(__('common.image_upload_helper'))
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
                SpatieMediaLibraryImageColumn::make('images')->label(__('common.image'))
                    ->collection('images')
                    ->circular()
                    ->height(50)
                    ->width(50),
                Tables\Columns\TextColumn::make('title')->label(__('common.title'))->limit(40)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('projectGroups.category.name')
                    ->label(__('common.project_category'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('common.creator'))
                    ->searchable()
                    ->sortable()
                    ->placeholder(__('common.not_assigned'))
                    ->badge()
                    ->color(fn ($record) => $record->createdBy ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('district')->label(__('common.district'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')->label(__('common.neighborhood'))->searchable()->limit(30),
                Tables\Columns\TextColumn::make('min_budget')->label(__('common.min_budget'))
                    ->money('TRY')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('max_budget')->label(__('common.max_budget'))
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')->label(__('common.start_date'))->date('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_date')->label(__('common.end_date'))->date('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label(__('common.created_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('category')
                    ->label(__('common.project_category'))
                    ->relationship('projectGroups.category', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('creator_type')
                    ->label(__('common.creator_type'))
                    ->options([
                        'with_user' => __('common.user_assigned'),
                        'not_assigned' => __('common.not_assigned'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        if ($value === 'with_user') {
                            $query->whereNotNull('created_by_id');
                        } elseif ($value === 'not_assigned') {
                            $query->whereNull('created_by_id');
                        }
                    }),

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

                Filter::make('budget_filter')
                    ->label(__('common.budget'))
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('min_budget')
                                    ->label(__('common.min_budget'))
                                    ->numeric()
                                    ->placeholder(__('common.min_budget_example')),
                                Forms\Components\TextInput::make('max_budget')
                                    ->label(__('common.max_budget'))
                                    ->numeric()
                                    ->placeholder(__('common.max_budget_example')),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['min_budget'])) {
                            $query->where('min_budget', '>=', $data['min_budget']);
                        }

                        if (! empty($data['max_budget'])) {
                            $query->where('max_budget', '<=', $data['max_budget']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->visible(fn ($record) => ! $record->trashed()),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn ($record) => ! $record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading(__('common.delete_project'))
                        ->modalDescription(__('common.delete_project_description'))
                        ->modalSubmitActionLabel(__('common.yes_delete'))
                        ->successNotificationTitle(__('common.project_deleted')),

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
                ])
                    ->label(__('common.actions'))
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray'),
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
            ])
            ->groups([
                Group::make('projectGroups.category.name')
                    ->label(__('common.project_category'))
                    ->getDescriptionFromRecordUsing(function ($record): string {
                        $category = $record->projectGroups->first()?->category;
                        $end = __('common.not_specified');

                        if ($category && $category->end_datetime) {
                            $end = Carbon::parse($category->end_datetime)->format('d.m.Y');
                        }

                        return __('common.end').": {$end}";
                    }),

            ])
            ->defaultGroup('projectGroups.category.name');

    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
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
            \Filament\Actions\Action::make('create_new')
                ->label(__('common.create_new_project'))
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(function (): string {
                    return static::getUrl('create');
                })
                ->openUrlInNewTab(false),
        ];
    }
}
