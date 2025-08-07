<?php

namespace App\Providers;

use App\Models\Reservation;
use App\Models\Ressource;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
// Importe tes modèles et politiques ici si tu les utilises
use App\Models\User;
use App\Policies\ReservationPolicy;
use App\Policies\RessourcePolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Le tableau de politiques (policies) de l'application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Exemple de liaison d’un modèle avec une policy
        Ressource::class => RessourcePolicy::class,
        Reservation::class => ReservationPolicy::class,
    ];

    /**
     * Enregistre tous les services d’authentification / autorisation.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Exemple : définition d'une Gate personnalisée
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
