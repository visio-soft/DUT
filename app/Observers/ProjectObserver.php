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
