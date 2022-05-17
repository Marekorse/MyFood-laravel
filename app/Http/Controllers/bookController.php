<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\BookShare;
use App\Models\Book;
use App\Models\Privacy;
use Illuminate\Support\Facades\App;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Notifications\notifyUser;
use App\Traits\FileHandler;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class bookController extends Controller
{
    use FileHandler;
    /**
     * show book users.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUsers(Request $request)
    {
        if ($request->page) {
            $book_users= Book::where($request->id)
                ->users()
                ->paginate(10);

            return response([
                'book_users'=>$book_users
            ]);
        }

        $user=auth()
            ->user();

        $book=Book::find($request->id);

        $book_users= $book
            ->users()
            ->paginate(10)
            ->toArray();
        $book->user['owner']=true;
        array_push($book_users['data'], $book->user);

        $shared=$book->users()->where('id', $user->id)->exists();

        $requested=false;

        if (!$shared) {
            $requested_users=User::find($book->user_id)
            ->notifications()
            ->where('type','App\Notifications\BookShare')
            ->pluck('data')
            ->pluck('user')
            ->toArray();

           $requested = in_array($user->id, array_column($requested_users, 'id'));

        }
        return response([
            'shared'=>$shared,
            'requested'=> $requested,
            'book_users'=>$book_users,

            ], 200);
    }
    /**
     * show all shared books.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function showSharedbooks(Request $request)
    {
        $user=auth()->user();
        $user_books=$user->userBooks()->paginate(10);

        return response($user_books,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\recipe  $recept
     * @return \Illuminate\Http\Response
     */
    public function showBook(Request $request, $id=null)
    {

        $user=auth()->user();
        

        if (isset($id)) {
            $book= Book::where('id', $id)
                ->orWhere('name', $id)
                ->orWhere('slug', $id)
                ->firstOrFail();

            $this->authorize('viewAny', $book);

        }
        else $book=$user
            ->book;

        if ($request->ajax()) {

            $categories_name=null;
            $search = $request->search;

            if (isset($request->category)) {
                $categories_name=explode(',',$request->category );
            };

            $recipes=$book
                ->bookRecipes()
                ->with('improvements','image')
                ->when($search, function ($query, $search) {
                    $query->where('title','like', '%'.$search.'%');
                })
                ->when($categories_name, function ($query, $categories_name) {
                    $query->whereHas('categories', function($q) use ($categories_name){
                        $q->whereIn('name', $categories_name);
                        }, '=', count($categories_name)
                    );
                })
                ->withCount('comments', 'likes')
                ->paginate(12);

            return response(['recipes'=> $recipes],200);
        }

        $shared=$book->users()->where('id', $user->id)->exists();    

        $most_liked=$book
            ->bookRecipes()
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();
            

        $recipes_categories=$book
            ->bookRecipes()
            ->whereHas('categories')
            ->with('categories:name')
            ->get()
            ->pluck('categories');

        $filtered_categories=[];
        foreach ($recipes_categories as $key => $categories) {
            foreach ($categories as $key => $category) {
                $filtered_categories[]=$category['name'];
            }
        };
        
        $user_popular_categories= array_slice(array_count_values($filtered_categories), 0, 10, true);
    
        if ($user_popular_categories) {
            arsort($user_popular_categories);
        }

        $privacy_options=Privacy::all();
        
        return view('recipes-book', [
            'card_url'=>url('/receptar/'.$book->name.'/recept'),
            'base_url'=>url('/receptar/'.$book->name),
            'user'=>$user,
            'most_liked'=>json_encode($most_liked),
            'privacy_options'=> $privacy_options,
            'book'=>$book,
            'shared'=>json_encode($shared),
            'user_popular_categories'=> json_encode($user_popular_categories),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRecipe(Request $request)
    {
        $book=Book::find($request->book_id);

        $this->authorize('hasAccess', $book);

        $already_in_book=$book->bookRecipes()->where('id', $request->recipe_id)->first();
        $is_public=Recipe::whereHas('privacy', function (Builder $query) {
            $query->where('id', 3);
        })->exists();

        if (isset($already_in_book)) {
            return response()->json([
                'message'=>'Recipe is already in book  '
            ], 422);
        }
        else if (!$is_public) {
            return response()->json([
                'message'=>'Recipe is not Public  '
            ], 422);
        };

        return $book->bookRecipes()->attach($request->recipe_id);
    }
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request)
    {
        $user=auth()->user();
        $already_shared=$user->book->users->where('id', $request->user_id)->first();

        if (isset($already_shared)) {
            return response()->json([
                'message'=>'Book is already shared with user'
            ], 422);
        };

        $requested_users=$user
            ->notifications()
            ->where('type','App\Notifications\BookShare')
            ->pluck('data')
            ->pluck('user')
            ->toArray();

        $requested = in_array($request->user_id, array_column($requested_users, 'id'));

        if(isset($requested)){

            $user->book->users()->attach($request->user_id);
            $url=url('/receptar/' . $user->name);
            $message='Prijal vašu žiadosť o zdielanie receptára';
            $data=[
                'message'=> $message,
                'icon'=>'avatar',
                'link'=> $url,
                'user'=> $user
            ];
            $to_user= User::find($request->user_id);
            $to_user->notify(new notifyUser($data));

        }else{
            return response()->json([
                'message'=>'The given data was invalid. User dont send request'
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function updateBookName(Request $request, $id)
    {
        
        $book=Book::find($id);

        $this->authorize('updateBook' ,$book);

        $book->update([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name)
        ]);

        return $book->name;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function updateBookPrivacy(Request $request, $id)
    {
        
        $book=Book::find($id);

        $this->authorize('updateBook' ,$book);

        $book->privacy()->sync([$request->privacy_id]);

        return $book->privacy;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyRecipe(Request $request, $book_id,$recipe_id)
    {
        $book=Book::find($book_id);
        $recipe=recipe::find($recipe_id);
        
        $this->authorize('hasAccess' ,$book);

        if (!$recipe->user && $recipe->bookRecipes()->count() <= 1) {
           $recipe->delete();
        }

        $book->bookRecipes()->detach($recipe_id);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyUser(Request $request, $book_id,$user_id)
    {
        $book=Book::find($book_id);

        if ($user_id != auth()->id()) {
            $this->authorize('deleteUser' ,$book, );
        }

        Book::find($book_id)->users()->detach($user_id);
    }
    /**
     * new BookShare request from user.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {
        $to_user=User::find($request->to_user)
        ->load('book');

        $from_user=auth()->user();

        $url=url('/pouzivatel/' . $from_user->name);

        $data=[
            'request'=>true,
            'user'=> $from_user,
            'icon'=>'avatar',
            'link'=> $url,
            'accept_url'=>url('/book/'.$to_user->book->id.'/user/'),
            'message'=>__('general.book_access')
        ];

        $to_user->notify(new BookShare($data));
    }
}
