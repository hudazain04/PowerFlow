<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratorApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $userId;
    public $generatorName;

    public function __construct($userId, $generatorName)
    {
        $this->userId = $userId;
        $this->generatorName = $generatorName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user.' . $this->userId)
        ];
    }
    public function broadcastWith():array {
        return [
            'message' => "Your generator '{$this->generatorName}' has been approved!",
            'generator_name' => $this->generatorName,
            'timestamp' => now()->toDateTimeString(),

        ];
    }
}
