<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user is administrator.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function isAdmin(User $user)
    {
        return $user->role_id === 1;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function isFollower(User $user, User $model)
    {
        if($user->role_id === 1){
            return true;
        };
        
        if ($model->id == $user->id) {
            return true;
        };

        return in_array($user->id, array_column($model->followers->toArray(), 'id'));
        

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        /* if($user->role_id === 1){
            return true;
        };*/
        return (int)$user->id == (int)$model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        if($user->role_id === 1){
            return true;
        };

        return (int)$user->id == (int)$model->id;
    }
}
