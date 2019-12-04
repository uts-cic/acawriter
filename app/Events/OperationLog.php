<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

use App\User;

class OperationLog implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $details;
    public $userId;

    public function __construct(User $user, $message = "")
    {
        $status = $message !== "" ? $message : "";
        $this->details = array(
            "status" => $status,
            "info"  => array("user" => $user),
            "time"  => time()
        );
        Log::info('operationLog', ['details' => $this->details]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('operational-log');
    }
}
