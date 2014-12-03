<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 07.11.14
 * Time: 08:50
 */

namespace Haberberger\Services;

use Haberberger\RedisStorageBundle\Storage\AbstractStorage;

abstract class AbstractStorageFactory
{
    protected $redis;

    protected $instances;

    function __construct($redis)
    {
        $this->redis = $redis;
        $this->instances = [];
    }

    /**
     * Get a simple cache instance with identifier
     * @param string $identifier the identifier (an arbitrary string)
     * @return AbstractStorage
     */
    public function getInstance($identifier)
    {
        if (!array_key_exists($identifier, $this->instances)) {
            $this->instances[$identifier] = $this->createInstance($identifier);
        }

        return $this->instances[$identifier];
    }

    /**
     * Create an instance of the required storage type
     * @param $identifier
     * @return mixed
     */
    abstract protected function createInstance($identifier);
}