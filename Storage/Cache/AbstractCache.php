<?php
/**
 * Created by IntelliJ IDEA.
 * User: andreas
 * Date: 22.10.14
 * Time: 12:31
 */

namespace Haberberger\RedisStorageBundle\Storage\Cache;

use Haberberger\RedisStorageBundle\Services\RedisStorage;
use Haberberger\RedisStorageBundle\Storage\AbstractStorage;

abstract class AbstractCache extends AbstractStorage
{
    const PATTERN_KEY = 'CACHE_%s_%s';

    /**
     * Put a value into the cache using a key
     * @param string $key         the key
     * @param string $value       the value
     * @return mixed
     */
    abstract public function put($key, $value);

    /**
     * Get a value from the cache using key
     * @param string $key the key
     * @return bool|string the value or false if the value doesn't exist
     */
    abstract public function get($key);

    /**
     * Expire a cache value identified by key
     * @param string $key the key
     * @return mixed
     */
    abstract public function expire($key);
}