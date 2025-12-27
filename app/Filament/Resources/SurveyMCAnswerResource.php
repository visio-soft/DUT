<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyMCAnswerResource\Pages;
use App\Models\SurveyAnswer;
use App\Models\Project;
use App\Models\SurveyQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;

class SurveyMCAnswerResource extends Resource
{
    protected static ?string $model = SurveyAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('common.multiple_choice_answers');
    }

    public static function getModelLabel(): string
    {
        return __('common.multiple_choice_answers');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.multiple_choice_answers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.surveys');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('answer_text')
                    ->label(__('common.answer'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->groups([
                Group::make('question.survey.project.title')
                    ->label(__('common.project'))
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
                Group::make('question.text')
                    ->label(__('common.question_text'))
                    ->titlePrefixedWithLabel(false)
                    ->collapsible()
                    ->getDescriptionFromRecordUsing(fn ($record) => $record->question?->survey?->title),
            ])
            ->defaultGroup('question.survey.project.title')
            ->columns([
                Tables\Columns\TextColumn::make('question.survey.project.title')
                    ->label(__('common.project'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('question.text')
                    ->label(__('common.question_text'))
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->question?->text)
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_text')
                    ->label(__('common.selected_option'))
                    ->badge()
                    ->color(fn ($state) => match(strtoupper($state)) {
                        'A' => 'danger',
                        'B' => 'info',
                        'C' => 'success',
                        'D' => 'warning',
                        'E' => 'gray',
                        default => 'primary',
                    })
                    ->formatStateUsing(function ($state, $record) {
                        $options = $record->question?->options ?? [];
                        $letter = strtoupper($state);
                        $index = ord($letter) - ord('A');
                        $optionText = $options[$index]['text'] ?? $state;
                        return $letter . ') ' . \Str::limit($optionText, 30);
                    }),
                Tables\Columns\TextColumn::make('response.user.name')
                    ->label(__('common.user'))
                    ->default(__('common.anonymous'))
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')
                    ->label(__('common.project'))
                    ->options(fn () => Project::whereHas('surveys.questions', fn ($q) => $q->where('type', 'multiple_choice'))->pluck('title', 'id'))
                    ->query(fn (Builder $query, array $data) => $data['value'] 
                        ? $query->whereHas('question.survey', fn ($q) => $q->where('project_id', $data['value']))
                        : $query
                    ),
                Tables\Filters\SelectFilter::make('question_id')
                    ->label(__('common.question_text'))
                    ->options(fn () => SurveyQuestion::where('type', 'multiple_choice')->pluck('text', 'id'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('answer_text')
                    ->label(__('common.selected_option'))
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                        'E' => 'E',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('common.no_answers'))
            ->emptyStateDescription(__('common.no_mc_answers_desc'))
            ->emptyStateIcon('heroicon-o-list-bullet');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('question', fn ($q) => $q->where('type', 'multiple_choice'))
            ->with(['question.survey.project', 'response.user']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyMCAnswers::route('/'),
        ];
    }
}
