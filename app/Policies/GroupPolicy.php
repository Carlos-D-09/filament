<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role->role === 'Administrador' ?
            Response::allow() :
            Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group)
    {
        return $user->role->role === 'Administrador' && $group->user_id == $user->id ?
            Response::allow() :
            Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group)
    {
        return $user->role->role === 'Administrador' && $group->user_id == $user->id ?
            Response::allow() :
            Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group)
    {
        //
    }
}
