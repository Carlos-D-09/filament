<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return Auth::check() ? Response::allow() : Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task)
    {
        return Auth::check() ? Response::allow() : Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return Auth::check() ? Response::allow() : Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task)
    {
        if($user->role->role == 'Administrador' && $task->group->user_id == $user->id){
            return Response::allow();
        }

        if (
            $user->role->role == 'Colaborador' && $task->created_by == $user->id &&
            (
                $task->assigned_to == Auth::id() ||
                ($task->assigned_to == null && $task->created_by == Auth::id())
            )
        ){
            return Response::allow();
        }

        return Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task)
    {
        if($user->role->role == 'Administrador' && $task->group->user_id == $user->id){
            return Response::allow();
        }

        if (
            $user->role->role == 'Colaborador' &&
            $task->created_by == $user->id &&
            $task->completed == false &&
            (
                $task->assigned_to == Auth::id() ||
                $task->assigned_to == null
            )
        ){
            return Response::allow();

        }
        return Response::deny('No tienes permiso de crear usuarios');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task)
    {
        //
    }
}
