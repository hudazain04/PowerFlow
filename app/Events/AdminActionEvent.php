<?php

namespace App\Events;

use App\Http\Resources\SpendingPaymentRersource;
use App\Types\ActionTypes;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminActionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $generator_id;
    public $action;
    public function __construct($generator_id,$action)
    {
        $this->generator_id=$generator_id;
        $this->action=$action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("{$this->generator_id}.action"),
        ];
    }
    public function broadcastWith() : array
    {
        $data= [
            'id'=>$this->action->id,
            'status'=>$this->action->status,
            'priority'=>$this->action->priority,
            'counter_id'=>$this->action->counter_id,
            'type'=>$this->action->type,

        ];
        if ($this->action->type === ActionTypes::Payment)
        {
            array_merge($data,['payment'=>$this->action->relatedData['payment']]);
        }
        if ($this->action->type=== ActionTypes::Cut)
        {
            array_merge($data,['latestSpending'=>$this->action->relatedData['latestSpending'],
                'latestPayment'=>$this->action->relatedData['latestPayment']]);
        }
        return $data;
    }
    public function broadcastAs()
    {
        return 'new.action';
    }
}
