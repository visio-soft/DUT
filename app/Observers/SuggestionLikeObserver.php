<?php

namespace App\Observers;

use App\Models\SuggestionLike;
use App\Notifications\SuggestionLikedNotification;

class SuggestionLikeObserver
{
    /**
     * Handle the SuggestionLike "created" event.
     */
    public function created(SuggestionLike $suggestionLike): void
    {
        // Send notification to suggestion creator when someone likes their suggestion
        $suggestion = $suggestionLike->suggestion;

        // Check if suggestion exists and has a creator
        if (! $suggestion) {
            return;
        }

        // Only send notification if the liker is not the suggestion creator
        if ($suggestion->createdBy && $suggestionLike->user_id !== $suggestion->created_by_id) {
            $suggestion->createdBy->notify(
                new SuggestionLikedNotification($suggestion, $suggestionLike->user)
            );
        }
    }

    /**
     * Handle the SuggestionLike "updated" event.
     */
    public function updated(SuggestionLike $suggestionLike): void
    {
        //
    }

    /**
     * Handle the SuggestionLike "deleted" event.
     */
    public function deleted(SuggestionLike $suggestionLike): void
    {
        //
    }

    /**
     * Handle the SuggestionLike "restored" event.
     */
    public function restored(SuggestionLike $suggestionLike): void
    {
        //
    }

    /**
     * Handle the SuggestionLike "force deleted" event.
     */
    public function forceDeleted(SuggestionLike $suggestionLike): void
    {
        //
    }
}
