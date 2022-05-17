<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =[
        'name', 'position'
    ];

    public $timestamps = false;
    
    /**
     * Get the category belongs to many recipes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
    
}
