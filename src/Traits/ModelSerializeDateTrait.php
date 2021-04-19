<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

trait ModelSerializeDateTrait
{
    // 处理日期转换问题
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}