<?php

namespace App\Policies;

use App\Models\ElectricalBox;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ElectricalBoxPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('VIEW_BOXES');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ElectricalBox $electricalBox): bool
    {
        return $user->hasPermission('VIEW_BOXES') &&
            $user->powerGenerator->id === $electricalBox->generator_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('CREATE_BOXES');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ElectricalBox $electricalBox): bool
    {
        return $user->hasPermission('UPDATE_BOXES') &&
            $user->powerGenerator->id === $electricalBox->generator_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ElectricalBox $electricalBox): bool
    {
        return $user->hasPermission('DELETE_BOXES') &&
            $user->powerGenerator->id === $electricalBox->generator_id;
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ElectricalBox $electricalBox): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ElectricalBox $electricalBox): bool
    {
        return false;
    }
}
