<?php

namespace App\Listeners;

use App\Events\UserLog;
use App\Activity;

class UpdateUserActivity
{
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
        $activity->name = isset($event->action) ? $event->action : 'logout';
        $activity->save();

        return $activity;
    }
}
