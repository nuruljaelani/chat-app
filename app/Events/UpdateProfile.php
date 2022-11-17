<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateProfile implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId, $name, $avatar;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $name, $avatar)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->avatar = $avatar;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('update.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->userId,
            'name' => $this->name,
            'avatar' => $this->avatar
        ];
    }
}
