<?php

declare(strict_types=1);

namespace App\Tests\Unit\Facade;

use App\Facade\RedisStorageFacade;
use App\Tests\BaseApiTest;
use Predis\Client;

class RedisStorageFacadeTest extends BaseApiTest
{
    protected $redisClient;
    protected $facade;

    public function setUp(): void
    {
        $this->redisClient = $this->prophesize(Client::class);
        $this->facade = new RedisStorageFacade($this->redisClient->reveal());
    }

    public function testSet()
    {
        $this->redisClient->set('key', 'value', 'EX', 'ttl')->shouldBeCalledTimes(1);
        $this->facade->set('key', 'value', 'ttl');
    }

    public function testGet()
    {
        $this->redisClient->get('key')->shouldBeCalledTimes(1)->willReturn('value');
        $this->assertEquals('value', $this->facade->get('key'));
    }

    public function testHas()
    {
        $this->redisClient->exists('key')->shouldBeCalledTimes(1)->willReturn(1);
        $this->assertTrue($this->facade->has('key'));
    }
}
