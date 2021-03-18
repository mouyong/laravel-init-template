<?php

namespace ZhenMu\LaravelInitTemplate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'province',
        'city',
        'county',
        'address',
        'longitude',
        'latitude',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
