<?php

namespace App\Http\Controllers;

use App\Models\ImproveRecipe;
use App\Models\Book;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Notifications\notifyUser;
use App\Traits\FileHandler;
use Illuminate\Support\Facades\Notification;

class ImprovedRecipeController extends Controller
{
    use FileHandler;
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($book_id, $recipe_id)
    {
        $book=Book::find($book_id);

        $this->authorize('editRecipe' ,$book);

        $recipe=Recipe::find($recipe_id);
        $recipe->load('image');
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

        return view('edit-book-recipe', [
            'action'=> 'newImprovedRecipe',
            'recept'=> $recipe,
            'categories'=>$categories,
            'book'=>$book
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $book_id, $recipe_id)
    {
    
        $user= auth()->user();
        $book=Book::find($book_id);

        $this->authorize('editRecipe' ,$book);

        $request->validate(
            [
                'recipe_id' => 'required | integer',
                'file' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
                'time' => 'required | integer',
                'chunk' => 'required | integer',
                'difficulty' => 'required | string',
                'ingredients' => 'required|not_in:[]',
                'text' => 'required ',
                'approach' => 'required|not_in:[]',
                
            ],
        );

        $data=[
            'recipe_id'=>$request->recipe_id,
            'book_id'=>$request->book_id,
            'time'=>$request->time,
            'chunk'=>$request->chunk,
            'difficulty'=>$request->difficulty,
            'ingredients'=>$request->ingredients,
            'text'=>$request->text,
            'approach'=>$request->approach,
    
        ];

        $recipe=$user->improveRecipe()->create($data);

        if ($request->file()) {
                $storage_path=storage_path('app/private/images/improvedRecipes/'. $book_id);
                
                $file=$this->uploadImage($request->file,$storage_path,800,800);
                $recipe
                    ->image()
                    ->create(
                        ['url'=>'images/improvedRecipes/'.$book_id.'/'. $file
                    ]);
        };
            $notification=[
                'message'=> __('general.user_improved_recipe').$request->title,
                'icon'=>'avatar',
                'link'=> url('/recept' . $recipe->title),
                'user'=> $user
            ];

            $followers= $user->followers;
            Notification::send($followers, new notifyUser($notification));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\improveRecipe  $improveRecipe
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $book_id, $recipe_id)
    {
        $book=Book::where('id',$book_id)
            ->orWhere('name', $book_id)
            ->orWhere('slug', $book_id)
            ->first();

        $user=auth()->user();

        $shared=$book->users()->where('id', $user->id)->exists();

        $recipe = $book
            ->bookRecipes()
            ->where(function($query) use ($recipe_id) {
                $query->where('id', $recipe_id);
                $query->orWhere('slug', $recipe_id);
            })
            ->with('user','categories','image')
            ->when($user, function ($query, $user){
                $query->withExists(['bookRecipes' => function ($query) use($user) {
                    $query->where('book_id', $user->book->id);
                }]);
            })
            ->with(['improvements' => function ($query) use($book) {
                $query->where('book_id', $book->id)
                ->with('image')
                ->with('user');
            }])
            ->firstOrFail();
        
        return view('recipe-book', [
            'user'=>$user,
            'recipe'=> $recipe,
            'book'=> $book,
            'shared'=>json_encode($shared)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\improveRecipe  $improveRecipe
     * @return \Illuminate\Http\Response
     */
    public function edit($book_id, $recipe_id)
    {
        $user_id=auth()->id();

        $book=Book::find($book_id);

        $this->authorize('editRecipe' ,$book);

        $recipe=Recipe::
            where('id',$recipe_id)
            ->with('image','improvement.image')
            ->first();

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


        return view('edit-book-recipe', [
            'action'=> 'editImprovedRecipe',
            'recept'=> $recipe,
            'categories'=>$categories,
            'book'=>$book
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\improveRecipe  $improveRecipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $book_id, $recipe_id)
    {
        $user= auth()->user();
        $book=Book::find($book_id);

        $request->validate(
            [
                'recipe_id' => 'required | integer',
                'file' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
                'time' => 'integer',
                'chunk' => 'integer',
                'difficulty' => 'string',
                'ingredients' => 'String',
                'text' => 'String',
                'approach' => 'String',
            ],
        );

        $data=[
            'recipe_id'=>$request->recipe_id,
            'book_id'=>$request->book_id,
            'time'=>$request->time,
            'chunk'=>$request->chunk,
            'difficulty'=>$request->difficulty,
            'ingredients'=>$request->ingredients,
            'text'=>$request->text,
            'approach'=>$request->approach,
        ];

        $recipe=ImproveRecipe::
            where(
                [
                    ['recipe_id',$data['recipe_id']],
                    ['user_id',$user->id ]
                ]
            )
            ->with('image')
            ->first();
        
        $this->authorize('update' ,$recipe);

        if ($request->file()) {
                $storage_path=storage_path('app/private/images/improvedRecipes/'. $book_id);
                $this->deleteImage($recipe->image->url, 'private', $recipe->image->thumbnail_url);
                $file=$this->uploadImage($request->file,$storage_path,800,800);
                $thumb=$this->uploadImage($request->file,$storage_path,200,200, true);
                $recipe
                    ->image()
                    ->update(
                        ['url'=>'images/improvedRecipes/'.$book_id.'/'. $file,
                        'thumbnail_url'=> 'images/recipes/'.$recipe->id.'/'.$thumb,
                    ]);
        };

        $recipe->update($data);

            $notification=[
                'message'=> __('general.user_improved_recipe').$request->title,
                'icon'=>'avatar',
                'link'=> url('/recept' . $recipe->title),
                'user'=> $user
            ];

            $followers= $user->followers;

            Notification::send($followers, new notifyUser($notification));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\improveRecipe  $improveRecipe
     * @return \Illuminate\Http\Response
     */
    public function destroy($book_id , $recipe_id)
    {
        $recipe=ImproveRecipe::find($recipe_id);

        $this->authorize('delete' ,$recipe);

        $recipe->delete();
    }
}
