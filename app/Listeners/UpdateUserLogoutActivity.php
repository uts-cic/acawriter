<?php

namespace App\Listeners;

use App\Activity;
use Illuminate\Auth\Events\Logout;


class UpdateUserLogoutActivity
{
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
