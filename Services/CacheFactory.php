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
use Haberberger\Services\AbstractStorageFactory;

class CacheFactory extends AbstractStorageFactory
{
    protected function createInstance($identifier)
    {
        return new SimpleCache($identifier, $this->redis);
    }
}