<?php

namespace ZhenMu\LaravelInitTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use ZhenMu\LaravelInitTemplate\Traits\ModelSerializeDateTrait;

class BaseModel extends Model
{
    use ModelSerializeDateTrait;
}