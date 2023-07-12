<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trout extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'trout_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'trout_detail',
        'life_id',
        'seqno',
        'point',
        'color',
    ];

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    protected $table = 'trout';

    public $timestamps = false;

    /**
     * Get the life that owns the trout.
     */
    public function life()
    {
        return $this->belongsTo(Life::class, 'life_id', 'life_id');
    }

    public function updateTrout($data)
    {
        if ($this) {
            $this->trout_detail = $data['trout_detail'];
            $this->point = $data['point'];
            $this->color = $data['color'];
            $this->save();
        }
    }
}
