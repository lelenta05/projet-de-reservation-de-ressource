<?php

namespace App\Providers;

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
        //create des gate pour la gestion des acces en fonction du role 
        //gate pour l'admin 
        Gate::define('is-admin', function($user){
            return $user->role && $user->role->nom === 'admin';
        });

        //gate pour l'user 
        Gate::define('is-user', function ($user){
            return $user->role && $user->role->nom === 'user';
        });
    }
}
