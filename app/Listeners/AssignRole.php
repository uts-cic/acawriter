<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Role;


class AssignRole
{
    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {

        $user = $event->user;
        $role = Role::where('name', $event->role)->first();
        $user->roles()->attach($role);
        return $user;
    }
}
