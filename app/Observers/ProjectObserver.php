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
}
