<?php

namespace App\Notifications;

use App\Enums\SuggestionStatusEnum;
use App\Models\Suggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DecisionResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Suggestion $suggestion;

    public SuggestionStatusEnum $decision;

    public ?string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Suggestion $suggestion, SuggestionStatusEnum $decision, ?string $reason = null)
    {
        $this->suggestion = $suggestion;
        $this->decision = $decision;
        $this->reason = $reason;
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
        $message = (new MailMessage)
            ->subject('Öneri Sonucu: '.$this->decision->getLabel())
            ->greeting('Merhaba '.$notifiable->name.',')
            ->line('Öneriniz hakkında bir karar verildi: "'.$this->suggestion->title.'"');

        $message->line('Durum: '.$this->decision->getLabel());

        if ($this->reason) {
            $message->line('Açıklama: '.$this->reason);
        }

        $message->action('Öneriyi Görüntüle', url('/suggestions/'.$this->suggestion->id));

        if ($this->decision === SuggestionStatusEnum::OPEN) {
            $message->line('Değerli katkınız için teşekkür ederiz!');
        } else {
            $message->line('İlginiz için teşekkür ederiz. Başka önerileriniz için bizi bekleriz.');
        }

        return $message;
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
            'decision' => $this->decision->value,
            'decision_label' => $this->decision->getLabel(),
            'reason' => $this->reason,
            'message' => 'Öneriniz hakkında karar verildi: '.$this->decision->getLabel(),
        ];
    }
}
