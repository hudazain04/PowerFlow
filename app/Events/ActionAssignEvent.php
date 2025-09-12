<?php

namespace App\Events;

use App\Http\Resources\CounterResource;
use App\Types\ActionTypes;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActionAssignEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $employee_id;
    public $action;
    public function __construct($action,$employee_id)
    {
        $this->action=$action;
        $this->employee_id=$employee_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("{$this->employee_id}.action"),
        ];
    }
    public function broadcastWith(): array
    {
        $data=[
            'id'=>$this->action->id,
            'type'=>$this->action->type,
            'counter'=>CounterResource::make($this->action->counter),
            'status'=>$this->action->status,
            'employee_id'=>$this->action->employee_id,
            'priority'=>$this->action->priority,
            'parent_id'=>$this->action->parent_id,
            'created_at'  => $this->action->created_at->format('Y-m-d h:s a'),
        ];
        if ($this->action->type===ActionTypes::Payment)
        {
            array_merge($data,['payment'=>$this->action->relatedData['payment']]);
        }
        elseif ($this->action->type===ActionTypes::Cut)
        {
            array_merge($data,['latestSpending'=>$this->action->relatedData['latestSpending'],
                'latestPayment'=>$this->action->relatedData['latestPayment']]);
        }
        return $data;
    }

    public function broadcastAs()
    {
        return 'action.assigned';
    }
}
