<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 07.11.14
 * Time: 08:53
 */

namespace Haberberger\Services;


use Haberberger\Storage\Dump\SimpleDump;

class DumpFactory extends AbstractStorageFactory
{
    /**
     * Create an instance of the required storage type
     * @param $identifier
     * @return mixed
     */
    protected function createInstance($identifier)
    {
        return new SimpleDump($identifier, $this->redis);
    }

}