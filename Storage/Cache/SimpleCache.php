<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 22.10.14
 * Time: 10:35
 */

namespace Haberberger\RedisStorageBundle\Storage\Cache;

/**
 * A simple Cache that expires by timeout
 * Class SimpleCache
 * @package Haberberger\RedisStorageBundle\Storage\Cache
 */
class SimpleCache extends AbstractCache
{
    /** @var  int */
    protected $ttl;

    function __construct($identifier, $redis)
    {
        parent::__construct($identifier, $redis);
        $this->ttl = null;
    }

    /**
     * Put a value into the cache using a key
     * @param string $key         the key
     * @param string $value       the value
     * @param int    $overrideTtl optional time to live for a single record
     * @return string redis result
     */
    public function put($key, $value, $overrideTtl = null)
    {
        $redisKey = $this->translateKey($key);
        $timeout = ($overrideTtl === null) ? $this->ttl : $overrideTtl;
        return $this->redis->stringWrite($redisKey, $value, $timeout);
    }

    /**
     * Get a value from the cache using key
     * @param string $key the key
     * @return bool|string the value or false if the value doesn't exist
     */
    public function get($key)
    {
        $redisKey = $this->translateKey($key);
        if ($this->redis->stringExists($redisKey)) {
            return $this->redis->stringRead($redisKey);
        } else {
            return false;
        }
    }

    /**
     * Expire a cache value identified by key
     * @param string $key the key
     * @return mixed
     */
    public function expire($key)
    {
        $redisKey = $this->translateKey($key);
        return $this->redis->stringDelete($redisKey);
    }


    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }
}