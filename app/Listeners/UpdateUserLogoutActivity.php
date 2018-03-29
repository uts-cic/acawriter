<?php

namespace App\Listeners;

use App\Events\UserLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Role;
use App\User;
use App\Activity;
use Illuminate\Auth\Events\Logout;


class UpdateUserLogoutActivity
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


    public function handle(Logout $event)
    {
        $activity = new Activity();
        $activity->user_id = $event->user->id;
        $activity->name = 'logout';
        $activity->save();

        return $activity;

    }
}
