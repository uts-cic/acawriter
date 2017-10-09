<?php

namespace App\Events;


use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user;
    public $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(User $user, $role)
    {
        $this->user = $user;
        $this->role = $role;
    }


}
