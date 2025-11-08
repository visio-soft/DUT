<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\ProjectGroup;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure at least one project group is selected and all share the same category.
        $selected = $data['projectGroups'] ?? [];
        $selected = is_array($selected) ? array_filter($selected) : (empty($selected) ? [] : [$selected]);

        if (empty($selected)) {
            \Illuminate\Validation\ValidationException::withMessages([
                'projectGroups' => [__('validation.required', ['attribute' => __('common.project_group')])],
            ])->throw();
        }

        $groups = ProjectGroup::query()->select('id', 'category_id')->whereIn('id', $selected)->get();
        $categoryIds = $groups->pluck('category_id')->filter()->unique()->values();

        if ($categoryIds->count() !== 1) {
            \Illuminate\Validation\ValidationException::withMessages([
                'projectGroups' => [__('common.project_groups_must_share_same_category')],
            ])->throw();
        }

        // Set the inferred category_id from the selected groups
        $data['category_id'] = $categoryIds->first();

        return $data;
    }
}
