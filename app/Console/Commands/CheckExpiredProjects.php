<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\SuggestionLike;
use App\Models\User;
use App\Notifications\ProjectVotingCompletedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckExpiredProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for expired projects and notifies users who voted on them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired projects...');

        // Find projects that:
        // 1. Have an end_date in the past
        // 2. Have NOT sent the notification yet (is_notification_sent = false)
        // 3. Are suggestions (implicitly handled by Project scope but good to be aware)
        
        $expiredProjects = Project::query()
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->where('is_notification_sent', false)
            ->get();

        if ($expiredProjects->isEmpty()) {
            $this->info('No new expired projects found.');
            return;
        }

        foreach ($expiredProjects as $project) {
            $this->info("Processing project: {$project->title} (ID: {$project->id})");
            
            // Find all unique users who liked any suggestion within this project
            // We join suggestion_likes -> suggestions -> project
            $this->notifyVoters($project);
            
            // Mark as sent
            $project->update(['is_notification_sent' => true]);
            $this->info("Marked project as notification sent.");
        }
        
        $this->info('Done.');
    }
    
    protected function notifyVoters(Project $project)
    {
        // Get all suggestion IDs belonging to this project
        $suggestionIds = $project->suggestions()->pluck('id');
        
        if ($suggestionIds->isEmpty()) {
            $this->info(" - No suggestions in this project.");
            return;
        }

        // Get unique user IDs who liked these suggestions
        // suggestion_likes.user_id
        $userIds = SuggestionLike::whereIn('suggestion_id', $suggestionIds)
            ->distinct()
            ->pluck('user_id');
            
        if ($userIds->isEmpty()) {
            $this->info(" - No voters found for this project.");
            return;
        }
        
        $users = User::whereIn('id', $userIds)->get();
        $count = $users->count();
        
        $this->info(" - Sending notification to {$count} users...");
        
        Notification::send($users, new ProjectVotingCompletedNotification($project));
    }
}
