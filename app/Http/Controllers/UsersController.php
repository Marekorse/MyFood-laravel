<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class UsersController extends Controller
{
    use FileHandler;
    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::where('id', $id)
            ->with('book')
            ->orWhere('name', $id)
            ->withCount('recipes','followers')
            ->firstOrFail();

        $auth_user=auth()->user();
        $owner=false;
        if ($auth_user) {
            $owner = $auth_user->id == $user->id;
        }
           
        if ($request->ajax()) {
            $categories_name=null;
            $search = $request->search;

            if (isset($request->category)) {
                $categories_name=explode(',',$request->category );
            };
            
            $recipes= $user
                ->recipes()
                ->when(!$owner, function ($query) {
                    $query->whereHas('privacy', function (Builder $query) {
                        $query->where('id', 3);
                    });
                })
                ->when($search, function ($query, $search) {
                    $query->where('title','like', '%'.$search.'%');
                })
                ->when($user, function ($query, $user){
                    $query->withExists(['bookRecipes' => function ($query) use($user) {
                        $query->where('user_id', $user->id);
                    }]);
                }) 
                ->when($categories_name, function ($query, $categories_name) {
                    $query->whereHas('categories', function($q) use ($categories_name){
                        $q->whereIn('name', $categories_name);
                        }, '=', count($categories_name)
                    );
                })
                ->where([
                    'confirmation'=>1,
                ])
                ->with('image')
                ->withCount('comments', 'likes')
                ->paginate(12);
            
            return response(['recipes'=>$recipes],200);
        }
        
        $book_access=false;

        if($auth_user && $user->book->privacy()->where('id',3)->exists()){
            $book_access=true;
        }
        else{
            $book_access= $auth_user->id == $user->id || $auth_user->followings()->where('user_id',$user->id)->exists();
        }

        $recipes = $user->recipes()->get();

        foreach ($recipes as $recipe) {
            foreach ($recipe->ratings as $rating) {
                $ratings[]=$rating->stars;
            }
        };
        if (isset($ratings)) {
            $ratings = array_sum($ratings)/count($ratings);
            $ratings = round($ratings, 1) ;
        } else {
            $ratings = 0;
        }

        return view('user', [
            'user'=> $user,
            'book_access'=>$book_access,
            'avg_ratings' => $ratings,
            'auth_id'=> $auth_user->id ?? 0
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @id  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
        $user=User::find(auth()->id());

        return view('settings', [
            'user'=>$user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @id  \Illuminate\Http\Request  $request
     * @id  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'unique:users|max:255',
            'avatar' => 'image|mimes:jpg,jpeg,png,gif,svg',
            'cover' => 'image|mimes:jpg,jpeg,png,gif,svg',
            'email' => 'nullable',
            'status' => 'string | nullable',
        ]);

        if ($request->avatar) {

            $storage_path=storage_path('app/public/images/users/'.$user->id);
            $image=$this->uploadImage($request->avatar,$storage_path,800,800, false);
            $thumb=$this->uploadImage($request->avatar,$storage_path,200,200, true);
            $this->createAvatar($storage_path.'/'.$thumb, 400);
            if ($user->avatar) {
                $this->deleteImage($user->avatar->url,'public',$user->avatar->thumbnail_url);
                $this->deleteImage($user->avatar->url,'public',$user->avatar->url);
            }
        
            $user->avatar()
                ->updateOrCreate(
                    [
                        'imageable_id'=>$user->id,
                        'name'=>'avatar'
                    ],
                    [
                        'url'=>'images/users/' .$user->id .'/'. $image,
                        'thumbnail_url'=> 'images/users/'.$user->id.'/'.$thumb,
                    ]
                );
    
        }
        if ($request->cover) {

            $storage_path=storage_path('app/public/images/users/'.$user->id);
            $image=$this->uploadImage($request->cover,$storage_path,800,800, false);
            $thumb=$this->uploadImage($request->cover,$storage_path,200,200, true);
            if ($user->cover) {
                $this->deleteImage($user->cover->url,'public',$user->cover->thumbnail_url);
                $this->deleteImage($user->cover->url,'public',$user->cover->url);
            }
            $user->cover()
                ->updateOrCreate(
                    [
                        'imageable_id'=>$user->id,
                        'name'=>'cover'
                    ],
                    [
                        'url'=>'images/users/' .$user->id .'/'. $image,
                        'thumbnail_url'=> 'images/users/'.$user->id.'/'.$thumb,
                    ]
                );
        }
        $user->update(
            $request->all()
        );
        return $user->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        if (Hash::check($request->password, auth()->user()->password)) {

            Storage::deleteDirectory("public/images/users/$user->name/");

            $user->delete();

            return response()->json(['message'=> 'User deleted'], 200);
        } else {
            return response()->json(['message'=> 'Incorrect password'], 500);
        }
    }
}
