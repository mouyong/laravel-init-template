<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

trait CacheModelDetailTrait
{
    public static function refreshModel()
    {
        \Cache::forget(static::getModelDetailCacheKey());

        return static::getFromCache();
    }

    public static function getFromCache()
    {
        return \Cache::remember(static::getModelDetailCacheKey(), static::getDetailCacheTtl(), function () {
            return static::getModelDetail();
        });
    }

    abstract public static function getModelDetail();

    public static function getModelClass()
    {
        return static::class;
    }

    protected static function getModelDetailCacheKey()
    {
        $instance = new static();

        return sprintf("model:%s:%s:%s", static::getModelClass(), $instance->getKeyName(), $instance->getKey());
    }

    /**
     * @return \Carbon\Carbon|null null to cache forever
     */
    protected static function getDetailCacheTtl()
    {
        return after_minutes(5);
    }
}