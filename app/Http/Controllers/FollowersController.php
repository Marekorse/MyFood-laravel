<?php
namespace App\Http\Controllers;

use App\Notifications\notifyUser;
use App\Models\User;
use App\Notifications\followerRequest;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->page_no) {
            $followers= User::find($request->id)
                ->followers()
                ->paginate(10);

            return response([
                'followers'=>$followers
            ]);
        }
    
        $user=auth()
            ->user();

        $followers= User::find($request->id)
            ->followers()
            ->paginate(10);

        $requested=false;
        $followed=false;
        if ($user) {
            $followed=User::find($request->id)
                ->followers()
                ->where('id', $user->id)
                ->exists();

            if (!$followed) {
                $requested_users=User::find($request->id)
                ->notifications()
                ->where('type','App\Notifications\followerRequest')
                ->pluck('data')
                ->pluck('user')
                ->toArray();
            
            $requested = in_array($user->id, array_column($requested_users, 'id'));
        }
        
        }
        return response([
            'followed'=>$followed,
            'requested'=> $requested,
            'followers'=>$followers,
            
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=auth()->user();

        $already_follow=$user->followers()->where('id', $request->user_id)->first();

        if (isset($already_follow)) {
            return response()->json([
                'message'=>'The given data was invalid. User already follows'
            ], 422);
        };

        $requested_users=$user
            ->notifications()
            ->where('type','App\Notifications\followerRequest')
            ->pluck('data')
            ->pluck('user')
            ->toArray();
           
        $requested = in_array($request->user_id, array_column($requested_users, 'id'));

         if (isset($requested)) {
             $user->followers()->attach($request->user_id);

            $new_follower=User::find($request->user_id);

            $url=url('/pouzivatel/' . $user->name);
            $message=__('general.user_follower_request_accepted');

            $data=[
                'message'=> $message,
                'icon'=>'avatar',
                'link'=> $url,
                'user'=> $user
            ];

            $new_follower->notify(new notifyUser($data));

            return response($new_follower, 200);
         }
         else{
            return response()->json([
                'message'=>'The given data was invalid. User dont send request'
            ], 422);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user=auth()
            ->user();

        $user->followings()
            ->detach($id);

        $has_book_access=$user
            ->book
            ->users()
            ->where('id', 56)
            ->exists();
        
        if ($has_book_access) {
            $user
            ->book
            ->users()
            ->detach($id);
        }

        return response()->json(['message'=>'Deleted'], 200);
    }

    /**
     * Send follower request for user.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {
        $to_user=User::find($request->to_user);

        $from_user=auth()->user();

        $url=url('/pouzivatel/' . $from_user->name);

        $data=[
            'request'=>true,
            'user'=> $from_user,
            'icon'=>'avatar',
            'link'=> $url,
            'accept_url'=>url('/followers'),
            'message'=>__('general.follower_request')
        ];

        $to_user->notify(new followerRequest($data));


    }


}
