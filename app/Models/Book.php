<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable =[
        'name','slug','privacy' 
    ];

    public $timestamps = false;
    protected $with = array('privacy');
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function bookRecipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
    public function privacy()
    {
        return $this->morphToMany(Privacy::class, 'privacyable');
    }
}
