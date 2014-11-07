<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 07.11.14
 * Time: 08:48
 */

namespace Haberberger\Storage\Dump;


use Haberberger\RedisStorageBundle\Storage\AbstractStorage;

class SimpleDump extends AbstractStorage
{
    const PATTERN_KEY = 'DUMP_%s_%s';

    /**
     * Dump a value in key
     * @param $key
     * @param $value
     * @return string
     */
    public function dump($key, $value)
    {
        $redisKey = $this->translateKey($key);
        return $this->redis->stringWrite($redisKey, $value);
    }

    /**
     * Retrieve a value from key
     * @param $key
     * @return bool|string
     */
    public function retrieve($key)
    {
        $redisKey = $this->translateKey($key);
        if ($this->redis->stringExists($redisKey)) {
            return $this->redis->stringRead($redisKey);
        } else {
            return false;
        }
    }
}