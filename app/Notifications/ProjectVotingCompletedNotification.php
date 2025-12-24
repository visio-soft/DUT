<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class ProjectVotingCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(public \App\Models\Project $project)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => 'Oylama Tamamlandı',
            'message' => "{$this->project->title} projesinin oylama süreci tamamlanmıştır.",
            'action_url' => route('projects.show', $this->project),
        ];
    }
}
