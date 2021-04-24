<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

trait CacheModelListTrait
{
    public static function refreshModelList()
    {
        \Cache::forget(static::getModelListCacheKey());

        return static::getListFromCache();
    }

    public static function getListFromCache()
    {
        return \Cache::remember(static::getModelListCacheKey(), static::getListCacheTtl(), function () {
            return static::getModelList();
        });
    }

    abstract public static function getModelList();

    public static function getModelClass()
    {
        return static::class;
    }

    protected static function getModelListCacheKey()
    {
        $instance = new static();

        $cacheKey = url_cache_key();
        if (method_exists($instance, 'customModelListCacheKey')) {
            $cacheKey = $instance->customModelListCacheKey();
        }

        return sprintf("model:%s:list:%s", static::getModelClass(), $cacheKey);
    }

    /**
     * @return \Carbon\Carbon|null null to cache forever
     */
    protected static function getListCacheTtl()
    {
        return after_minutes(5);
    }
}