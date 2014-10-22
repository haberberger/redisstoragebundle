<?php
/**
 * Created by IntelliJ IDEA.
 * User: andreas
 * Date: 22.10.14
 * Time: 12:31
 */

namespace Haberberger\RedisStorageBundle\Storage\Cache;

use Haberberger\RedisStorageBundle\Services\RedisStorage;

class AbstractCache
{
    const PATTERN_KEY = 'CACHE_%s_%s';

    /** @var  RedisStorage */
    protected $redis;

    /** @var  string */
    protected $identifier;

    function __construct($identifier, $redis)
    {
        $this->identifier = $identifier;
        $this->redis = $redis;
    }

    protected function translateKey($key)
    {
        return sprintf(self::PATTERN_KEY, $this->identifier, $key);
    }
}