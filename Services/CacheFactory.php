<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 22.10.14
 * Time: 10:21
 */

namespace Haberberger\RedisStorageBundle\Services;


use Haberberger\RedisStorageBundle\Storage\Cache\SimpleCache;
use Haberberger\RedisStorageBundle\Storage\Cache\AbstractCache;

class CacheFactory
{
    protected $redis;

    protected $instances;

    function __construct($redis)
    {
        $this->redis = $redis;
        $this->instances = [];
    }

    /**
     * Get a simple cache instance with identifier
     * @param string $identifier the identifier (an arbitrary string)
     * @return AbstractCache
     */
    public function getInstance($identifier)
    {
        if (!array_key_exists($identifier, $this->instances)) {
            $this->instances[$identifier] = $this->createInstance($identifier);
        }

        return $this->instances[$identifier];
    }

    protected function createInstance($identifier)
    {
        return new SimpleCache($identifier, $this->redis);
    }

}