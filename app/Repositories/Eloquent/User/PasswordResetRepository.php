<?php

namespace App\Repositories\Eloquent\User;

use App\DTOs\UserDTO;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Repositories\interfaces\User\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    protected $model;
   public function __construct(User $model){
    $this->model=$model;
  }
    public function findEmail(string $email) : ?User
    {
      return $this->model->where('email',$email)->first();
    }

    public function UpdatePassword(User $user,string $password) : bool
    {
        return $user->forceFill([
            'password'=>Hash::make($password)
        ])->save();

    }




}
