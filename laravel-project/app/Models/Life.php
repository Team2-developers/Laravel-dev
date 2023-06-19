<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Life extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'life_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'life_name',
        'life_detail',
        'message',
        'user_id',
        'good',
    ];

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    protected $table = 'life';

    public $timestamps = false;

    /**
     * Get the user that owns the life.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }


    public function trouts()
    {
        return $this->hasMany(Trout::class, 'life_id', 'life_id');
    }
}
