<?php

namespace App\Observers;

use App\Models\Suggestion;
use Illuminate\Support\Facades\Auth;

class SuggestionObserver
{
    public function creating(Suggestion $suggestion): void
    {
        if (Auth::check()) {
            $suggestion->created_by_id = Auth::id();
        }
    }

    public function updating(Suggestion $suggestion): void
    {
        if (Auth::check()) {
            $suggestion->updated_by_id = Auth::id();
        }
    }

    /**
     * Handle the Suggestion "deleting" event.
     * Delete child design and design likes when parent suggestion is deleted
     */
    public function deleting(Suggestion $suggestion): void
    {
        // Delete design first if it exists (this will automatically delete design likes)
        if ($suggestion->design) {
            $suggestion->design->delete();
        }
    }
}
