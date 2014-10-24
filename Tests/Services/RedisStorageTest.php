<?php
/**
 * Created by IntelliJ IDEA.
 * User: ahaberberger
 * Date: 24.10.14
 * Time: 14:30
 */

namespace Haberberger\RedisStorageBundle\Tests\Services;

use Haberberger\RedisStorageBundle\Services\RedisStorage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedisStorageTest extends WebTestCase
{

    public function testTest()
    {
        $this->assertEquals(1+1, 2);
    }

    public function testParseRedisUrl()
    {
        //$redisStorage = new RedisStorage('redis://');

        $in = [
            'redis://user:pass@localhost:1234/2' => ['user', 'pass', 'localhost', 1234, 2],
            'redis://user:pass@localhost:1234' => ['user', 'pass', 'localhost', 1234, 0]
        ];

        foreach ($in as $url => $expected) {
            $actual = RedisStorage::parseRedisUrl($url);
            $this->assertEquals($expected, $actual);
        }



    }
}