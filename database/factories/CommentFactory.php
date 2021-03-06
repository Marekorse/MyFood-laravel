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
            '๐','๐','๐','๐','๐','๐','๐คฃ','๐','๐',
            '๐','๐','๐','๐','๐ฅฐ','๐','๐คฉ','๐','๐',
            '๐ฅฒ','๐','๐','๐','๐คช','๐','๐ค','๐ค','๐คญ',
            '๐คซ','๐','๐','๐ค','๐ค','๐คจ','๐','๐','๐ถ',
            '๐','๐ฌ','๐ฎโ๐จ','๐คฅ','๐','๐','๐ช','๐คค','๐ด',
            '๐ท','๐ค','๐ค','๐คข','๐คฎ','๐คง','๐ฅต','๐ฅถ','๐ฅด',
            '๐ฅณ','๐ฅธ','๐','๐ค','๐ง','๐','๐','๐','โน๏ธ',
            '๐ฒ','๐ณ','๐ฅบ','๐ฆ','๐ง','๐จ','๐ฐ','๐ฅ','๐ข',
            '๐ญ','๐ฑ','๐','๐ฃ','๐','๐','๐ฉ','๐ซ','๐ฅฑ',
            '๐ค','๐ก','๐ ','๐คฌ','๐','๐ฟ','๐','โ ๏ธ','๐ฉ'
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
