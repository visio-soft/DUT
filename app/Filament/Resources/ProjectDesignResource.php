<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectDesignResource\Pages;
use App\Filament\Resources\ProjectDesignResource\RelationManagers;
use App\Models\ProjectDesign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectDesignResource extends Resource
{
    protected static ?string $model = ProjectDesign::class;
    
    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.project_design.plural_label');
    }
    
    public static function getModelLabel(): string
    {
        return __('filament.resources.project_design.label');
    }
    
    public static function getNavigationLabel(): string
    {
        return __('filament.resources.project_design.navigation_label');
    }
    
    public static function getNavigationGroup(): string
    {
        return __('filament.navigation.group.suggestion_management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static bool $shouldRegisterNavigation = true; // Navigation'da gösterilsin

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.resources.project_design.sections.project_information'))
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label(__('filament.resources.project_design.fields.project'))
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),
                    ]),

                Forms\Components\Section::make(__('filament.resources.project_design.sections.design_data'))
                    ->schema([
                        Forms\Components\Textarea::make('design_data_display')
                            ->label(__('filament.resources.project_design.fields.design_data'))
                            ->rows(10)
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->design_data) {
                                    return json_encode($record->design_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                }
                                return '';
                            }),

                        Forms\Components\KeyValue::make('design_summary')
                            ->label(__('filament.resources.project_design.fields.design_summary'))
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->design_data) {
                                    return [
                                        __('filament.resources.project_design.fields.element_count') => $record->design_data['total_elements'] ?? 0,
                                        __('filament.resources.project_design.fields.creation_date') => $record->design_data['timestamp'] ?? __('filament.resources.project_design.fields.unknown'),
                                        __('filament.resources.project_design.fields.project_id') => $record->design_data['project_id'] ?? __('filament.resources.project_design.fields.unknown'),
                                    ];
                                }
                                return [];
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('project.image')
                    ->label(__('filament.resources.project_design.fields.project_image'))
                    ->getStateUsing(function ($record) {
                        if ($record->project && $record->project->hasMedia('images')) {
                            return $record->project->getFirstMediaUrl('images');
                        }
                        return null;
                    })
                    ->defaultImageUrl(url('/images/no-image.png'))
                    ->size(60)
                    ->circular(),

                    Tables\Columns\TextColumn::make('project.title')
                        ->label(__('filament.resources.project_design.fields.project_title'))
                        ->sortable()
                        ->searchable()
                        ->wrap(),                Tables\Columns\TextColumn::make('project.budget')
                    ->label(__('filament.resources.project_design.fields.budget'))
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.category.name')
                    ->label(__('filament.resources.project_design.fields.category'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('elements_count')
                    ->label(__('filament.resources.project_design.fields.elements_count'))
                    ->getStateUsing(function ($record) {
                        return $record->design_data['total_elements'] ?? 0;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('design_timestamp')
                    ->label(__('filament.resources.project_design.fields.design_date'))
                    ->getStateUsing(function ($record) {
                        $timestamp = $record->design_data['timestamp'] ?? null;
                        if ($timestamp) {
                            return \Carbon\Carbon::parse($timestamp)->format('d.m.Y H:i');
                        }
                        return __('filament.resources.project_design.fields.unknown');
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('project.design_completed')
                    ->label(__('filament.resources.project_design.fields.completed'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.resources.project_design.fields.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.resources.project_design.fields.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')
                    ->label(__('filament.resources.project_design.filters.project'))
                    ->relationship('project', 'title')
                    ->searchable(),

                Tables\Filters\Filter::make('completed_projects')
                    ->label(__('filament.resources.project_design.filters.completed_projects'))
                    ->query(fn (Builder $query): Builder => $query->whereHas('project', fn (Builder $query) => $query->where('design_completed', true))),

                Tables\Filters\Filter::make('pending_projects')
                    ->label(__('filament.resources.project_design.filters.pending_projects'))
                    ->query(fn (Builder $query): Builder => $query->whereHas('project', fn (Builder $query) => $query->where('design_completed', false))),

                Tables\Filters\Filter::make('popular_designs')
                    ->label(__('filament.resources.project_design.filters.popular_designs'))
                    ->query(fn (Builder $query): Builder => $query->withCount('likes')->having('likes_count', '>=', 3)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('filament.resources.project_design.actions.view'))
                        ->color('Gray')
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\Action::make('view_design')
                        ->label(__('filament.resources.project_design.actions.edit_design'))
                        ->icon('heroicon-o-paint-brush')
                        ->color('primary')
                        ->url(function ($record) {
                            $projectImage = '';
                            if ($record->project && $record->project->hasMedia('images')) {
                                $projectImage = $record->project->getFirstMediaUrl('images');
                            }

                            return url('/admin/drag-drop-test?' . http_build_query([
                                'project_id' => $record->project_id,
                                'image' => $projectImage
                            ]));
                        })
                        ->openUrlInNewTab(false),

                    Tables\Actions\EditAction::make()
                        ->label(__('filament.resources.project_design.actions.inspect_database'))
                        ->color('info')
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\DeleteAction::make()
                        ->label(__('filament.resources.project_design.actions.delete'))
                        ->icon('heroicon-o-trash'),
                ])
                ->label(__('filament.resources.project_design.actions.actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProjectDesigns::route('/'),
            'create' => Pages\CreateProjectDesign::route('/create'),
            'view' => Pages\ViewProjectDesign::route('/{record}'),
            'edit' => Pages\EditProjectDesign::route('/{record}/edit'),
        ];
    }
}
