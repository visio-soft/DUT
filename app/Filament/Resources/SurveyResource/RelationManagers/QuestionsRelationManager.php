<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('common.questions');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('text')
                    ->label(__('common.question_text'))
                    ->required()
                    ->maxLength(500)
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
                    ->visible(fn (Forms\Get $get) => $get('type') === 'multiple_choice')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->label(__('common.order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('text')
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),
                Tables\Columns\TextColumn::make('text')
                    ->label(__('common.question_text'))
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('common.question_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'text' => __('common.open_ended'),
                        'multiple_choice' => __('common.multiple_choice'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'info',
                        'multiple_choice' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('answers_count')
                    ->counts('answers')
                    ->label(__('common.answer_count'))
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('common.question_type'))
                    ->options([
                        'text' => __('common.open_ended'),
                        'multiple_choice' => __('common.multiple_choice'),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
