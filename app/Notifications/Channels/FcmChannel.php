<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (! $token = $notifiable->routeNotificationFor('fcm')) {
            return;
        }

        $message = $notification->toFcm($notifiable);
        app('firebase.messaging')->send(
            CloudMessage::withTarget('token', $token)
                ->withNotification(
                    FirebaseNotification::create(
                        $message['title'] ?? 'Notification',
                        $message['body'] ?? ''
                    )
                )
                ->withData($message['data'] ?? [])

        );
        Firebase::messaging()->send($message);
    }
}
