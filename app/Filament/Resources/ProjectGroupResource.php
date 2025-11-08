<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectGroupResource\Pages;
use App\Models\ProjectGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectGroupResource extends Resource
{
    protected static ?string $model = ProjectGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('common.project_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.project_groups');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.project_groups');
    }

    public static function getModelLabel(): string
    {
        return __('common.project_group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('common.project_group'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label(__('common.project_category'))
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('common.project_group')),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->label(__('common.project_category')),
                Tables\Columns\TextColumn::make('projects_count')
                    ->counts('projects')
                    ->label(__('common.projects'))
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('suggestions_count')
                    ->counts('suggestions')
                    ->label(__('common.suggestions'))
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProjectGroups::route('/'),
            'create' => Pages\CreateProjectGroup::route('/create'),
            'edit' => Pages\EditProjectGroup::route('/{record}/edit'),
        ];
    }
}
