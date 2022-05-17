<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'daily_visitors', 'page'
    ];
    protected $primaryKey = 'created_at';

    public function setCreatedAtAttribute($dateAndTime)
    {
        $this->attributes['created_at'] = date('Y-m-d');
    }

    public function setUpdatedAtAttribute($dateAndTime)
    {
        $this->attributes['updated_at'] = date('Y-m-d');
    }
}
