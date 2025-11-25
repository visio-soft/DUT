<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('common.project_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.project_categories');
    }

    public static function getModelLabel(): string
    {
        return __('common.project_category');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.project_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label(__('common.parent_category'))
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder(__('common.select_parent_category')),

                Forms\Components\TextInput::make('name')
                    ->label(__('common.project_category'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('common.enter_category_name')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('common.parent_category'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.project_category'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Alt Kategori Sayısı')
                    ->counts('children')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('project_groups_count')
                    ->label('Proje Grup Sayısı')
                    ->counts('projectGroups')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('projects_count')
                    ->label(__('common.project_count'))
                    ->state(function (Category $record): int {
                        return $record->projects_count;
                    })
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('suggestions_count')
                    ->label(__('common.suggestion_count'))
                    ->counts('suggestions')
                    ->sortable()
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
            ->defaultSort('name')
            ->filters([
                TrashedFilter::make(),
                Filter::make('parent_categories')
                    ->label('Ana Kategoriler')
                    ->query(fn ($query) => $query->whereNull('parent_id')),
                Filter::make('child_categories')
                    ->label('Alt Kategoriler')
                    ->query(fn ($query) => $query->whereNotNull('parent_id')),
                Filter::make('empty_categories')
                    ->label('Boş Kategoriler')
                    ->query(fn ($query) => $query->doesntHave('projectGroups')),
                Filter::make('active_categories')
                    ->label('Aktif Kategoriler')
                    ->query(fn ($query) => $query->has('projectGroups')),
                Filter::make('has_suggestions')
                    ->label('Önerisi Olan')
                    ->query(fn ($query) => $query->has('suggestions')),
            ])
            ->emptyStateHeading('Henüz proje kategorisi yok')
            ->emptyStateDescription('Yeni bir proje kategorisi oluşturarak başlayın.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed())
                    ->requiresConfirmation()
                    ->modalHeading(__('common.delete_project'))
                    ->modalDescription(__('common.delete_project_description'))
                    ->modalSubmitActionLabel(__('common.yes_delete'))
                    ->successNotificationTitle(__('common.project_deleted')),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Allow TrashedFilter to control deleted rows by removing the soft deleting scope
        // Note: 'projects' count is handled via accessor (projects_count) due to complex many-to-many relationship
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->withCount(['projectGroups', 'suggestions', 'children']);
    }
}
