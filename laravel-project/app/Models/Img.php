<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    use HasFactory;
    protected $fillable = [
        //ここにテーブルのカラム名を入力します。
        'img_pass',
    ];

    protected $table = 'img';
    protected $primaryKey = 'img_id';
}
