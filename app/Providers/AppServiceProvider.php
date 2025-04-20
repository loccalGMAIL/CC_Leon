<?php

namespace App\Providers;

use App\Models\User;
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
        //gates para los roles
        Gate::define('ver-admin', function (User $user) {
            return $user->rol == 'admin';
        });
        Gate::define('ver-todos', function ($user) {
            return in_array($user->rol, ['admin', 'usuario']);
        });
    }
}
