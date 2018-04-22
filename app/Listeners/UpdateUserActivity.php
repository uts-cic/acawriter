<?php
/**
 * Copyright (c) 2018 original UTS CIC. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * UTS Connected Intelligence Centre
 */

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
