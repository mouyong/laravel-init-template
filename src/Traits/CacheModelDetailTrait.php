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

        $cacheKeyName = $instance->getKeyName();
        if (method_exists($instance, 'customModelDetailKeyNameCacheKey')) {
            $cacheKey = $instance->customModelDetailKeyNameCacheKey();
        }

        $cacheKey = $instance->getKey();
        if (method_exists($instance, 'customModelDetailCacheKey')) {
            $cacheKey = $instance->customModelDetailCacheKey();
        }

        return sprintf("model:%s:detail:%s:%s", static::getModelClass(), $cacheKeyName, $cacheKey);
    }

    /**
     * @return \Carbon\Carbon|null null to cache forever
     */
    protected static function getDetailCacheTtl()
    {
        return after_minutes(5);
    }
}