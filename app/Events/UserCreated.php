<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserCreated
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
