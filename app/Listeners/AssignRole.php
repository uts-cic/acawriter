<?php

namespace App\Listeners;

use App\Events\USerRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Role;
use App\User;


class AssignRole
{
    /**
     * Create the event listener.
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
    public function handle(UserRegistered $event)
    {

        $user = $event->user;
        $role = Role::where('name',$event->role)->first();
        $user->roles()->attach($role);
        return $user;

    }
}
