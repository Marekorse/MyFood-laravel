<?php

namespace Database\Factories;
use App\Models\Recipe;

use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipe_id'=> recipe::all()->random()->id,
            'stars'=> rand(1,5),
        ];
    }
}
