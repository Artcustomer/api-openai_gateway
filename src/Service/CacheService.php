<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @author David
 */
class CacheService
{

    protected CacheInterface $cache;
    protected ?AdapterInterface $adapter = null;
    protected bool $isCacheAvailable = false;

    /**
     * Constructor
     *
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->isCacheAvailable = true;
        $this->cache = $cache;

        if ($this->cache instanceof AbstractAdapter) {
            $this->adapter = $this->cache;
        } elseif ($this->cache instanceof TraceableAdapter) {
            $this->adapter = $this->cache->getPool();
        } else {
            // Warning : Cache is not well configured
            $this->isCacheAvailable = false;
        }
    }

    /**
     * Get item from cache
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache->get(
            $key,
            function () use ($default) {
                return $default;
            }
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set(string $key, mixed $value): bool
    {
        if (!$this->isCacheAvailable) {
            return false;
        }

        $item = $this->adapter->getItem($key);
        $item->set($value);

        return $this->adapter->save($item);
    }

    /**
     * @param string|array $keys
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete(string|array $keys): bool
    {
        if (!$this->isCacheAvailable) {
            return false;
        }

        if (is_array($keys)) {
            return $this->adapter->deleteItems($keys);
        }

        return $this->adapter->deleteItem($keys);
    }

    /**
     * @param string $key
     * @return CacheItem|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getItem(string $key): ?CacheItem
    {
        if (!$this->isCacheAvailable) {
            return null;
        }

        return $this->adapter->getItem($key);
    }

    /**
     * @return bool
     */
    public function isCacheAvailable(): bool
    {
        return $this->isCacheAvailable;
    }
}
