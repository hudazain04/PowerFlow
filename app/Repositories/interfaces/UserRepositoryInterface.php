<?php

namespace App\Repositories\interfaces;

interface UserRepositoryInterface
{
    public function count() : int;

    public function blockedCount() : int;
}
