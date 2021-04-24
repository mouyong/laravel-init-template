<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use Illuminate\Database\Eloquent\Model;

trait CacheModelListTrait
{
    use CacheModelDetailTrait;

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

    public static function getModelListCacheKey($key = null)
    {
        /** @var Model $instance */
        $instance = new static();

        $cacheKey = url_cache_key();
        if (method_exists($instance, 'customModelListCacheKey')) {
            $cacheKey = $instance->customModelListCacheKey();
        }

        if ($key) {
            $cacheKey = $key;
        }

        return sprintf("model:%s:list:%s", static::getModelClass(), $cacheKey);
    }

    /**
     * @return \Carbon\Carbon|null null to cache forever
     */
    public static function getListCacheTtl()
    {
        return after_minutes(5);
    }
}