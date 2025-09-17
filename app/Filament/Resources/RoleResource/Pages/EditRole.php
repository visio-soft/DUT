<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $rolePermissions = $this->record->permissions->pluck('name')->toArray();

        // Populate tab-specific permission fields
        $data['user_permissions'] = array_filter($rolePermissions, fn($perm) => str_contains($perm, 'user'));
        $data['content_permissions'] = array_filter($rolePermissions, fn($perm) =>
            str_contains($perm, 'oneri') || str_contains($perm, 'project') ||
            str_contains($perm, 'category') || str_contains($perm, 'obje')
        );
        $data['system_permissions'] = array_filter($rolePermissions, fn($perm) =>
            str_contains($perm, 'role') || str_contains($perm, 'permission') ||
            str_contains($perm, 'widget') || str_contains($perm, 'page')
        );

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Collect permissions from all tab groups
        $allPermissions = collect();

        // Collect permissions from each tab
        $permissionFields = ['user_permissions', 'content_permissions', 'system_permissions'];
        foreach ($permissionFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $allPermissions = $allPermissions->merge($data[$field]);
                unset($data[$field]);
            }
        }

        // Also collect from the standard shield components
        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, ['name', 'guard_name', 'select_all', 'description', Utils::getTenantModelForeignKey(), 'user_permissions', 'content_permissions', 'system_permissions']);
            })
            ->values()
            ->flatten()
            ->merge($allPermissions)
            ->unique();

        if (Arr::has($data, Utils::getTenantModelForeignKey())) {
            return Arr::only($data, ['name', 'guard_name', 'description', Utils::getTenantModelForeignKey()]);
        }

        return Arr::only($data, ['name', 'guard_name', 'description']);
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }
}
