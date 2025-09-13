<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Action as ActionModel;
use Illuminate\Support\Collection;

interface ActionRepositoryInterface
{
    public function find($action_id) : ?ActionModel;

    public function create(array $data) : ActionModel;

    public function update(ActionModel $action ,array $data) : ActionModel;

    public function getAll($generator_id) : Collection;
}
