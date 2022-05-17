<?php

namespace Database\Factories;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $emojis=[
            '😀','😃','😄','😁','😆','😅','🤣','😂','🙂',
            '🙃','😉','😊','😇','🥰','😍','🤩','😘','😗',
            '🥲','😋','😛','😜','🤪','😝','🤑','🤗','🤭',
            '🤫','😚','😙','🤔','🤐','🤨','😐','😑','😶',
            '🙄','😬','😮‍💨','🤥','😌','😔','😪','🤤','😴',
            '😷','🤒','🤕','🤢','🤮','🤧','🥵','🥶','🥴',
            '🥳','🥸','😎','🤓','🧐','😕','😟','🙁','☹️',
            '😲','😳','🥺','😦','😧','😨','😰','😥','😢',
            '😭','😱','😖','😣','😞','😓','😩','😫','🥱',
            '😤','😡','😠','🤬','😈','👿','💀','☠️','💩'
        ];
        $text=$this->faker->text($maxNbChars = 250);
        
        $iterator=rand(0,3);

        for ($i = $iterator; $i < 3; $i++) {
            $random_index=array_rand( $emojis, 1);
            $text.=$emojis[$random_index];
        };

        return [
            'recipe_id'=>recipe::all()->random()->id,
            'text' => $text
        ];
    }
}
