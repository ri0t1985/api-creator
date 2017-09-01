<?php

declare(strict_types=1);

namespace PhpUnit\App\Cache;

use PHPUnit\Framework\TestCase;
use App\Cache\Redis;
use Predis\Client;

/**
 * @covers Redis
 */
final class RedisTest extends TestCase
{
    public function testHas(): void
    {
        $mock = $this->getRedisMock();
        $this->assertTrue($mock->has('test'));
    }

    public function testGet(): void
    {
        $mock = $this->getRedisMock();
        $this->assertEquals('test_string', $mock->get('test'));
    }

    public function testRemove(): void
    {
        $mock = $this->getRedisMock();
        $this->assertTrue($mock->remove(['test']));
    }

    public function testStore(): void
    {
        $mock = $this->getRedisMock();
        $this->assertTrue($mock->store('test', 'test', 60));
    }


    /**
     * @return Redis
     */
    protected function getRedisMock()
    {
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('exists')->willReturn(true);
        $clientMock->method('get')->willReturn('test_string');
        $clientMock->method('del')->willReturn(true);
        $clientMock->method('set')->willReturn(true);

        $mock =  $this->createMock(Redis::class);
        $mock->method('getClient')->willReturn($clientMock);

        return $mock;
    }
}


