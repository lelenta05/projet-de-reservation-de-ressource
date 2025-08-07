<?php

namespace App\Policies;

use App\Models\Ressource;
use App\Models\User;
use Illuminate\Auth\Access\Response;

//Permet de restreindre les actions sur la ressource aux admins uniquement.
class RessourcePolicy
{
    //donne tous les droits a l'admin 
    public function before(User $user, $ability)
    {
        //si l'user est admin , il peut tout faire 
        return $user->role && $user->role->nom === 'admin' ? true : null ;
    }



    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //tout le monde peut voir la liste des ressources
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ressource $ressource): bool
    {
        //tout le monde peut voir une ressource 
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;//sera override par before pour admin 
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ressource $ressource): bool
    {
        return false;//sera override par before pour admin 
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ressource $ressource): bool
    {
        return false;//sera override par before pour admin 
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ressource $ressource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ressource $ressource): bool
    {
        return false;
    }
}
