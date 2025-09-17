<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjeResource\Pages;
use App\Filament\Resources\ObjeResource\RelationManagers;
use App\Models\Obje;
use App\Rules\ImageFormatRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ObjeResource extends Resource
{
    protected static ?string $model = Obje::class;

    public static function getNavigationGroup(): string
    {
        return __('filament.navigation.group.object_management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.object.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.object.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.object.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.object_information'))
                    ->description(__('app.object_information_description'))
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->label(__('filament.resources.object.fields.category'))
                            ->required()
                            ->options(\App\Models\Obje::CATEGORIES)
                            ->placeholder(__('filament.placeholders.enter_object_category')),
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.resources.object.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.enter_object_name')),
                        SpatieMediaLibraryFileUpload::make('images')
                            ->required()
                            ->label(__('filament.resources.object.fields.images'))
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->directory('objeler')
                            // Removed size and resize constraints per requirement: only restrict by file type (jpeg/jpg/png)
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->helperText(__('filament.helper_texts.recommended_format'))
                            ->hintColor('info')
                            ->preserveFilenames()
                            ->columnSpanFull()
                            ->visibility('public')
                            ->disk('public')
                            ->rules(['required']),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('images')
                    ->label(__('filament.resources.object.fields.images'))
                    ->collection('images')
                    ->height(50)
                    ->width(50)
                    ->square()
                    ->disk('public')
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.resources.object.fields.name'))
                    ->searchable(['name'])
                    ->sortable('name'),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.resources.object.fields.category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.resources.object.fields.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.resources.object.fields.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('filament.resources.object.fields.category'))
                    ->options(\App\Models\Obje::CATEGORIES)
                    ->placeholder(__('app.all_categories'))
                    ->multiple()
                    ->searchable(),
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
            'index' => Pages\ListObjes::route('/'),
            'create' => Pages\CreateObje::route('/create'),
            'edit' => Pages\EditObje::route('/{record}/edit'),
        ];
    }
}
