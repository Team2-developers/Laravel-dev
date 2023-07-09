<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'game_status',
        'user_id',
        'user_2',
        'user_3',
        'user_4',
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'game_id';


    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game';

    // 以下を追加
    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user_2');
    }

    public function user3()
    {
        return $this->belongsTo(User::class, 'user_3');
    }

    public function user4()
    {
        return $this->belongsTo(User::class, 'user_4');
    }
}
