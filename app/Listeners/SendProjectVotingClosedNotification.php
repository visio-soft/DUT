<?php

namespace App\Listeners;

use App\Events\ProjectVotingClosed;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProjectVotingClosedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ProjectVotingClosed $event): void
    {
        $project = $event->project;

        // Collect users who liked the project
        $userIdsFromLikes = $project->likes()->pluck('user_id');

        // Collect users who commented on the project
        $userIdsFromComments = $project->comments()->pluck('user_id');

        // Collect users who filled a survey for the project
        // Survey has many responses, response belongs to user
        $userIdsFromSurveys = $project->surveys()->with('responses')->get()->flatMap(function ($survey) {
            return $survey->responses->pluck('user_id');
        });

        // Merge all user IDs and get unique values
        $userIds = $userIdsFromLikes
            ->concat($userIdsFromComments)
            ->concat($userIdsFromSurveys)
            ->filter() // Remove nulls if any
            ->unique();

        if ($userIds->isEmpty()) {
            return;
        }

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            Notification::make()
                ->title('Proje Oylaması Kapandı')
                ->body("Takip ettiğiniz {$project->title} projesinin oylama süreci tamamlanmıştır.")
                ->success()
                ->sendToDatabase($user);
        }
    }
}
