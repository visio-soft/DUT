<?php

namespace App\Observers;

use App\Models\Oneri;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    public function creating(Oneri $oneri): void
    {
        if (Auth::check()) {
            $oneri->created_by_id = Auth::id();
        }
    }

    public function updating(Oneri $oneri): void
    {
        if (Auth::check()) {
            $oneri->updated_by_id = Auth::id();
        }
    }
}
