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

    // Place this resource right after the Öneri resource in the sidebar
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.group.suggestion_management');
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

    protected static bool $shouldRegisterNavigation = true;

    // Yetkilendirme kontrolünü geçici olarak devre dışı bırak
    public static function canViewAny(): bool
    {
        return true; // Test amaçlı - daha sonra kaldırılacak
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
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->helperText(__('filament.helper_texts.recommended_format'))
                            ->hintColor('info')
                            ->preserveFilenames()
                            ->columnSpanFull()
                            ->visibility('public')
                            ->disk('public')
                            ->downloadable()
                            ->openable()
                            ->deletable()
                            ->rules(['required'])
                            ->maxSize(10240), // 10MB max
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
                    ->visibility('public')
                    ->checkFileExistence(false),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('app.view'))
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->label(__('app.edit'))
                        ->color('warning')
                        ->icon('heroicon-o-pencil'),
                    Tables\Actions\DeleteAction::make()
                        ->label(__('app.delete'))
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation(),
                ])
                ->label(__('app.actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button()
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
            'view' => Pages\ViewObje::route('/{record}'),
            'edit' => Pages\EditObje::route('/{record}/edit'),
        ];
    }
}
