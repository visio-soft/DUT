<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class SuggestionObserver
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

    /**
     * Handle the Oneri "deleting" event.
     * Parent oneri silinince child design ve design like'ları da silinsin
     */
    public function deleting(Oneri $oneri): void
    {
        // Önce design varsa onu sil (bu otomatik olarak design like'ları da silecek)
        if ($oneri->design) {
            $oneri->design->delete();
        }
    }
}
