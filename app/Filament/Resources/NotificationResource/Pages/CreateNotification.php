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
        // We don't actually create a single record here in the traditional sense
        // because we might be sending to multiple users.
        // However, Filament expects a model to be returned.
        // We will send the notifications and then return the *last* created notification
        // or a dummy one if needed, but usually returning the last one is fine for redirection.

        $recipients = match ($data['recipients']) {
            'all' => User::all(),
            'specific' => User::whereIn('id', $data['users'])->get(),
            default => collect(),
        };

        $title = $data['title'];
        $body = $data['body'];
        $actionLabel = $data['action_label'] ?? null;
        $actionUrl = $data['action_url'] ?? null;

        // Send notification using Laravel's Notification system
        NotificationFacade::send($recipients, new GeneralNotification($title, $body, $actionLabel, $actionUrl));

        // Filament expects a model to be returned.
        // Since we sent database notifications, records were created in the notifications table.
        // We can fetch the last one created for one of the users to satisfy the return type.
        // Or we can just return a new instance if we redirect to index anyway.
        
        // Let's try to find one of the created notifications.
        // Since we can't easily get the IDs of created notifications from the send method,
        // we will just return a dummy instance or the last created notification in the system.
        // A better approach for Filament might be to not use CreateRecord but a custom page,
        // but CreateRecord is convenient for the form.
        
        // Let's return the last created notification to be safe.
        return \App\Models\Notification::latest()->first();
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
