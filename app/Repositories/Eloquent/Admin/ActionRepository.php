<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Action as ActionModel;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

    public function getAll($generator_id,?array $filters=[]): LengthAwarePaginator
    {
        $actions=ActionModel::filter($filters)->where('generator_id',$generator_id)->paginate(10);
        return  $actions;
   }

    public function getUserActions($user): Collection
    {
        $actions=$user->actions()->where(['type'=> ActionTypes::OverConsume,'status'=>ComplaintStatusTypes::Pending])->get();
        return  $actions;
    }
}
