<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-only', function (User $user) {
            return $user->role_id === 1
                ? Response::allow()
                : Response::deny();
        });

        Gate::define('student-only', function (User $user) {
            return $user->role_id === 4
                ? Response::allow()
                : Response::deny();
        });
    }
}
