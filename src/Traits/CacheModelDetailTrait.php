<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use Illuminate\Database\Eloquent\Model;

trait CacheModelDetailTrait
{
    public static function refreshModelDetail()
    {
        \Cache::forget(static::getModelDetailCacheKey());

        return static::getFromDetailCache();
    }

    public static function getFromDetailCache()
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

    public static function getModelDetailCacheKey()
    {
        /** @var Model $instance */
        $instance = new static();

        $cacheKeyName = $instance->getKeyName();
        if (method_exists($instance, 'customModelDetailKeyNameCacheKey')) {
            $cacheKeyName = $instance->customModelDetailKeyNameCacheKey();
        }

        $cacheKey = url_cache_key();
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