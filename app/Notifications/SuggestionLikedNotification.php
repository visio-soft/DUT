<?php

namespace App\Notifications;

use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuggestionLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Suggestion $suggestion;

    public User $liker;

    /**
     * Create a new notification instance.
     */
    public function __construct(Suggestion $suggestion, User $liker)
    {
        $this->suggestion = $suggestion;
        $this->liker = $liker;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Öneriniz Beğenildi!')
            ->greeting('Merhaba '.$notifiable->name.',')
            ->line($this->liker->name.' önerinizi beğendi: "'.$this->suggestion->title.'"')
            ->action('Öneriyi Görüntüle', url('/suggestions/'.$this->suggestion->id))
            ->line('Desteğiniz için teşekkür ederiz!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'suggestion_id' => $this->suggestion->id,
            'suggestion_title' => $this->suggestion->title,
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'message' => $this->liker->name.' önerinizi beğendi: "'.$this->suggestion->title.'"',
        ];
    }
}
