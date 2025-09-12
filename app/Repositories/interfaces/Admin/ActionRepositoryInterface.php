<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Action as ActionModel;

interface ActionRepositoryInterface
{
    public function find($action_id) : ?ActionModel;

    public function create(array $data) : ActionModel;

    public function update(ActionModel $action ,array $data) : ActionModel;
}
