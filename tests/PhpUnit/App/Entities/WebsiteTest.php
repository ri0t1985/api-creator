<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Website;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entities\Website
 */
final class WebsiteTest extends TestCase
{
    /**
     * Tests the basic website functionality
     *
     * @covers \App\Entities\Website
     */
    public function testWebsite()
    {
        $website = new Website();

        $this->assertEmpty($website->getName());
        $this->assertEmpty($website->getUrl());
        $this->assertEmpty($website->getUrlHash());
        $this->assertEmpty($website->getEndpoints());
        $this->assertEmpty($website->getId());

        $website->setName('test_name');
        $website->setUrl('test_url');
        $website->setUrlHash('url_hash');

        $this->assertEquals('test_name', $website->getName());
        $this->assertEquals('test_url',  $website->getUrl());
        $this->assertEquals('url_hash',  $website->getUrlHash());
    }
}