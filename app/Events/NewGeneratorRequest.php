<?php

namespace App\Events;

use App\Models\GeneratorRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewGeneratorRequest implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $generator;
    public function __construct(GeneratorRequest $generator)
    {
      $this->generator=$generator;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.dashboard'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'=>$this->generator->id,
            'generator_name'=>$this->generator->generator_name,

               ];
    }
    public function broadcastAs()
    {
        return 'generator.request.created';
    }

}
