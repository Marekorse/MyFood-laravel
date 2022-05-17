<?php
namespace App\Http\Controllers;

use App\Notifications\notifyUser;
use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cookie;

class ratingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ratings=Recipe::find($request->recipe_id)->ratings()->get();

        return response(['ratings'=>$ratings], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cookie_time = 60 * 24 * 365 ;

        $logged_user=auth()->user();

        if ($request->user_id) {
            $request->validate(
                [
                    'recipe_id' => 'required | integer ',
                    'stars' => 'required | integer',
                    'user_id'=> Rule::unique('ratings')
                                ->where(function ($query) use ($request) {
                                    return $query->where('recipe_id', $request->recipe_id);
                                }),
                ],
                [
                    'user_id.unique' =>'recept môžete hodnotiť iba raz',
                ]
            );

            $rating=Rating::create([
                'user_id' => $request->user_id,
                'recipe_id'=> $request->recipe_id,
                'stars'=>  (int)$request->stars,
            ])->load('user');

            $to_user=Recipe::find($request->recipe_id)
                ->user()
                ->first();

            if($to_user && $logged_user->id != $to_user->id){
                $url=url('/recept/' . $request->recipe_id);

                $message=__('general.user_rated_your_recipe');

                $notification=[
                    'message'=> $message,
                    'icon'=>'avatar',
                    'link'=> $url,
                    'user'=> $logged_user
                ];
                
                if ($to_user) {
                    $to_user->notify(new notifyUser($notification));
                }
            }
            return response()->json([
                'rating'=> $rating,
                'message' =>__('general.thanks_for_rate')
            ])->cookie('alreadyRated', $request->recipe_id, $cookie_time);
        } else {
            if ($request->hasCookie('alreadyRated')) {
                $cookie = $request->cookie('alreadyRated');
                if ($cookie === $request->recipe_id) {
                    return response()->json([
                        'message' => __('general.already_rated')
                    ], 500);
                    ;
                }
            }

            $rating=Rating::create([
                'recipe_id'=> $request->recipe_id,
                'stars'=>   (int)$request->stars,
            ]);

            return response()->json([
                'rating'=> $rating,
                'message'=> __('general.thanks_for_rate')
            ])->cookie('alreadyRated', $request->recipe_id, $cookie_time);
        }
    }

    /**
     * Display ratings with users(paginated).
     *
     * @return \Illuminate\Http\Response
     */
    public function UsersRatings(Request $request)
    {
        $user_ratings=Recipe::find($request->recipe_id)->ratings()->with('user')->simplePaginate(12);

        return response($user_ratings, 200);
    }
}
