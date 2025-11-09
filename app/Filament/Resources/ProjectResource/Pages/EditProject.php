<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // With the new hierarchy, project_group_id is required and is a single value, not multiple
        // The form already handles the requirement, so we don't need additional validation here
        // Category is inferred from the project group
        
        return $data;
    }
}
