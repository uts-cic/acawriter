<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role');
    }

    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function hasAnyRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        if ($this->roles()->whereIn('name', $roles)->count()) {
            return true;
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->count()) {
            return true;
        }
        return false;
    }


    public function getRole()
    {
        $sequence = array('admin', 'staff', 'user', 'demo');
        foreach ($sequence as $role) {
            if ($this->hasRole($role)) {
                return $role;
            }
        }
        return false;
    }
}
