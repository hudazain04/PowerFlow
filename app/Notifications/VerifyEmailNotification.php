<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

//implements ShouldQueue
class VerifyEmailNotification extends  VerifyEmail
{
    use Queueable;
    private $user;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
//        $this->user=$user;
    }


    protected function verificationUrl($notifiable)
    {

//        URL::forceRootUrl('http://localhost:8000');

//        route("verification.verify");
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),

            ]
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail( $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
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
