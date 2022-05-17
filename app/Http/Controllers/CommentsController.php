<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use App\Notifications\notifyUser;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments = Recipe::find($request->recipe_id)->comments()->with('user')->latest()->paginate(10);
        return response($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required| string',
            'recipe_id' => 'required | integer',
            'recipe_user_id' => 'integer'
        ]);

        $user= auth()->user();

        $touser=User::find($request->recipe_user_id);
        
        $comment = $user->comments()->create(
            $request->only('text', 'recipe_id')
        );
        if ($request->recipe_user_id && $request->recipe_user_id !== $user->id ) {

            $message = __('general.user_commented_your_recipe');

            $url= url('/recept'.$request->recipe_id);

            $data=[
                'message'=>$message,
                'icon'=>'avatar',
                'user'=> $user,
                'link'=>   $url,
            ];
            
            $touser->notify(new notifyUser($data));    
        }
        
        return $comment->load('user');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update([
            'text'=>$request->text,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();
    }
}
