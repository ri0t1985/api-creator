<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Endpoint;
use App\Entities\Selector;
use App\Entities\Website;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entities\Endpoint
 */
final class EndpointTest extends TestCase
{
    /**
     * Tests the basic functionality of an end point.
     *
     * @covers \App\Entities\Endpoint
     */
    public function testEndpoint()
    {
        $endpoint = new Endpoint();

        $this->assertEmpty($endpoint->getId());
        $this->assertEmpty($endpoint->getName());
        $this->assertEmpty($endpoint->getWebsite());
        $this->assertEmpty($endpoint->getSelectors());

        $endpoint->setName('test_name');

        $this->assertEquals('test_name', $endpoint->getName());

        $selector = $this->createMock(Selector::class);
        $selector->expects($this->any())->method('getId')->willReturn('123456');

        $endpoint->setSelectors([$selector]);

        $this->assertEquals([$selector], $endpoint->getSelectors());

        $websiteMock = $this->createMock(Website::class);
        $websiteMock->expects($this->any())->method('getId')->willReturn('333333');

        $endpoint->setWebsite($websiteMock);

        $website = $endpoint->getWebsite();

        $this->assertEquals('333333', $website->getId());
    }
}