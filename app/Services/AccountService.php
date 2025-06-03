<?php

namespace App\Services;

use App\ApiHelper\ApiResponse;
use App\Repositories\interfaces\UserRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    )
    {
        //
    }
    public function blocking($user_id)
    {
        $user=$this->userRepository->findById($user_id);
        $user->blocked=! $user->blocked;
        $user->save();
        return $this->success(['blocked'=>$user->blocked],__('messages.success'));
    }
}
