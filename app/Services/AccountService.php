<?php

namespace App\Services;

use App\ApiHelper\ApiResponse;
use App\DTOs\ProfileDTO;
use App\DTOs\UserDTO;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Repositories\interfaces\UserRepositoryInterface;
use App\Types\UserTypes;
use http\Client\Curl\User;
use Illuminate\Support\Facades\Auth;
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
    public function getProfile()
    {
        $user=Auth::user();
        return $this->success(ProfileResource::make($user),__('messages.success'));
    }

    public function updateProfile(ProfileDTO $profileDTO)
    {
        $user=Auth::user();
        $user=$this->userRepository->update($user,$profileDTO->toArray());
        $profileDTO=ProfileDTO::fromModel($user);
        return $this->success(ProfileResource::make($profileDTO),__('auth.updateProfile'));

    }
    public function blocking($user_id)
    {
        $user=$this->userRepository->findById($user_id);
        $user->blocked=! $user->blocked;
        $user->save();
        return $this->success(['blocked'=>$user->blocked],__('messages.success'));
    }

    public function getAll(array $search)
    {
        $filters=[$search,'roles'=>[UserTypes::USER]];
        $users=$this->userRepository->getAll($filters);
        return $this->successWithPagination(UserResource::collection($users),__('messages.success'));
    }
}
