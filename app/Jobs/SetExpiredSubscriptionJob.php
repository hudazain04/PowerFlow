<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SetExpiredSubscriptionJob implements ShouldQueue
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
    public function handle(): void
    {
        $this->subscription->update([
            'expired_at'=>true,
        ]);
    }
}
