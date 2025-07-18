<?php

namespace App\Repositories\interfaces\User;

use App\DTOs\UserDTO;
use App\Models\User;

interface VerificationRepositoryInterface
{
    public function findById(int $id): ?User;
    public function markEmailAsVerified(User $user): bool;
    public function hasVerifiedEmail(User $user): bool;
    public function sendVerificationNotification(User $user): void;
}
