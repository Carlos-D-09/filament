<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user){
        return $user->role->role === 'Administrador' ? Response::allow() : Response::deny('No tienes permiso de crear usuarios');
    }

    public function update(User $user, User $userToUpdated){
        return $user->role->role === 'Administrador' && $userToUpdated->role->role !== 'Administrador' ? Response::allow() : Response::deny('No tienes permiso de editar usuarios');
    }

    public function delete(User $user){
        return $user->role->role === 'Administrador' ? Response::allow() : Response::deny('No tienes permiso de eliminar usuarios');
    }
}
