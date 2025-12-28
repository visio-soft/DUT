<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationGroup = null;
    
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('common.surveys');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.survey');
    }

    public static function getModelLabel(): string
    {
        return __('common.survey');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.surveys_navigation');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.survey_details'))
                    ->description(__('common.survey_details_description'))
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label(__('common.project'))
                            ->relationship('project', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('title')
                            ->label(__('common.survey_title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->label(__('common.description'))
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('status')
                            ->label(__('common.active'))
                            ->default(true)
                            ->helperText(__('common.survey_status_help')),
                    ])->columns(2),

                Forms\Components\Section::make(__('common.questions'))
                    ->description(__('common.questions_section_description'))
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('text')
                                    ->label(__('common.question_text'))
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('type')
                                    ->label(__('common.question_type'))
                                    ->options([
                                        'text' => __('common.open_ended'),
                                        'multiple_choice' => __('common.multiple_choice'),
                                    ])
                                    ->required()
                                    ->live()
                                    ->default('text'),
                                
                                Forms\Components\Repeater::make('options')
                                    ->label(__('common.options'))
                                    ->schema([
                                        Forms\Components\TextInput::make('text')
                                            ->label(__('common.option_text'))
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->reorderable()
                                    ->maxItems(5)
                                    ->minItems(2)
                                    ->defaultItems(2)
                                    ->addActionLabel(__('common.add_option'))
                                    ->itemLabel(fn (array $state): string => $state['text'] ?? __('common.option_text'))
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'multiple_choice'),
                                
                                Forms\Components\Hidden::make('is_required')
                                    ->default(true),
                            ])
                            ->orderColumn('order')
                            ->defaultItems(1)
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->label(''),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('common.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('project.title')
                    ->label(__('common.project'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('status')
                    ->label(__('common.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label(__('common.question_count'))
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('responses_count')
                    ->counts('responses')
                    ->label(__('common.response_count'))
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('project_id')
                    ->label(__('common.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('status')
                    ->label(__('common.status'))
                    ->boolean()
                    ->trueLabel(__('common.active'))
                    ->falseLabel(__('common.inactive'))
                    ->placeholder(__('common.all')),
                Filter::make('has_responses')
                    ->label(__('common.has_responses'))
                    ->query(fn (Builder $query): Builder => $query->has('responses')),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label(__('common.from_date')),
                        Forms\Components\DatePicker::make('until')
                            ->label(__('common.to_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = __('common.from_date') . ': ' . $data['from'];
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = __('common.to_date') . ': ' . $data['until'];
                        }
                        return $indicators;
                    }),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label(__('common.actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->link(),
            ])
            ->actionsPosition(\Filament\Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('common.no_surveys'))
            ->emptyStateDescription(__('common.no_surveys_description'))
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
            RelationManagers\ResponsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'view' => Pages\ViewSurvey::route('/{record}'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
