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

        // Automatically set category_id from the related project
        if ($suggestion->project_id && ! $suggestion->category_id) {
            $project = \App\Models\Project::find($suggestion->project_id);
            if ($project) {
                // First try to get category_id directly from the project
                if ($project->category_id) {
                    $suggestion->category_id = $project->category_id;
                } else {
                    // Otherwise, try to get it from the project's first project group
                    $firstGroup = $project->projectGroups()->first();
                    if ($firstGroup) {
                        $suggestion->category_id = $firstGroup->category_id;
                    }
                }
            }
        }
    }

    public function updating(Suggestion $suggestion): void
    {
        if (Auth::check()) {
            $suggestion->updated_by_id = Auth::id();
        }

        // Check if status has changed to a decision status
        if ($suggestion->isDirty('status')) {
            $newStatus = $suggestion->status;
            $oldStatus = $suggestion->getOriginal('status');

            // Send notification if status changed to a decision status
            if (in_array($newStatus, [
                \App\Enums\SuggestionStatusEnum::APPROVED,
                \App\Enums\SuggestionStatusEnum::REJECTED,
                \App\Enums\SuggestionStatusEnum::IMPLEMENTED,
            ])) {
                // Send notification after the model is saved
                $suggestion->registerModelEvent('saved', function ($model) use ($newStatus) {
                    if ($model->createdBy) {
                        $model->createdBy->notify(
                            new \App\Notifications\DecisionResultNotification($model, $newStatus)
                        );
                    }
                });
            }
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
