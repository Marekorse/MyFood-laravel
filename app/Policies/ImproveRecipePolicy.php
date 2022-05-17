<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ImproveRecipe;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImproveRecipePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ImproveRecipe $improve_recipe)
    {
        if($user->role_id === 1){
            return true;
        };
        
        return $user->id == $improve_recipe->user_id;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ImproveRecipe $improve_recipe)
    {
        if($user->role_id === 1){
            return true;
        };

        return $user->id == $improve_recipe->user_id;
    }
}