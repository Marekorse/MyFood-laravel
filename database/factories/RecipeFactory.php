<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        $slug = Str::slug($title, '-');

        $ingredients=[];
        $approachs=[];

        for ($i=1; $i < rand(3,6) ; $i++) { 
            $approach= [
                'id' => $i,
                'name' =>  $this->faker->text($maxNbChars = 200),
            ];
            array_push($approachs, $approach);
        }

        for ($i=1; $i < rand(4,8) ; $i++) { 
            $ingredient= [
                'id' => $i,
                'name' =>  $this->faker->word()
            ];
            array_push($ingredients, $ingredient);
        }

        return [
            'title' => $title,
            'time' => $this->faker->numberBetween($min = 30, $max = 160),
            'difficulty' => $this->faker->randomElement(['easy', 'medium','hard']),
            'chunk' => $this->faker->numberBetween($min = 1, $max = 8),
            'ingredients' => json_encode($ingredients),
            'text' => $this->faker->text($maxNbChars = 250),
            'approach' => json_encode($approachs),
            'slug' => $slug,
            'confirmation' => 1,
        ];
    }
}
