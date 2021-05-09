<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Simple\FilesystemCache;

trait InteractsWithCache
{
    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * Get cache instance.
     *
     * @return \Psr\SimpleCache\CacheInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getCache()
    {
        if ($this->cache) {
            return $this->cache;
        }

        if (property_exists($this, 'app') && $this->app instanceof \Pimple\Container && isset($this->app['cache'])) {
            $this->setCache($this->app['cache']);

            // Fix PHPStan error
            assert($this->cache instanceof \Psr\SimpleCache\CacheInterface);

            return $this->cache;
        }

        if (property_exists($this, 'app') && $this->app instanceof \Illuminate\Container\Container && $this->app->bound('cache')) {
            $this->setCache($this->app['cache']->driver());

            // Fix PHPStan error
            assert($this->cache instanceof \Psr\SimpleCache\CacheInterface);

            return $this->cache;
        }

            return $this->cache = $this->createDefaultCache();
    }

    /**
     * Set cache instance.
     *
     * @param \Psr\SimpleCache\CacheInterface|\Psr\Cache\CacheItemPoolInterface $cache
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setCache($cache)
    {
        if (empty(\array_intersect([SimpleCacheInterface::class, CacheItemPoolInterface::class], \class_implements($cache)))) {
            throw new \InvalidArgumentException(\sprintf('The cache instance must implements %s or %s interface.', SimpleCacheInterface::class, CacheItemPoolInterface::class));
        }

        if ($cache instanceof CacheItemPoolInterface) {
            if (!$this->isSymfony43OrHigher()) {
                throw new \InvalidArgumentException(sprintf('The cache instance must implements %s', SimpleCacheInterface::class));
            }
            $cache = new Psr16Cache($cache);
        }

        $this->cache = $cache;

        return $this;
    }

    /**
     * @return SimpleCacheInterface|FilesystemCache
     */
    protected function createDefaultCache()
    {
        if ($this->isSymfony43OrHigher()) {
            return new Psr16Cache(new FilesystemAdapter('easywechat', 1500));
        }

        return new FilesystemCache();
    }

    /**
     * @return bool
     */
    protected function isSymfony43OrHigher(): bool
    {
        return \class_exists('Symfony\Component\Cache\Psr16Cache');
    }
}
