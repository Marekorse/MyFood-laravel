<?php
namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Conversation $conversation)
    {
        if($user->role_id === 1){
            return true;
        };
        foreach ($conversation->users as $conversation_user) {
            if ((int)$conversation_user->id == (int)$user->id) {
                return true;
            }
        }
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Conversation $conversation)
    {
        if($user->role_id === 1){
            return true;
        };
        foreach ($conversation->users as $conversation_user) {
            if ((int)$conversation_user['id'] == (int)$user->id) {
                return true;
            }
        }
    }
}
