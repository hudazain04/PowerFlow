<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\User\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $user;
    public function __construct(User $user)
    {
        $this->user=$user;
    }

    /**
     * Execute the job.
     */
    public function handle(VerificationService $verificationService): void
    {
        $verificationService->sendVerificationEmail($this->user);
    }
}
