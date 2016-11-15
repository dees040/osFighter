<?php

namespace App\Events;

use App\Models\ShoutBox;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ShoutBoxMessageCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var ShoutBox
     */
    public $message;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param ShoutBox $message
     */
    public function __construct(ShoutBox $message)
    {
        $this->message = $message;

        $this->message->user = $message->user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['shout_box'];
    }
}
