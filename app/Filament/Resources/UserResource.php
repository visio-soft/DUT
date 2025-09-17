<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    public static function getNavigationGroup(): string
    {
        return __('filament.navigation.group.flix_management');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.user.plural_label');
    }
    
    public static function getModelLabel(): string
    {
        return __('filament.resources.user.label');
    }
    
    public static function getNavigationLabel(): string
    {
        return __('filament.resources.user.navigation_label');
    }
    
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.user_information'))
                    ->description(__('filament.sections.user_information_description'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.resources.user.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.enter_user_name')),
                            
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament.resources.user.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder(__('filament.placeholders.enter_email')),
                            
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament.resources.user.fields.password'))
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->placeholder(__('filament.placeholders.enter_password'))
                            ->helperText(__('filament.helper_texts.strong_password')),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make(__('filament.sections.roles_permissions'))
                    ->description(__('filament.sections.roles_permissions_description'))
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label(__('filament.resources.user.fields.roles'))
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->preload()
                            ->searchable()
                            ->placeholder(__('filament.placeholders.select_user_roles'))
                            ->helperText(__('filament.helper_texts.select_user_roles_help')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.resources.user.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament.resources.user.fields.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage(__('filament.notifications.email_copied'))
                    ->copyMessageDuration(1500),
                    
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('filament.resources.user.fields.roles'))
                    ->badge()
                    ->color('primary')
                    ->separator(',')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.resources.user.fields.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.resources.user.fields.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('filament.filters.role'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->placeholder(__('filament.placeholders.filter_by_role')),
                    
                Tables\Filters\Filter::make('created_from')
                    ->label(__('filament.filters.creation_date'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('filament.filters.start_date')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('filament.filters.end_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('filament.resources.user.actions.delete_user'))
                    ->modalDescription(__('filament.confirmations.delete_user'))
                    ->modalSubmitActionLabel(__('filament.confirmations.yes_delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('filament.resources.user.actions.delete_users'))
                        ->modalDescription(__('filament.confirmations.delete_users'))
                        ->modalSubmitActionLabel(__('filament.confirmations.yes_delete')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
