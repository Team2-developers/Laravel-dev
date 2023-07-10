<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'life_id', 'user_id', 'comment'
    ];

    public function life()
    {
        return $this->belongsTo('App\Life');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'comment_id';


    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';
}
