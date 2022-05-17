<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'time', 'chunk', 'difficulty', 'amount', 'ingredients', 'text', 'approach',
        'slug', 'cretated_at', 'updated_at', 'confirmation', 'guest', 'category', 'popularity', 'privacy'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'amount' => 'array',
        'approach' => 'array',
    ];
    protected $with = array('image');

    /**
     * Get all of the comments for the recipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the recipes that owns the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function improvements()
    {
        return $this->hasMany(ImproveRecipe::class);
    }
    public function improvement()
    {
        return $this->hasOne(ImproveRecipe::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function bookRecipes()
    {
        return $this->belongsToMany(Book::class);
    }
    /**
     * Get all of the recipe images.
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function privacy()
    {
        return $this->morphToMany(Privacy::class, 'privacyable');
    }
}
