<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string
    {
        return __('filament.navigation.group.project_management');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.category.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.category.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.category.navigation_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('filament.resources.project.fields.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('filament.placeholders.enter_project_name')),

                Forms\Components\DateTimePicker::make('start_datetime')->label(__('filament.resources.category.fields.start_datetime'))
                    ->required()
                    ->seconds(false)
                    ->format('Y-m-d H:i')
                    ->displayFormat('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->placeholder(__('filament.placeholders.start_datetime_placeholder'))
                    ->helperText(__('filament.helper_texts.start_datetime_help')),

                Forms\Components\DateTimePicker::make('end_datetime')->label(__('filament.resources.category.fields.end_datetime'))
                    ->required()
                    ->seconds(false)
                    ->format('Y-m-d H:i')
                    ->displayFormat('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->placeholder(__('filament.placeholders.end_datetime_placeholder'))
                    ->helperText(__('filament.helper_texts.end_datetime_help')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.resources.category.fields.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_datetime')
                    ->label(__('filament.resources.category.fields.start_date'))
                    ->dateTime('d.m.Y H:i')
                    ->placeholder(__('filament.placeholders.dash'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_datetime')
                    ->label(__('filament.resources.category.fields.end_date'))
                    ->dateTime('d.m.Y H:i')
                    ->placeholder(__('filament.placeholders.dash'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('oneriler_count')
                    ->label(__('filament.resources.category.fields.suggestions_count'))
                    ->counts('oneriler')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.resources.category.fields.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.resources.category.actions.edit'))
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->label(__('filament.resources.category.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('oneriler');
    }
}
