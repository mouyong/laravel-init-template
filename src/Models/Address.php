<?php

namespace ZhenMu\LaravelInitTemplate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends BaseModel
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
