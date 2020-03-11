<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('bypass-captcha', function ($user) {
            return $user->hasAnyRole(['admin', 'staff', 'user']);
        });

        $gate->define('manage-documents', function ($user) {
            return $user->hasAnyRole(['admin', 'staff', 'user']);
        });

        $gate->define('export-pdf', function ($user) {
            return $user->hasAnyRole(['admin', 'staff', 'user']);
        });

        $gate->define('view-examples', function ($user) {
            return $user->hasAnyRole(['admin', 'staff', 'user']);
        });

        $gate->define('manage-assignments', function ($user) {
            return $user->hasAnyRole(['admin', 'staff']);
        });

        $gate->define('administer-users', function ($user) {
            return $user->hasRole('admin');
        });

        $gate->define('access-reports', function ($user) {
            return $user->hasRole('admin');
        });

    }
}
