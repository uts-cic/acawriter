<?php

namespace App\Listeners;

use App\Events\UserLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Role;
use App\User;
use App\Activity;


class UpdateUserActivity
{
    /**
     * Create the event listener.
     * stores into userlog - login and logout times
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */


    public function handle(UserLog $event)
    {
        $activity = new Activity();
        $activity->user_id = $event->user->id;
        $activity->name = isset($event->action) ? $event->action: 'logout';
        $activity->save();

        return $activity;

    }
}
