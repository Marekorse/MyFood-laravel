<?php

namespace App\Policies;

use App\Models\User;
use App\Models\book;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Book $book)
    {
        if($user->role_id === 1){
            return true;
        };
        if ($book->user->id == $user->id) {
            return true;
        }
        else if ($book->privacy()->where('id',3)->exists()) {
            return true;
        };

        return in_array($user->id, array_column($book->user->followers->toArray(), 'id'));
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function hasAccess(User $user, Book $book)
    {
        if($user->role_id === 1){
            return true;
        };
        if ($book->user->id == $user->id) {
            return true;
        }
        return $book->users()->where('id', $user->id)->exists();
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\comment  $comment
     * @return mixed
     */
    public function storeRecipe(User $user, book $book)
    {
        if($user->role_id === 1){
            return true;
        };

        if ($user->id == $book->user_id) {
            return true;
        }
        else return in_array($user->id, array_column($book->users->toArray(), 'id'));
        
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\comment  $comment
     * @return mixed
     */
    public function editRecipe(User $user, book $book)
    {
        if($user->role_id === 1){
            return true;
        };

        if ($user->id == $book->user_id) {
            return true;
        }
        else return in_array($user->id, array_column($book->users->toArray(), 'id'));
        
    }
    public function updateBook(User $user, book $book)
    {
        if($user->role_id === 1){
            return true;
        };

        if ($user->id == $book->user_id) {
            return true;
        }
        
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\comment  $comment
     * @return mixed
     */
    public function deleteRecipe(User $user, book $book)
    {
        if($user->role_id === 1){
            return true;
        };

        if ($user->id == $book->user_id) {
            return true;
        }
        return in_array($user->id, array_column($book->users->toArray(), 'id'));
    }
    
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\comment  $comment
     * @return mixed
     */
    public function deleteUser(User $user, book $book )
    {
        if($user->role_id === 1){
            return true;
        };
        
        if($user->id == $book->user_id){
            return true;
        };
        return $user->id == $book->user_id;
    }
}
