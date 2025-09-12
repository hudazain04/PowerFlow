<?php

namespace App\Events;

use App\Http\Resources\CounterResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintAssignEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public  $complaint;
    public  $employee_id;

    public function __construct($complaint, $employee_id)
    {
        $this->employee_id=$employee_id;
        $this->complaint=$complaint;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("{$this->employee_id}.complaint"),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'complaint' => [
                'id' => $this->complaint->id,
                'status' => $this->complaint->status,
                'description' => $this->complaint->description,
                'type'=>$this->complaint->type,
                'counter' => CounterResource::make($this->complaint->counter),
                'created_at' => $this->complaint->created_at->format('Y-m-d h:s a'),
            ]
        ];
    }

    public function broadcastAs()
    {
        return 'cut.complaint';
    }

}
