<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('common.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.users');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.users');
    }

    public static function getModelLabel(): string
    {
        return __('common.user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.user_information'))
                    ->description(__('common.user_information_description'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('common.full_name_label'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('common.enter_user_name')),

                        Forms\Components\TextInput::make('email')
                            ->label(__('common.email_label'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder(__('common.enter_email')),

                        Forms\Components\TextInput::make('password')
                            ->label(__('common.password_label'))
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->placeholder(__('common.enter_password'))
                            ->helperText(__('common.password_helper')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('common.roles_and_permissions'))
                    ->description(__('common.roles_and_permissions_description'))
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label(__('common.roles'))
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->preload()
                            ->searchable()
                            ->placeholder(__('common.select_user_roles'))
                            ->helperText(__('common.select_roles_helper')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('common.id_label'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.name_label'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('common.email_label'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage(__('common.email_copied'))
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('common.roles'))
                    ->badge()
                    ->color('primary')
                    ->separator(',')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_date_label'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('common.updated_date_label'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('common.role_label'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->placeholder(__('common.filter_by_role')),

                Tables\Filters\Filter::make('created_from')
                    ->label(__('common.creation_date_filter'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('common.start_date')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('common.end_date')),
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
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('common.delete_user'))
                    ->modalDescription(__('common.delete_user_description'))
                    ->modalSubmitActionLabel(__('common.yes_delete_user')),

                Tables\Actions\RestoreAction::make()
                    ->label(__('common.restore'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('common.restore_user'))
                    ->modalDescription(__('common.restore_user_description'))
                    ->modalSubmitActionLabel(__('common.yes_restore_user'))
                    ->successNotificationTitle(__('common.user_restored')),

                Tables\Actions\ForceDeleteAction::make()
                    ->label(__('common.permanently_delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(__('common.force_delete_user'))
                    ->modalDescription(__('common.force_delete_user_description'))
                    ->modalSubmitActionLabel(__('common.yes_force_delete_user'))
                    ->successNotificationTitle(__('common.user_force_deleted')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading(__('common.delete_users'))
                        ->modalDescription(__('common.delete_users_description'))
                        ->modalSubmitActionLabel(__('common.yes_delete')),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
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
