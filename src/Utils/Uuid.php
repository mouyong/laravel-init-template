<?php

namespace ZhenMu\LaravelInitTemplate\Utils;

class Uuid
{
    public static function uuid($hex = true)
    {
        if ($hex) {
            return \Ramsey\Uuid\Uuid::uuid4()->getHex()->toString();
        }

        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
