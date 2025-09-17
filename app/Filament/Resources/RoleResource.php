<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use App\Filament\Resources\RoleResource\Pages;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Spatie\Permission\Models\Permission;

class RoleResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament-shield::filament-shield.section.basic_info'))
                    ->description('Rol hakkında temel bilgileri girin')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->helperText(__('filament-shield::filament-shield.helpers.role_name'))
                                    ->unique(
                                        ignoreRecord: true, /** @phpstan-ignore-next-line */
                                        modifyRuleUsing: fn (Unique $rule) => Utils::isTenancyEnabled() ? $rule->where(Utils::getTenantModelForeignKey(), Filament::getTenant()?->id) : $rule
                                    )
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Örn: editor, moderator, admin')
                                    ->autocomplete('off'),

                                Forms\Components\TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->helperText(__('filament-shield::filament-shield.helpers.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated(),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->helperText('Bu rolün ne işe yaradığını ve hangi yetkilere sahip olduğunu açıklayın')
                            ->placeholder('Bu rol, sistem editörlerine içerik yönetimi yetkisi verir...')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\Select::make(config('permission.column_names.team_foreign_key'))
                            ->label(__('filament-shield::filament-shield.field.team'))
                            ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                            /** @phpstan-ignore-next-line */
                            ->default([Filament::getTenant()?->id])
                            ->options(fn (): Arrayable => Utils::getTenantModel() ? Utils::getTenantModel()::pluck('name', 'id') : collect())
                            ->hidden(fn (): bool => ! (static::shield()->isCentralApp() && Utils::isTenancyEnabled()))
                            ->dehydrated(fn (): bool => ! (static::shield()->isCentralApp() && Utils::isTenancyEnabled())),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Forms\Components\Section::make(__('filament-shield::filament-shield.field.permissions'))
                    ->description(__('filament-shield::filament-shield.helpers.permissions_help'))
                    ->schema([
                        ShieldSelectAllToggle::make('select_all')
                            ->onIcon('heroicon-s-shield-check')
                            ->offIcon('heroicon-s-shield-exclamation')
                            ->label(__('filament-shield::filament-shield.field.select_all.name'))
                            ->helperText(fn (): HtmlString => new HtmlString(__('filament-shield::filament-shield.field.select_all.message')))
                            ->dehydrated(fn (bool $state): bool => $state),

                        Forms\Components\Tabs::make('permission_groups')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('user_management')
                                    ->label('👥 Kullanıcı Yönetimi')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('user_permissions')
                                            ->hiddenLabel()
                                            ->options(fn () => self::getPermissionsByPattern(['user']))
                                            ->columns(2)
                                            ->bulkToggleable()
                                    ]),

                                Forms\Components\Tabs\Tab::make('content_management')
                                    ->label('📝 İçerik Yönetimi')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('content_permissions')
                                            ->hiddenLabel()
                                            ->options(fn () => self::getPermissionsByPattern(['oneri', 'project', 'category', 'obje']))
                                            ->columns(2)
                                            ->bulkToggleable()
                                    ]),

                                Forms\Components\Tabs\Tab::make('system_management')
                                    ->label('⚙️ Sistem Yönetimi')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('system_permissions')
                                            ->hiddenLabel()
                                            ->options(fn () => self::getPermissionsByPattern(['role', 'permission', 'widget', 'page']))
                                            ->columns(2)
                                            ->bulkToggleable()
                                    ]),

                                Forms\Components\Tabs\Tab::make('all_permissions')
                                    ->label('🔐 Tüm İzinler')
                                    ->schema([
                                        static::getShieldFormComponents(),
                                    ]),
                            ])
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('font-medium')
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description ? Str::limit($record->description, 50) : $record->permissions_count . ' izin')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable()
                    ->wrap()
                    ->placeholder('Açıklama yok'),

                Tables\Columns\TextColumn::make('guard_name')
                    ->badge()
                    ->color('warning')
                    ->label(__('filament-shield::filament-shield.column.guard_name'))
                    ->tooltip('Güvenlik koruyucusu'),

                Tables\Columns\TextColumn::make('team.name')
                    ->default('Genel')
                    ->badge()
                    ->color(fn (mixed $state): string => str($state)->contains('Genel') ? 'gray' : 'primary')
                    ->label(__('filament-shield::filament-shield.column.team'))
                    ->searchable()
                    ->visible(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled()),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.permissions'))
                    ->counts('permissions')
                    ->colors(['success'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('users_count')
                    ->badge()
                    ->label('Kullanıcılar')
                    ->counts('users')
                    ->colors(['info'])
                    ->sortable()
                    ->tooltip('Bu role sahip kullanıcı sayısı'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record) => $record->updated_at?->format('d.m.Y H:i:s')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label('Güvenlik Koruyucusu')
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ])
                    ->default('web'),

                Tables\Filters\Filter::make('has_users')
                    ->label('Kullanıcısı Olan Roller')
                    ->query(fn ($query) => $query->has('users'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_users')
                    ->label('Kullanıcısı Olmayan Roller')
                    ->query(fn ($query) => $query->doesntHave('users'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Görüntüle'),
                Tables\Actions\EditAction::make()
                    ->label('Düzenle'),
                Tables\Actions\DeleteAction::make()
                    ->label('Sil')
                    ->requiresConfirmation()
                    ->modalHeading('Rolü Sil')
                    ->modalDescription('Bu rolü silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                    ->modalSubmitActionLabel('Evet, Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Seçilenleri Sil')
                        ->requiresConfirmation()
                        ->modalHeading('Rolleri Sil')
                        ->modalDescription('Seçili rolleri silmek istediğinizden emin misiniz?')
                        ->modalSubmitActionLabel('Evet, Sil'),

                    Tables\Actions\BulkAction::make('bulk_assign_permissions')
                        ->label('İzin Ata')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('permissions')
                                ->label('Atanacak İzinler')
                                ->multiple()
                                ->options(fn () => \Spatie\Permission\Models\Permission::all()->pluck('name', 'name'))
                                ->searchable()
                                ->preload()
                                ->placeholder('İzinleri seçin')
                                ->helperText('Seçili rollere bu izinler eklenecek (mevcut izinler korunur)'),
                        ])
                        ->action(function (array $data, \Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $role) {
                                foreach ($data['permissions'] as $permission) {
                                    $role->givePermissionTo($permission);
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title(count($records) . ' role izinler başarıyla atandı')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalSubmitActionLabel('İzinleri Ata'),

                    Tables\Actions\BulkAction::make('bulk_revoke_permissions')
                        ->label('İzin Kaldır')
                        ->icon('heroicon-o-shield-exclamation')
                        ->color('danger')
                        ->form([
                            Forms\Components\Select::make('permissions')
                                ->label('Kaldırılacak İzinler')
                                ->multiple()
                                ->options(fn () => \Spatie\Permission\Models\Permission::all()->pluck('name', 'name'))
                                ->searchable()
                                ->preload()
                                ->placeholder('İzinleri seçin')
                                ->helperText('Seçili rollerden bu izinler kaldırılacak'),
                        ])
                        ->action(function (array $data, \Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $role) {
                                foreach ($data['permissions'] as $permission) {
                                    $role->revokePermissionTo($permission);
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title(count($records) . ' rolden izinler başarıyla kaldırıldı')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalSubmitActionLabel('İzinleri Kaldır'),
                ])
            ])
            ->emptyStateHeading('Henüz rol yok')
            ->emptyStateDescription('Yeni bir rol oluşturarak başlayın.')
            ->emptyStateIcon('heroicon-o-shield-exclamation');
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster() ?? static::$cluster;
    }

    public static function getModel(): string
    {
        return Utils::getRoleModel();
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.roles');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Utils::isResourceNavigationRegistered();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.group.roles_user_management');
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-shield::filament-shield.nav.role.label');
    }

    public static function getNavigationIcon(): string
    {
        return __('filament-shield::filament-shield.nav.role.icon');
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return Utils::getSubNavigationPosition() ?? static::$subNavigationPosition;
    }

    public static function getSlug(): string
    {
        return Utils::getResourceSlug();
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }

    public static function isScopedToTenant(): bool
    {
        return Utils::isScopedToTenant();
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable() && count(static::getGloballySearchableAttributes()) && static::canViewAny();
    }

    protected static function getPermissionsByPattern(array $patterns): array
    {
        $permissions = Permission::all();
        $filteredPermissions = [];

        foreach ($permissions as $permission) {
            foreach ($patterns as $pattern) {
                if (str_contains($permission->name, $pattern)) {
                    $filteredPermissions[$permission->name] = self::formatPermissionLabel($permission->name);
                    break;
                }
            }
        }

        return $filteredPermissions;
    }

    protected static function formatPermissionLabel(string $permissionName): string
    {
        $labels = [
            'view' => 'Görüntüle',
            'view_any' => 'Tümünü Görüntüle',
            'create' => 'Oluştur',
            'update' => 'Güncelle',
            'delete' => 'Sil',
            'delete_any' => 'Toplu Sil',
            'force_delete' => 'Kalıcı Sil',
            'force_delete_any' => 'Toplu Kalıcı Sil',
            'restore' => 'Geri Yükle',
            'restore_any' => 'Toplu Geri Yükle',
            'replicate' => 'Kopyala',
            'reorder' => 'Sırala',
        ];

        $parts = explode('_', $permissionName);
        $resource = array_pop($parts);
        $action = implode('_', $parts);

        $actionLabel = $labels[$action] ?? ucfirst($action);
        $resourceLabel = ucfirst(str_replace(['_', '::'], [' ', ' '], $resource));

        return $actionLabel . ' - ' . $resourceLabel;
    }
}
