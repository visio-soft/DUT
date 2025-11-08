<?php

namespace App\Observers;

use App\Models\LegacyProject;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    public function creating(LegacyProject $project): void
    {
        if (Auth::check()) {
            $project->created_by_id = Auth::id();
        }
    }

    public function updating(LegacyProject $project): void
    {
        if (Auth::check()) {
            $project->updated_by_id = Auth::id();
        }
    }

    /**
     * Handle the LegacyProject "deleting" event.
     * Parent project silinince child design ve design like'ları da silinsin
     */
    public function deleting(LegacyProject $project): void
    {
        // Önce design varsa onu sil (bu otomatik olarak design like'ları da silecek)
        if ($project->design) {
            $project->design->delete();
        }
    }
}
