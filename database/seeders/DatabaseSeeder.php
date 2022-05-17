<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Privacy;
use App\Models\Role;
use App\Traits\FileHandler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use FileHandler;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Role::create(['role' => 'administrator']);
        Role::create(['role' => 'user']);

        Privacy::create(['privacy' => 'private']);
        Privacy::create(['privacy' => 'only_followers']);
        Privacy::create(['privacy' => 'public']);

        User::factory()
            ->count(50)
            ->has(
                Recipe::factory()
                ->count(rand(5,10))
            )
            ->has(
                Comment::factory()
                ->count(rand(20,26))
            )
            ->create();

            $categories=[
                'Maso a Ryby',
                'Polievky',
                'Cestoviny',
                'Dezerty',
                'Koláče',
                'Večera',
                'Bielkovinové',
                'Vegánske',
                'Vajcia',
                'Bezlepkové',
                'Zdravá strava',
                'šaláty',
                'snack',
            ];

            foreach ($categories as $key => $category) {
                $new_category= new Category;
                $new_category->name = $category;
                $new_category->position = $key;
                $new_category->lang = 'sk';
                $new_category->save();
            };
   
            $recipes=Recipe::all();

            $recipes_ids=$recipes
                ->pluck('id')
                ->toArray();
            
            $images = Storage::disk('public')
                ->allFiles('images/faker/full/');

            $recipes->each(function ($recipe) use($images){
            $recipe->privacy()->sync([3]);
            $randomFile = $images[array_rand($images)];
            $thumb=basename($randomFile,'.jpg').'-thumb.jpg';
            $recipe
                ->image()
                ->create(
                    [
                        'url'=> $randomFile,
                        'thumbnail_url'=> 'images/faker/thumb/'.$thumb,
                    ]
                );
                $category_ids=Category::all()
                    ->pluck('id')
                    ->toArray();

                shuffle($category_ids);
                $ids=array_slice($category_ids, 0, rand(1,count($category_ids)), true);
                foreach($ids as $id)

                $recipe->categories()
                    ->attach($id);
                
            });

            $recipes_ids=recipe::all()
                ->pluck('id')
                ->toArray();

            User::all()->each(function ($user) use($recipes_ids){
                $array_of_random_ids=array_rand($recipes_ids, rand(1,count($recipes_ids)));
                
                $user->book()
                    ->create(
                        [
                            'name'=>__('general.recipes_book_default').' '.$user->name,
                            'slug'=> Str::slug(__('general.recipes_book_default').' '.$user->name),
                        ]
                    ); 
                $user->book->privacy()->sync([3]);
                
                foreach($array_of_random_ids as $key => $recipe_id){
                    if ($recipe_id === 0) {
                        continue;
                    };
                    $user->likes()->create(['recipe_id'=>$recipe_id]);
                };
                foreach($array_of_random_ids as $key => $recipe_id){
                    if ($recipe_id === 0) {
                        continue;
                    };
                    $user->ratings()
                        ->create([
                            'recipe_id'=> $recipe_id,
                            'stars'=> rand(1,5)
                        ]);
                };
                $users=User::all();
                for ($i=rand(1,count($users)); $i < 20; $i++){
                    $random_id= $users
                        ->random()->id;

                        if($user->id == $random_id ){
                            continue;
                        };
                    $user
                        ->followers()
                        ->attach($random_id);
                }
            });
            
    }
}
