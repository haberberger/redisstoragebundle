<?php
/**
 * RedisStorage.php
 * User: ahaberberger
 * Date: 15.04.14
 * Time: 10:02
 */

namespace Haberberger\Bundle\RedisStorageBundle\Services;
use Haberberger\Bundle\RedisStorageBundle\Exceptions\RedisBundleException;
use Haberberger\Bundle\URSBundle\Model\AbstractUrsModel;
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

    public function stringWrite($key, $value)
    {
        return $this->_redis->set($key, $value);
    }

    public function stringRead($key)
    {
        return $this->_redis->get($key);
    }

    public function stringExists($key) {
        return ($this->_redis->get($key) !== null);
    }

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
     * @param string $classname
     * @param $id
     * @return mixed
     */
    public function getModel($classname, $id)
    {
        $key = sprintf('%s_%s', $classname::getClassIdentifier(), $id);
        $data = json_decode($this->stringRead($key), true);
        $meta = $data[self::KEY_META];
        $content = $data[self::KEY_CONTENT];
        $classname = $meta[self::KEY_META_CLASSNAME];
        $instance = $classname::fromArray($content);
        return $instance;
    }

    /**
     * @param AbstractUrsModel $model
     * @return mixed
     */
    public function writeModel(AbstractUrsModel $model)
    {
        $classname = get_class($model);
        $key = sprintf('%s_%s', $classname::getClassIdentifier(), $model->getId());
        $update = $this->stringExists($key);
        $meta = [
            self::KEY_META_CLASSNAME => get_class($model)
        ];
        $content = $model->toArray();
        $this->stringWrite(
            $key,
            json_encode([
                self::KEY_META => $meta,
                self::KEY_CONTENT => $content
            ])
        );
        return $update;
    }

    public function listModel($classname, $variant = self::VARIANT_FULL)
    {
        //TODO Pattern quoten
        $pattern = sprintf('%s_*', $classname::getClassIdentifier());
        $entries = $this->stringGetByPattern($pattern);
        $out = [];
        foreach ($entries as $entry) {
            $data = json_decode($entry, true);
            $meta = $data[self::KEY_META];
            $content = $data[self::KEY_CONTENT];
            $classname = $meta[self::KEY_META_CLASSNAME];
            $instance = $classname::fromArray($content);
            switch ($variant) {
                case self::VARIANT_FULL:
                    $out[] = $instance;
                    break;
                case self::VARIANT_ID:
                    $out[] = $instance->getId();
                    break;
            }

        }
        return $out;
    }

    public static function parseRedisUrl($url)
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