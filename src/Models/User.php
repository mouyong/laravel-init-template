<?php

namespace ZhenMu\LaravelInitTemplate\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use ZhenMu\LaravelInitTemplate\Traits\JwtUserTrait;
use ZhenMu\LaravelInitTemplate\Traits\ModelSerializeDateTrait;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    use JwtUserTrait;
    use ModelSerializeDateTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'realname',
        'mobile',
        'avatar',
        'id_card',
        'email',
        'password',
        'ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
