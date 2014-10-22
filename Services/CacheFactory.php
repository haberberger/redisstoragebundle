<?php
/**
 * Created by IntelliJ IDEA.
 * User: andreas
 * Date: 22.10.14
 * Time: 10:21
 */

namespace Haberberger\RedisStorageBundle\Services;


class CacheFactory
{
    protected $redis;

    function __construct($redis)
    {
        $this->redis = $redis;
    }

}