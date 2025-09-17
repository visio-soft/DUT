<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function afterCreate(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                /** @phpstan-ignore-next-line */
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }
}
