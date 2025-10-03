<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $token
    )
    {
        //
    }
    public function verification($notifiable){
        $cleanToken = explode('&', $this->token)[0];
//        URL::forceRootUrl('http://localhost:8000');
        return URL::temporarySignedRoute('verification.pass',
            now()->addMinutes(config('app.url').'/reset-password?token='.$this->token),
            [
                'id' => $notifiable->getKey(),
//                'hash' => sha1($notifiable->getEmailForVerification()),
                 'token'=>$cleanToken
            ]
        );

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
//        $url = url(config('app.url').'/reset-password?token='.$this->token);
        $verificationUrl = $this->verification($notifiable);
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $verificationUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id'=>$this->id,
        ];
    }
}
