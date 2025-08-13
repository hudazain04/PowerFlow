<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratorApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $userId;
    public $generator;

    public function __construct($userId, $generator)
    {
        $this->userId = $userId;
        $this->generator = $generator;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user.'.$this->userId)
        ];
    }
    public function broadcastWith():array {
        return [
            'message' => "Your generator '{$this->generator->name}' has been approved!",
            'generator_name' => $this->generator->name,
            'generator_location'=>$this->generator->location,
            'generator_user_id'=>$this->generator->user_id,
            'timestamp' => now()->toDateTimeString(),

        ];
    }
    public function broadcastAs()
    {
        return 'generator approved';
    }

}
