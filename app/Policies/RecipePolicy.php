<?php
namespace App\Policies;

use App\Models\User;
use App\Models\recipe;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recipe  $recipe
     * @return mixed
     */
    public function update(User $user, recipe $recipe)
    {
        if($user->role_id === 1){
            return true;
        };

        return (int)$user->id == (int)$recipe->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recipe  $recipe
     * @return mixed
     */
    public function delete(User $user, recipe $recipe)
    {
        if($user->role_id === 1){
            return true;
        };

        return (int)$user->id === (int)$recipe->user_id;
    }

}
