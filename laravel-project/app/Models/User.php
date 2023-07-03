<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'img_id',
        'user_mail',
        'user_name',
        'password',
        'life_id',
        'birth',
        'blood_type',
        'hobby',
        'episode1',
        'episode2',
        'episode3',
        'episode4',
        'episode5',
        'expires_at',
        'remember_token',
        'abilities',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth' => 'datetime',
        'expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $table = 'user';

    public $timestamps = false;
}