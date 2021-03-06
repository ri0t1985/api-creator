<?php

declare(strict_types=1);

namespace PhpUnit\App\Services;

use App\Services\EndPointService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Services\EndPointService
 */
final class EndpointServiceTest extends TestCase
{
    /**
     * @covers \App\Services\EndPointService::getOneByName()
     */
    public function testGetOneByName()
    {
        $endpointService = new EndPointService($this->getEntityManagerMock());

        $this->assertEquals('one_by_name', $endpointService->getOneByName('test'));
    }

    /**
     * @covers \App\Services\EndPointService::getOne()
     */
    public function testGetOne()
    {
        $endpointService = new EndPointService($this->getEntityManagerMock());

        $this->assertEquals('get_one', $endpointService->getOne(1));
    }

    /**
     * @covers \App\Services\EndPointService::getAll()
     */
    public function testGetAll()
    {
        $endpointService = new EndPointService($this->getEntityManagerMock());

        $this->assertEquals('get_all', $endpointService->getAll());
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