<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as ShieldRoleResource;

class RoleResource extends ShieldRoleResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('common.user_management');
    }
}
