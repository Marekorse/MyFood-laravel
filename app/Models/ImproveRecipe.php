<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImproveRecipe extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'recipe_id','book_id', 'time', 'chunk', 'difficulty', 'amount', 'ingredients', 'text', 'approach',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get all of the improvedRecipe images.
    */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

}