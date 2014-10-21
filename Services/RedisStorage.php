<?php
/**
 * RedisStorage.php
 * User: ahaberberger
 * Date: 15.04.14
 * Time: 10:02
 */

namespace Haberberger\RedisStorageBundle\Services;
use Haberberger\RedisStorageBundle\Exceptions\RedisBundleException;
use Predis\Client;

/**
 * Class RedisStorage
 * @package Haberberger\Bundle\RedisStorageBundle\Services
 */
class RedisStorage
{
    const PROTOCOL_REDIS = 'redis';
    const USER_DEFAULT = '';
    const PASSWORD_DEFAULT = '';
    const HOST_DEFAULT = 'localhost';
    const PORT_DEFAULT = 6379;
    const DB_DEFAULT = 0;
    const KEY_META_CLASSNAME = 'classname';
    const KEY_META = 'meta';
    const KEY_CONTENT = 'content';
    const VARIANT_ID = 'id';
    const VARIANT_FULL = 'full';

    /** @var \Predis\Client  */
    protected $_redis;

    function __construct($url)
    {
        try {
            list (
                $user,
                $passwd,
                $host,
                $port,
                $db
                ) = self::parseRedisUrl($url);
            $this->_redis = new Client(
                array(
                    'host' => $host,
                    'port' => $port
                )
            );
        } catch (\Exception $e) {
            throw new RedisBundleException($e);
        }

        try{
            $this->_redis->connect();
            $this->_redis->select($db);
        } catch (\Predis\Connection\ConnectionException $e) {
            throw new RedisBundleException($e);
        }
    }

    /**
     * Write a string value to storage
     * @param string $key       the key
     * @param string $value     the string value to be written
     * @param null|int $timeout an optional timout in milliseconds
     * @return string redis result
     */
    public function stringWrite($key, $value, $timeout = null)
    {
        $result = $this->_redis->set($key, $value);
        if ($timeout !== null) $this->_redis->pexpire($key, $timeout);
        return $result;
    }

    /**
     * Get a string value from storage
     * @param string $key   the key
     * @return string       the key's value
     */
    public function stringRead($key)
    {
        return $this->_redis->get($key);
    }

    /**
     * Check if a key exists in storage
     * @param string $key the key
     * @return bool       true, if the key exists, false otherwise
     */
    public function stringExists($key) {
        return ($this->_redis->get($key) !== null);
    }

    /**
     * Get string values from storage using a valid key pattern
     * @param string $pattern   the pattern
     * @return array            an array of string values
     */
    public function stringGetByPattern($pattern)
    {
        $keys = $this->_redis->keys($pattern);
        $values = [];
        foreach ($keys as $key) {
            $values[] = $this->stringRead($key);
        }
        return $values;
    }

    /**
     * Parse the given redis url
     * @param string $url   the url
     * @return array        an array of config values
     * @throws RedisBundleException
     */
    protected static function parseRedisUrl($url)
    {
        $user = self::USER_DEFAULT;
        $password = self::PASSWORD_DEFAULT;
        $host = self::HOST_DEFAULT;
        $port = self::PORT_DEFAULT;
        $db = self::DB_DEFAULT;

        list($protocol, $rest) = explode('://', $url);
        if ($protocol != self::PROTOCOL_REDIS) throw new RedisBundleException(sprintf('Protocol %s is not supported!', $protocol));
        if ($rest != '') {
            if (strpos($rest, '@') !== false) list($userpart, $rest) = explode('@', $rest);
            if (strpos($rest, ':') !== false) {
                list($host, $rest) = explode(':', $rest);
                if (strpos($rest, '/') !== false) {
                    list($port, $db) = explode('/', $rest);
                } else {
                    $port = $rest;
                }
            } else {
                if (strpos($rest, '/') !== false) {
                    list($host, $db) = explode('/', $rest);
                } else {
                    $host = $rest;
                }
            }
        }

        return [$user, $password, $host, (int) $port, (int) $db];
    }
} 