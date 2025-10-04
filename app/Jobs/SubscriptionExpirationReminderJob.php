<?php

namespace App\Jobs;

use App\Services\NotificationService;
use App\Types\NotificationTypes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SubscriptionExpirationReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private $subscription;
    public function __construct($subscription)
    {
        $this->subscription=$subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        $generator = $this->subscription->powerGenerator;
        $user = $generator->user;
        $notificationService->notifyCustomAdmin([
            'title'=>__('notification.expiration'),
            'body'=> __('notification.expirationBody'),
            'type'=>NotificationTypes::CustomerAdmin,
            'ids'=>[$user->id],
        ]);
    }
}
