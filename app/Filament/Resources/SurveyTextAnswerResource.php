<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyTextAnswerResource\Pages;
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

class SurveyTextAnswerResource extends Resource
{
    protected static ?string $model = SurveyAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('common.open_ended_answers');
    }

    public static function getModelLabel(): string
    {
        return __('common.open_ended_answers');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.open_ended_answers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.surveys');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('answer_text')
                    ->label(__('common.answer'))
                    ->required()
                    ->columnSpanFull(),
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
                    ->label(__('common.answer'))
                    ->wrap()
                    ->limit(100)
                    ->searchable(),
                Tables\Columns\TextColumn::make('response.user.name')
                    ->label(__('common.user'))
                    ->default(__('common.anonymous'))
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('response.ip_address')
                    ->label(__('common.ip_address'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')
                    ->label(__('common.project'))
                    ->options(fn () => Project::whereHas('surveys.questions', fn ($q) => $q->where('type', 'text'))->pluck('title', 'id'))
                    ->query(fn (Builder $query, array $data) => $data['value'] 
                        ? $query->whereHas('question.survey', fn ($q) => $q->where('project_id', $data['value']))
                        : $query
                    ),
                Tables\Filters\SelectFilter::make('question_id')
                    ->label(__('common.question_text'))
                    ->options(fn () => SurveyQuestion::where('type', 'text')->pluck('text', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(__('common.answer'))
                    ->modalContent(fn ($record) => view('filament.resources.survey-text-answer.view', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('common.no_answers'))
            ->emptyStateDescription(__('common.no_open_ended_answers_desc'))
            ->emptyStateIcon('heroicon-o-chat-bubble-bottom-center-text');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('question', fn ($q) => $q->where('type', 'text'))
            ->with(['question.survey.project', 'response.user']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyTextAnswers::route('/'),
        ];
    }
}
