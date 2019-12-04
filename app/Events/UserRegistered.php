<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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
