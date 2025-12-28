<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $recipients = match ($data['recipients']) {
            'all' => User::all(),
            'specific' => User::whereIn('id', $data['users'])->get(),
            default => collect(),
        };

        $title = $data['title'];
        $body = $data['body'];
        $actionLabel = $data['action_label'] ?? null;
        $actionUrl = $data['action_url'] ?? null;
        $scheduledAt = ($data['send_immediately'] ?? true) ? null : ($data['scheduled_at'] ?? null);

        // Create notification instance to get the data array
        $notification = new GeneralNotification($title, $body, $actionLabel, $actionUrl);
        
        // Manually create notifications in the database to support scheduled_at
        $lastNotification = null;

        foreach ($recipients as $recipient) {
            $lastNotification = $recipient->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => get_class($notification),
                'data' => $notification->toArray($recipient),
                'read_at' => null,
                'scheduled_at' => $scheduledAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Return the last created notification or a dummy one if no recipients
        return $lastNotification ?? new \App\Models\Notification();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('common.notifications_sent_success'))
            ->body(__('common.notifications_sent_body'));
    }
}
