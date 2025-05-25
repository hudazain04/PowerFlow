<?php

namespace App\Repositories\Eloquent;

use App\Models\User as UserModel;
use App\Repositories\interfaces\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function count(): int
    {
        return UserModel::count();
    }


    public function blockedCount(): int
    {
        return UserModel::where('blocked',true)->count();
    }
}
