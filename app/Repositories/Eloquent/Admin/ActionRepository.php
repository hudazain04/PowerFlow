<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Action as ActionModel;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;

class ActionRepository implements ActionRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function find($action_id): ?ActionModel
    {
        $action=ActionModel::find($action_id);
        return $action;
    }

    public function create(array $data): ActionModel
    {
       $action=ActionModel::create($data);
       return  $action;
    }

    public function update(ActionModel $action, array $data): ActionModel
    {
       $action->update($data);
       $action->save();
       return  $action;
    }
}
