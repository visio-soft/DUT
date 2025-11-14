<?php

namespace App\Filament\Resources;

use App\Filament\Helpers\CommonFilters;
use App\Filament\Resources\SuggestionResource\Pages;
use App\Filament\Resources\SuggestionResource\RelationManagers;
use App\Models\Project;
use App\Models\Suggestion;
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

                SelectFilter::make('creator_type')
                    ->label(__('common.creator_type'))
                    ->options([
                        'with_user' => __('common.user_assigned'),
                        'anonymous' => __('common.anonymous'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        if ($value === 'with_user') {
                            $query->whereNotNull('created_by_id');
                        } elseif ($value === 'anonymous') {
                            $query->whereNull('created_by_id');
                        }
                    }),

                CommonFilters::locationFilter(),

                // Budget filter: amount + more/less toggle
                Filter::make('budget_filter')
                    ->label(__('common.budget'))
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->label(__('common.budget'))
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Toggle::make('is_more')
                                    ->label(function (callable $get) {
                                        $amount = $get('amount');

                                        return $amount ? ($amount."₺'dan fazla?") : 'Bütçe Belirleyin';
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
                        if (! empty($data['is_more'])) {
                            $query->where('budget', '>=', $amount);
                        } else {
                            $query->where('budget', '<=', $amount);
                        }
                    }),

                // Like filter
                Filter::make('likes_filter')
                    ->label(__('common.like_count'))
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('min_likes')
                                    ->label(__('common.min_likes'))
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('max_likes')
                                    ->label(__('common.max_likes'))
                                    ->numeric(),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->withCount('likes');

                        if (! empty($data['min_likes'])) {
                            $query->having('likes_count', '>=', $data['min_likes']);
                        }

                        if (! empty($data['max_likes'])) {
                            $query->having('likes_count', '<=', $data['max_likes']);
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
                        ->modalHeading(__('common.delete_suggestion'))
                        ->modalDescription(__('common.delete_suggestion_description'))
                        ->modalSubmitActionLabel(__('common.yes_delete'))
                        ->successNotificationTitle(__('common.suggestion_deleted')),

                    Tables\Actions\RestoreAction::make()
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(__('common.restore_suggestion'))
                        ->modalDescription(__('common.restore_suggestion_description'))
                        ->modalSubmitActionLabel(__('common.yes_restore'))
                        ->successNotificationTitle(__('common.suggestion_restored')),

                    Tables\Actions\ForceDeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(__('common.force_delete_suggestion'))
                        ->modalDescription(__('common.force_delete_suggestion_description'))
                        ->modalSubmitActionLabel(__('common.yes_force_delete'))
                        ->successNotificationTitle(__('common.suggestion_force_deleted')),
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
                        ->modalHeading(__('common.delete_selected_suggestions'))
                        ->modalDescription(__('common.delete_selected_suggestions_description'))
                        ->modalSubmitActionLabel(__('common.yes_delete'))
                        ->successNotificationTitle(__('common.selected_suggestions_deleted')),

                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('common.restore_selected_suggestions'))
                        ->modalDescription(__('common.restore_selected_suggestions_description'))
                        ->modalSubmitActionLabel(__('common.yes_restore'))
                        ->successNotificationTitle(__('common.selected_suggestions_restored')),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('common.force_delete_selected_suggestions'))
                        ->modalDescription(__('common.force_delete_selected_suggestions_description'))
                        ->modalSubmitActionLabel(__('common.yes_force_delete'))
                        ->successNotificationTitle(__('common.selected_suggestions_force_deleted')),
                ]),
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
