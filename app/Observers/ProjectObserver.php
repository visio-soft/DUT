<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    public function creating(Project $project): void
    {
        if (Auth::check()) {
            $project->created_by_id = Auth::id();
        }
    }

    public function updating(Project $project): void
    {
        if (Auth::check()) {
            $project->updated_by_id = Auth::id();
        }

        if ($project->isDirty('status') && $project->status === \App\Enums\ProjectStatusEnum::VOTING_CLOSED) {
            \App\Events\ProjectVotingClosed::dispatch($project);
        }
    }

    public function saving(Project $project): void
    {
        // Do not override if category_id is already set (e.g., inferred by form hooks)
        if (! empty($project->category_id)) {
            return;
        }

        // If project groups are attached and at least one has a category
        $firstGroupWithCategory = $project->projectGroups()
            ->with('category')
            ->first();

        if ($firstGroupWithCategory && $firstGroupWithCategory->category_id) {
            $project->category_id = $firstGroupWithCategory->category_id;
        }
    }

    /**
     * Handle the Project "deleting" event.
     * Parent project silinince child design ve design like'ları da silinsin
     */
    public function deleting(Project $project): void
    {
        // Önce design varsa onu sil (bu otomatik olarak design like'ları da silecek)
        if ($project->design) {
            $project->design->delete();
        }
    }
}
