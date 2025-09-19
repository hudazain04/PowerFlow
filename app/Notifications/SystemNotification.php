<?php

namespace App\Notifications;

use App\Notifications\Channels\FcmChannel;
use App\Types\UserTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public function __construct(public array $data) {}

    public function via($notifiable)
    {
        if ($notifiable instanceof \App\Models\User && $notifiable->hasRole(UserTypes::ADMIN)) {
            return ['database', 'broadcast'];
        }
        if (($notifiable instanceof \App\Models\Employee && $notifiable->hasRole(UserTypes::EMPLOYEE))|| ($notifiable instanceof \App\Models\User && $notifiable->hasRole(UserTypes::USER))) {
            return ['database', FcmChannel::class];
        }
        return ['database'];
    }

    public function toFcm($notifiable): array
    {
        return [
            'title' => $this->data['title'],
            'body'  => $this->data['body'],
            'data'  => $this->data['extra'] ?? [],
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => $this->data['title'],
            'body'    => $this->data['body'],
            'extra'   => $this->data['extra'] ?? null,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->data['title'],
            'body' => $this->data['body'],
            'extra' => $this->data['extra'] ?? [],
        ]);
    }

}
