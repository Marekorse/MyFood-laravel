<?php
namespace App\Http\Controllers;


use App\Models\Recipe;
use App\Models\Category;
use App\Models\Privacy;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Notifications\notifyUser;
use App\Traits\FileHandler;
use Illuminate\Support\Facades\Notification;
use function GuzzleHttp\json_decode;
use Illuminate\Database\Eloquent\Builder;

class RecipesController extends Controller
{
    use FileHandler;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user=auth()->user();

        if ($user) {
           $user->load('book');
        };
        
        if ($request->ajax()) {
            
           $categories_name=null;
           $search = $request->search;

            if (isset($request->category)) {
                $categories_name=explode(',',$request->category );
            };
            
            $recipes= Recipe::
                whereHas('privacy', function (Builder $query) {
                    $query->where('id', 3);
                })
                ->where([
                    'confirmation'=>1,
                ])
                ->when($search, function ($query, $search) {
                    $query->where('title','like', '%'.$search.'%');
                })
                ->when($user, function ($query, $user){
                    $query->withExists(['bookRecipes' => function ($query) use($user) {
                        $query->where('book_id', $user->book->id);
                    }]);
                }) 
                ->when($categories_name, function ($query, $categories_name) {
                    $query->whereHas('categories', function($q) use ($categories_name){
                        $q->whereIn('name', $categories_name);
                        }, '=', count($categories_name)
                    );
                })
                ->with('user','image')
                ->withCount('comments', 'likes')
                ->paginate(16);
            
            return response(['recipes'=>$recipes],200);
        }
        else{
            
            $most_liked=Recipe::has('likes')->withCount('likes')->orderBy('likes_count', 'desc')->take(5)->get();
            $most_popular_user=User::has('followers')->withCount('followers')->orderBy('followers_count', 'desc')->take(5)->get();
            $most_commented=Recipe::has('comments')->withCount('comments')->orderBy('comments_count', 'desc')->take(5)->get();

            $recipes_categories=recipe::whereHas('categories')->with('categories:name')->get()->pluck('categories');
            $filtered_categories=[];
    
            foreach ($recipes_categories as $key => $categories) {
                foreach ($categories as $key => $category) {
                    $filtered_categories[]=$category['name'];
                }
            };
            
            $most_popular_categories= array_slice(array_count_values($filtered_categories), 0, 10, true);
            arsort($most_popular_categories);

            return view('recipes',[
                'card_url'=>url('/recept'),
                'base_url'=>url('/recepty'),
                'user'=>$user,
                'most_liked'=> $most_liked,
                'most_popular_user'=> $most_popular_user,   
                'most_commented' =>  $most_commented,
                'most_popular_categories'=>json_encode($most_popular_categories)
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth= auth()->check();

        return view('add-recipe', [
            'auth'=>$auth
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|unique:recipes|max:255',
                'time' => 'required | integer',
                'chunk' => 'required | integer',
                'difficulty' => 'required | string',
                'ingredients' => 'required|not_in:[]',
                'text' => 'required ',
                'approach' => 'required|not_in:[]',
                'guest' => 'string | nullable',
                'categories' => 'required | string',
                'categories' => 'required | string',
                'privacy' => 'string',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
        );

        $filtered_data=[
            'title'=>$request->title,
            'time'=>$request->time,
            'chunk'=>$request->chunk,
            'difficulty'=>$request->difficulty,
            'ingredients'=>$request->ingredients,
            'text'=>$request->text,
            'approach'=>$request->approach,
            'slug' => Str::slug($request->title),
            'link' => url('/recept/' . $request['slug']),
            'guest'=> $request->guest ?? null
        ];

        $user=auth()->user();

        if ($user) {
            $filtered_data['confirmation'] = 1 ;
            $recipe=$user->recipes()->create($filtered_data);
            $user->book->bookRecipes()->attach($recipe->id);
            $recipe->privacy()->sync([$request->privacy]);
            
            $notification=[
                'message'=> __('general.add_new_recipe'),
                'icon'=>'avatar',
                'link'=> url('/recept' . $recipe->title),
                'user'=> $user
            ];

            $followers= $user->followers;
            Notification::send($followers, new notifyUser($notification));
        } else {
            $recipe=Recipe::create($filtered_data);
            $recipe->privacy()->sync([3]);
        };

        $storage_path=storage_path('app/public/images/recipes/'.$recipe->id);
        $image=$this->uploadImage($request->file,$storage_path,1920,1080, false);
        $thumb=$this->uploadImage($request->file,$storage_path,1280,720, true); 
        $recipe
            ->image()
            ->create(
                [
                    'url'=>'images/recipes/' .$recipe->id .'/'. $image,
                    'thumbnail_url'=> 'images/recipes/'.$recipe->id.'/'.$thumb,
                ]
            );

        if($request->categories){
            foreach(json_decode($request->categories) as $category){
                $recipe->categories()->attach($category->id);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\recipe  $recept
     * @return \Illuminate\Http\Response
     */
    public function show($param, Request $request)
    {
        $user=auth()->user();

        $recipe = Recipe::where('id', $param)
            ->orWhere('slug', $param)
            ->with('user','categories','image')
            ->when($user, function ($query, $user){
                $query->withExists(['bookRecipes' => function ($query) use($user) {
                    $query->where('book_id', $user->book->id);
                }]);
            }) 
            ->firstOrFail();

        return view('recipe', [
            'user'=>$user,
            'recipe'=> $recipe,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $recipe=Recipe::with('image','privacy')->find($id);
       
        $this->authorize('update', $recipe);

        $categories=Category::all();
       
        foreach ($categories as  $category) {
            $id=$category->id;
            $category['selected']=false;
            foreach ($recipe->categories()->get() as  $selected_category) {
                if($id == $selected_category->id){
                    $category['selected']=true;
                }
            }
        }
        $recipe['ingredients']=json_decode($recipe->ingredients);

        $recipe['approach']=json_decode($recipe->approach);
        
        return view('edit-recipe', [
            'recept'=> $recipe,
            'categories'=>$categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\recipe  $recept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $recipe=Recipe::find($id);

        $this->authorize('update', $recipe);

        $request->validate(
            [
                'categories'=>' string',
                'title' => "required|unique:recipes,title,{$recipe->id}",
                'file' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
                'time' => 'required | integer',
                'chunk' => 'required | integer',
                'difficulty' => 'required | string',
                'ingredients' => 'required | string',
                'text' => 'required',
                'approach' => 'required | string ',
            ]
        );

        if ($request->file('file')) {

            $storage_path=storage_path('app/public/images/recipes/'.$recipe->id);
            $image=$this->uploadImage($request->file,$storage_path,500,320, false);
            $thumb=$this->uploadImage($request->file,$storage_path,150,150, true);
            $this->deleteImage($recipe->image->url,'public',$recipe->image->thumbnail_url);
            $this->deleteImage($recipe->image->url,'public',$recipe->image->url);

            $recipe
                ->image()
                ->update(
                    [
                        'url'=>'images/recipes/' .$recipe->id .'/'. $image,
                        'thumbnail_url'=> 'images/recipes/'.$recipe->id.'/'.$thumb,
                    ]
                );   
        }

        $filtered_data=[
            'title'=>$request->title,
            'time'=>$request->time,
            'chunk'=>$request->chunk,
            'difficulty'=>$request->difficulty,
            'ingredients'=> $request->ingredients,
            'text'=>$request->text,
            'approach'=> $request->approach,
            'slug' => Str::slug($request->title)
        ];

        $recipe->update(
            $filtered_data
        );
        $categories_ids = [];
        if($request->categories){
            foreach(json_decode($request->categories) as $category){
                array_push($categories_ids,$category->id );
            };
            
           $recipe->categories()->sync($categories_ids);
        }
        if($request->privacy){
            $recipe->privacy()->sync([$request->privacy]);
        }

        return $recipe;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\recipe  $recept
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $recipe=Recipe::find($id);
        $user=auth()->user();
        $this->authorize('delete', $recipe);

        if ($recipe->improvements()->exists()) {
            $recipe->user()->dissociate($recipe);
            $recipe->privacy='private';
            $user->book->bookRecipes()->detach($recipe->id);
            $recipe->save();

            return;
        }

        if ($recipe->img) {
            $image=$recipe->img;
            $path=explode('/', $image, );
            Storage::delete('public/images/recipes/' . $path[4]);
        }
        $recipe->delete();
    }

    /**
     * Update recipe confirmation .
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\recipe  $recept
     * @return \Illuminate\Http\Response
     */
    public function confirm_recipe(Request $request, $id)
    {
        $recipe=Recipe::find($id);

        $this->authorize('update', $recipe);

        $request->validate([
            'confirmation' => 'required | integer | max:1',
        ]);
        $recipe->update([
            'confirmation'=> $request->confirmation
        ]);
        return $recipe;
    }
}
