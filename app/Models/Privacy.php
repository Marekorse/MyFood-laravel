<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
       'privacy'
    ];

    public function bookPrivacy()
    {
        return $this->morphedByMany(Book::class, 'privacyable');
    }
    public function recipePrivacy()
    {
        return $this->morphedByMany(Recipe::class, 'privacyable');
    }
}
