<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable =[
        'card_name', 'created_at', 'updated_at'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
