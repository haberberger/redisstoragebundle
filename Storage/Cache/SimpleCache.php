<?php
/**
 * Created by IntelliJ IDEA.
 * User: andreas
 * Date: 22.10.14
 * Time: 10:35
 */

namespace Haberberger\RedisStorageBundle\Storage\Cache;

class SimpleCache extends AbstractCache
{
    /** @var  int */
    protected $ttl;

    function __construct($identifier, $redis)
    {
        parent::__construct($identifier, $redis);
        $this->ttl = null;
    }


    public function set($key, $value)
    {
        $redisKey = $this->translateKey($key);
        $this->redis->stringWrite($redisKey, $value, $this->ttl);
    }

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