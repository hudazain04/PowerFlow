<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\PowerGenerator as PowerGeneratorModel;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;

class PowerGeneratorRepository implements PowerGeneratorRepositoryInterface
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
        return PowerGeneratorModel::count();
    }
}
