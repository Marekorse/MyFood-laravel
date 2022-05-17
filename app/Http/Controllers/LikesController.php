<?php
namespace App\Http\Controllers;

use App\Notifications\notifyUser;
use App\Models\Like;
use Illuminate\Validation\Rule;
use App\Models\Recipe;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $likes=Recipe::find($request->recipe_id)->likes()->with('user')->paginate(10);

        $auth_user=auth()->user();
        if ($auth_user) {
           $loged_user_liked=Like::where(
                    [
                        [
                            'user_id', $auth_user->id
                        ],
                        [
                            'recipe_id', $request->recipe_id
                        ],
                    ]
                )
                ->first();
        }
        
        return response(
            [
                'likes'=>$likes,
                'logged_user_liked'=>$loged_user_liked ?? null
            ]
            , 200
        );
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
            'recipe_id'=> Rule::unique('likes')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user()->id);
            })
        ]);

        $user=auth()->user();

        $like=$user->likes()->create(
            $request->only('recipe_id')
        )->load('user');

         $to_user=Recipe::find($request->recipe_id)->user()->first();

         if ($to_user && $user->id != $to_user->id) {

            $url=url('/recept/' . $request->recipe_id);
            $message=__('general.user_liked_your_recipe');
            $data=[
                'message'=> $message,
                'icon'=>'avatar',
                'link'=> $url,
                'user'=> $user
            ];

            $to_user->notify(new notifyUser($data));
        }

        return response($like, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, like $like)
    {
        $like->delete();
        
        return response(['message'=>'Deleted'], 200);
    }
}
