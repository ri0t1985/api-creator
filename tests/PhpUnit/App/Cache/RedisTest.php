<?php

declare(strict_types=1);

namespace PhpUnit\App\Cache;

use PHPUnit\Framework\TestCase;
use App\Cache\Redis;
use Predis\Client;

/**
 * @covers \App\Cache\Redis
 */
final class RedisTest extends TestCase
{
    /**
     * @covers \App\Cache\Redis::has
     */
    public function testHas(): void
    {
        $mock = $this->getRedisMock();
        $this->assertFalse($mock->has('test'));
    }

    /**
     * @covers \App\Cache\Redis::get
     */
    public function testGet(): void
    {
        $mock = $this->getRedisMock();
        $this->assertEquals('test_string', $mock->get('test'));
    }

    /**
     * @covers \App\Cache\Redis::remove()
     */
    public function testRemove(): void
    {
        $mock = $this->getRedisMock();
        $this->assertTrue($mock->remove(['test']));
    }

    /**
     * @covers \App\Cache\Redis::store
     */
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
        $clientMock = $this->getMockBuilder(Client::class)
        ->setMethods(['exists', 'get', 'del', 'set'])->getMock();
        $clientMock->expects($this->any())->method('exists')->willReturn(0);
        $clientMock->expects($this->any())->method('get')->willReturn('test_string');
        $clientMock->expects($this->any())->method('del')->willReturn(true);
        $clientMock->expects($this->any())->method('set')->willReturn(true);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Redis $mock */
        $mock = $this->getMockBuilder(Redis::class)
            ->setMethods(array('getClient'))
            ->getMock();
        $mock->expects(($this->any()))->method('getClient')->willReturn($clientMock);

        return $mock;
    }

    /**
     * @covers \App\Cache\Redis::getClient()
     */
    public function testGetClient()
    {
        $redis = new Redis();
        $this->assertInstanceOf(Client::class, $redis->getClient());
    }
}


