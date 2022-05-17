<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable =[
        'task_name', 'deadline', 'img', 'todo_id', 'created_at', 'updated_at', 'archived', 'order'
    ];

    public function tasks()
    {
        return $this->belongsTo(Todo::class);
    }


}
