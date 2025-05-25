<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\interfaces\User\VerificationRepositoryInterface;

class VerificationRepository implements VerificationRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function markEmailAsVerified(User $user): bool
    {
        return $user->markEmailAsVerified();

    }

    public function hasVerifiedEmail(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function sendVerificationNotification(User $user): void
    {
        $user->notify(new VerifyEmailNotification());
    }
}
