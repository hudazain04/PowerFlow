<?php

namespace App\Events;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class registerEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $user;
    public $userId;
    public function __construct($userId,User $user , protected  $token)
    {
        $this->userId = $userId;
        $this->user=$user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user.'.$this->userId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            "user" => [
                'id'=>$this->user->id,
                'first_name'=>$this->user->first_name,
                'last_name'=>$this->user->last_name,
                'email'=>$this->user->email,
                'phone_number'=>$this->user->phone_number
            ],
            "token" => $this->token
        ];
    }
    public function broadcastAs()
    {
        return 'user.verified';
    }
}
