<?php
namespace App\Policies;

use App\Models\User;
use App\Models\comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentsPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\comment  $comment
     * @return mixed
     */
    public function update(User $user, comment $comment)
    {
        if($user->role_id === 1){
            return true;
        };
        return (int)$user->id === (int)$comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\comment  $comment
     * @return mixed
     */
    public function delete(User $user, comment $comment)
    {
        if($user->role_id === 1){
            return true;
        };
        return (int)$user->id === (int)$comment->user_id;
    }

}
