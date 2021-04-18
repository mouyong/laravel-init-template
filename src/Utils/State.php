<?php

namespace ZhenMu\LaravelInitTemplate\Utils;

class State
{
    public static function isEnable(int $flag, int $status)
    {
        return ($flag & $status) === $status;
    }

    public static function isDisable(int $flag, int $status)
    {
        return ($flag & $status) === 0;
    }

    public static function isOnlyEnable(int $flag, int $status)
    {
        return $flag === $status;
    }

    public static function addStatus(int $flag, int $status)
    {
        return $flag | $status;
    }

    public static function deductStatus(int $flag, int $status)
    {
        return $flag & ~$status;
    }
}