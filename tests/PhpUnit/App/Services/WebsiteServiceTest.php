<?php

declare(strict_types=1);

namespace PhpUnit\App\Services;

use App\Services\WebsiteService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers WebsiteService
 */
final class WebsiteServiceTest extends TestCase
{
    /**
     * @covers WebsiteService::getOneByName()
     */
    public function testGetOneByName(): void
    {
        $websiteService = new WebsiteService($this->getEntityManagerMock());

        $this->assertEquals('one_by_name', $websiteService->getOneByName('test'));
    }

    /**
     * @covers WebsiteService::getOne()
     */
    public function testGetOne(): void
    {
        $websiteService = new WebsiteService($this->getEntityManagerMock());

        $this->assertEquals('get_one', $websiteService->getOne(1));
    }

    /**
     * @covers WebsiteService::getAll()
     */
    public function testGetAll(): void
    {
        $websiteService = new WebsiteService($this->getEntityManagerMock());

        $this->assertEquals('get_all', $websiteService->getAll());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    protected function getEntityManagerMock()
    {
        $mock =  $this->createMock(EntityManager::class);
        $mock->expects($this->any())->method('getRepository')->willReturn($this->getRepositoryMock());

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityRepository
     */
    protected function getRepositoryMock()
    {
        $repoMock = $this->createMock(EntityRepository::class);
        $repoMock->expects($this->any())->method('findOneBy')->willReturn('one_by_name');
        $repoMock->expects($this->any())->method('findAll')->willReturn('get_all');
        $repoMock->expects($this->any())->method('find')->willReturn('get_one');

        return $repoMock;
    }
}