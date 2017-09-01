<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Endpoint;
use PHPUnit\Framework\TestCase;

/**
 * @covers Endpoint
 */
final class EndpointTest extends TestCase
{
    /**
     * Tests the basic functionality of an end point.
     */
    public function testEndpoint(): void
    {
        $endpoint = new Endpoint();

        $this->assertEmpty($endpoint->getName());
        $this->assertEmpty($endpoint->getWebsite());

        $endpoint->setName('test_name');

        $this->assertEquals('test_name', $endpoint->getName());
    }
}