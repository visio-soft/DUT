<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use App\Models\SurveyResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('common.responses');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->label(__('common.user'))
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('common.user'))
                    ->default(__('common.anonymous'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($state): string => $state === __('common.anonymous') ? 'gray' : 'info'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('common.ip_address'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('answers_count')
                    ->counts('answers')
                    ->label(__('common.answer_count'))
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.date'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_user')
                    ->label(__('common.registered_users'))
                    ->query(fn ($query) => $query->whereNotNull('user_id')),
                Tables\Filters\Filter::make('anonymous')
                    ->label(__('common.anonymous'))
                    ->query(fn ($query) => $query->whereNull('user_id')),
            ])
            ->actions([
                Tables\Actions\Action::make('view_answers')
                    ->label(__('common.view_answers'))
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (SurveyResponse $record) => __('common.response_details') . ' #' . $record->id)
                    ->modalContent(fn (SurveyResponse $record) => view('filament.resources.survey.response-detail', [
                        'response' => $record->load(['answers.question', 'user']),
                    ]))
                    ->modalWidth('lg')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('common.close')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('common.no_responses'))
            ->emptyStateDescription(__('common.no_responses_description'))
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}
