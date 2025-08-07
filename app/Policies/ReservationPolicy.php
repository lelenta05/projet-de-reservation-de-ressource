<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{

    public function before(User $user, $ability)
    {
        //admin peut tout faire 
        if($user->role && $user->role->nom === 'admin'){
            return true ;
        }
        return null ;//on continue les methodes suivants 
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;//tout le monde peut voir ses reservations
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        //un utilisateur peut voir uniquement ses reservations 
        return $reservation->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //un utilisateur peut faire une reservation 
        return $user->role && $user->role->nom === 'user';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        //un utilisateur peut modifier uniquement ses reservations 
        return $reservation->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        //un utilisateur peut uniquement supprime ses reservations 
        return $reservation->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return false;
    }
}
