<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 24.10.14
 * Time: 10:48
 */

namespace Haberberger\RedisStorageBundle\Storage;

use Haberberger\RedisStorageBundle\Services\RedisStorage;

/**
 * Parent Class for all Storage Implementations
 * Class AbstractStorage
 * @package Haberberger\RedisStorageBundle\Storage
 */
abstract class AbstractStorage
{
    const PATTERN_KEY = 'STORAGE_%s_%s';

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
        return sprintf(static::PATTERN_KEY, $this->identifier, $key);
    }
}