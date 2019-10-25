<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;
use App\User;


class UserActivity implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $data;
    public $userId;

    public function __construct(User $user, $data)
    {
        $sockData = new \stdClass;
        $sockData->type = $data->type ? $data->type : 'General';
        //if draft is saved $data-ref has the new draft obj & $data->ref->id is the new draft id
        //if this is true then send the job ref back to Vue to verify action
        $sockData->ref = $data->ref ? $data->jobRef : '';
        $sockData->msg = isset($data->msg) ? $data->msg : '';

        Log::info('activity', ['draft' => $sockData]);

        $this->data = $sockData;

        $this->userId = $user->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user-activity');
    }
}
