<?php

namespace ZhenMu\LaravelInitTemplate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use ZhenMu\LaravelInitTemplate\Models\BaseModel;

class Profile extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'realname',
        'mobile',
        'id_card',
    ];

    public function user()
    {
        return $this->belongsTo(config('laravel-init-template.user_model', \ZhenMu\LaravelInitTemplate\Models\User::class));
    }
}
