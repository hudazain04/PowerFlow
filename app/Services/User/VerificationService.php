<?php

namespace App\Services\User;

use App\Events\registerEvent;
use App\Exceptions\AuthException;
use App\Exceptions\VerificationException;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\interfaces\User\VerificationRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

class VerificationService
{
    public function __construct(
        protected VerificationRepositoryInterface $userRepository
    ) {}

    public function sendVerificationEmail(User $user)
    {

        if ($this->userRepository->hasVerifiedEmail($user)) {
            throw VerificationException::emailVerified();
        }

        $this->userRepository->sendVerificationNotification($user);
    }

    public function verifyUser(int $userId, string $emailHash)
    {
        $user = $this->userRepository->findById($userId);

        if (!$user || !hash_equals($emailHash, sha1($user->email))) {
            throw VerificationException::invalidLink();
        }

        if ($this->userRepository->hasVerifiedEmail($user)) {
            throw VerificationException::emailVerified();
        }

        $this->userRepository->markEmailAsVerified($user);

        $token=JWTAuth::fromUser($user);

//            event(new Verified($user));
            event(new registerEvent($userId,$user , $token) );
        Log::info("registerEvent fired for user {$userId}");


    }

    public function resendVerificationEmail(User $user)
    {

        if ($this->userRepository->hasVerifiedEmail($user)) {
            throw VerificationException::emailVerified();
        }

        $this->sendVerificationEmail($user);

    }








}
