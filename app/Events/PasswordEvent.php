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

class PasswordEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $user;
    public $userId;
    public $token;
    public function __construct($token,$user,$userId)
    {
        $this->user=$user;
        $this->userId=$userId;
        $this->token=$token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user'.$this->userId),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'id'=>$this->user->id,
            'first_name'=>$this->user->first_name,
            'last_name'=>$this->user->last_name,
            'email'=>$this->user->email,
            'phone_number'=>$this->user->phone_numbe
        ];

    }
    public function broadcastAs()
    {
        return 'user Password';
    }
}
