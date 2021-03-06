<?php

declare(strict_types=1);

namespace PhpUnit\App\Services;

use App\Services\DatabaseServiceContainer;
use App\Services\EndPointService;
use App\Services\SelectorService;
use App\Services\WebsiteService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Services\DatabaseServiceContainer
 */
final class DatabaseServiceContainerTest extends TestCase
{
    /**
     * @covers \App\Services\DatabaseServiceContainer::getEndPointService()
     */
    public function testGetEndpointService()
    {
        $databaseServiceContainer = new DatabaseServiceContainer($this->getEntityManagerMock());
        $endpointService = new EndPointService($this->getEntityManagerMock());
        $this->assertEquals($endpointService, $databaseServiceContainer->getEndPointService());
    }

    /**
     * @covers \App\Services\DatabaseServiceContainer::getWebsiteService()
     */
    public function testGetWebsiteService()
    {
        $databaseServiceContainer = new DatabaseServiceContainer($this->getEntityManagerMock());
        $websiteService = new WebsiteService($this->getEntityManagerMock());
        $this->assertEquals($websiteService, $databaseServiceContainer->getWebsiteService());
    }

    /**
     * @covers \App\Services\DatabaseServiceContainer::getSelectorService()
     */
    public function testGetSelectorService()
    {
        $databaseServiceContainer = new DatabaseServiceContainer($this->getEntityManagerMock());
        $selectorService = new SelectorService($this->getEntityManagerMock());
        $this->assertEquals($selectorService, $databaseServiceContainer->getSelectorService());
    }

    /**
     * @covers \App\Services\DatabaseServiceContainer::getConnection()
     */
    public function testGetConnection()
    {
        $databaseServiceContainer = new DatabaseServiceContainer($this->getEntityManagerMock());
        $this->assertEquals('connection_string', $databaseServiceContainer->getConnection());
    }

    /**
     * @return EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEntityManagerMock()
    {
        $mock =  $this->createMock(EntityManager::class);
        $mock->expects($this->any())->method('getConnection')->willReturn('connection_string');

        return $mock;
    }
}