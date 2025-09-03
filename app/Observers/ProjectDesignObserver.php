<?php

namespace App\Observers;

use App\Models\ProjectDesign;

class ProjectDesignObserver
{
    /**
     * Handle the ProjectDesign "created" event.
     */
    public function created(ProjectDesign $projectDesign): void
    {
        // Tasarım oluşturulduğunda projenin design_completed alanını true yap
        if ($projectDesign->project) {
            $projectDesign->project->update(['design_completed' => true]);
        }
    }

    /**
     * Handle the ProjectDesign "updated" event.
     */
    public function updated(ProjectDesign $projectDesign): void
    {
        //
    }

    /**
     * Handle the ProjectDesign "deleting" event.
     */
    public function deleting(ProjectDesign $projectDesign): void
    {
        // ProjectDesign silinmeden önce like'ları sil
        $projectDesign->likes()->delete();
    }

    /**
     * Handle the ProjectDesign "deleted" event.
     */
    public function deleted(ProjectDesign $projectDesign): void
    {
        // Tasarım silindiğinde projenin design_completed alanını false yap
        if ($projectDesign->project) {
            $projectDesign->project->update(['design_completed' => false]);
        }
    }

    /**
     * Handle the ProjectDesign "restored" event.
     */
    public function restored(ProjectDesign $projectDesign): void
    {
        // Tasarım restore edildiğinde projenin design_completed alanını true yap
        if ($projectDesign->project) {
            $projectDesign->project->update(['design_completed' => true]);
        }
    }

    /**
     * Handle the ProjectDesign "force deleted" event.
     */
    public function forceDeleted(ProjectDesign $projectDesign): void
    {
        // Tasarım kalıcı olarak silindiğinde projenin design_completed alanını false yap
        if ($projectDesign->project) {
            $projectDesign->project->update(['design_completed' => false]);
        }
    }
}
