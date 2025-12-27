<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Project;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Survey Details')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proje')
                            ->relationship('project', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('title')
                            ->label('Anket Başlığı')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('status')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Questions')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('text')
                                    ->label('Soru Metni')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('type')
                                    ->label('Soru Tipi')
                                    ->options([
                                        'text' => 'Açık Uçlu (Metin)',
                                        'multiple_choice' => 'Çoktan Seçmeli',
                                    ])
                                    ->required()
                                    ->live(),
                                
                                // Options Repeater for Multiple Choice (max 5: a, b, c, d, e)
                                Forms\Components\Repeater::make('options')
                                    ->label('Seçenekler (A, B, C, D, E)')
                                    ->schema([
                                        Forms\Components\TextInput::make('text')
                                            ->label('Seçenek Metni')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->reorderable()
                                    ->maxItems(5)
                                    ->minItems(2)
                                    ->defaultItems(2)
                                    ->addActionLabel('Seçenek Ekle (max 5)')
                                    ->itemLabel(fn (array $state, int $index): string => chr(65 + $index) . ') ' . ($state['text'] ?? ''))
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'multiple_choice'),
                                
                                // Hidden field to force required=true
                                Forms\Components\Hidden::make('is_required')
                                    ->default(true),
                            ])
                            ->orderColumn('order')
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->label('Sorular'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.title')
                    ->label('Proje')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Durum')
                    ->boolean(),
                Tables\Columns\TextColumn::make('responses_count')
                    ->counts('responses')
                    ->label('Cevaplar'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
