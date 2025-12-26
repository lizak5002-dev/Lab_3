<?php

namespace App\Providers;

use App\Models\Castle;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;


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
        Gate::define('modify-object', function (User $user, Castle $castle) {
        return $user->id == $castle->user_id || $user->is_admin;
        });
    }
}
