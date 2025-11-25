<?php

namespace App\Filament\Resources;

use App\Filament\Helpers\CommonFilters;
use App\Filament\Helpers\CommonTableActions;
use App\Filament\Resources\SuggestionResource\Pages;
use App\Filament\Resources\SuggestionResource\RelationManagers;
use App\Models\Suggestion;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class SuggestionResource extends Resource
{
    protected static ?string $model = Suggestion::class;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = null;

    public static function getNavigationLabel(): string
    {
        return __('common.suggestions');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.suggestions');
    }

    public static function getModelLabel(): string
    {
        return __('common.suggestion');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.suggestion_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.basic_information'))
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label(__('common.project'))
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(__('common.select_project')),
                        Forms\Components\Select::make('created_by_id')
                            ->label(__('common.suggestion_creator'))
                            ->relationship('createdBy', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder(__('common.select_user_or_anonymous'))
                            ->helperText(__('common.anonymous_suggestion_helper')),
                        Forms\Components\TextInput::make('title')
                            ->label(__('common.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('common.description'))
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label(__('common.status'))
                            ->options(\App\Enums\SuggestionStatusEnum::class)
                            ->default(\App\Enums\SuggestionStatusEnum::PENDING)
                            ->required(),

                        Forms\Components\Grid::make(2)
                            ->schema([
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

                                Forms\Components\TextInput::make('estimated_duration')
                                    ->label(__('common.estimated_duration'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(365)
                                    ->suffix(__('common.days'))
                                    ->placeholder(__('common.duration_example')),
                            ]),
                    ])
                    ->columnSpan(1),
                Forms\Components\Section::make(__('common.location'))
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        // Sadece detaylı tarif alanı bırakıldı
                        Forms\Components\Textarea::make('address_details')
                            ->label(__('common.detailed_address'))
                            ->placeholder(__('common.detailed_address_example'))
                            ->rows(3)
                            ->columnSpanFull(),

                        // Image upload
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label(__('common.suggestion_design_image'))
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->maxSize(20480) // 20MB limit
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
                Tables\Columns\TextColumn::make('status')
                    ->label(__('common.status'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('common.creator'))
                    ->searchable()
                    ->sortable()
                    ->placeholder(__('common.anonymous'))
                    ->badge()
                    ->color(fn ($record) => $record->createdBy ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('district')->label(__('common.district'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')->label(__('common.neighborhood'))->searchable()->limit(30),
                Tables\Columns\TextColumn::make('min_budget')->label(__('common.min_budget'))
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_budget')->label(__('common.max_budget'))
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('likes_count')
                    ->label(__('common.like_count'))
                    ->counts('likes')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comments_count')
                    ->label(__('common.comment_count'))
                    ->counts('comments')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_duration')
                    ->label(__('common.estimated_duration'))
                    ->suffix(' '.__('common.days'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_date')->label(__('common.end_date'))->date('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label(__('common.created_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('status')
                    ->label(__('common.status'))
                    ->options(\App\Enums\SuggestionStatusEnum::class)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('project')
                    ->label(__('common.project'))
                    ->relationship('project', 'title'),

                CommonFilters::creatorTypeFilter('common.anonymous'),
                CommonFilters::locationFilter(),
                CommonFilters::budgetRangeFilter(),
                CommonFilters::likesFilter(),
            ])
            ->filtersTriggerAction(CommonTableActions::filtersTriggerAction())
            ->filtersFormColumns(2)
            ->filtersFormWidth('3xl')
            ->actions([
                CommonTableActions::softDeleteActionGroup('suggestion'),
            ])
            ->bulkActions([
                CommonTableActions::softDeleteBulkActionGroup('suggestion'),
            ])
            ->groups([
                Group::make('project.title')
                    ->label(__('common.project'))
                    ->getDescriptionFromRecordUsing(function ($record): string {
                        $project = $record->project;
                        $end = __('common.not_specified');

                        if ($project && $project->end_date) {
                            $end = Carbon::parse($project->end_date)->format('d.m.Y');
                        }

                        return __('common.end').": {$end}";
                    }),

            ])
            ->defaultGroup('project.title');

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
            'index' => Pages\ListSuggestions::route('/'),
            'create' => Pages\CreateSuggestion::route('/create'),
            'edit' => Pages\EditSuggestion::route('/{record}/edit'),
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_new')
                ->label(__('common.create_new_suggestion'))
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(function (): string {
                    return static::getUrl('create');
                })
                ->openUrlInNewTab(false),
        ];
    }
}
