<?php

declare(strict_types=1);

namespace PhpUnit\App\Services;

use App\Services\SelectorService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers SelectorService
 */
final class SelectorServiceTest extends TestCase
{
    public function testGetOne(): void
    {
        $selectorService = new SelectorService($this->getEntityManagerMock());

        $this->assertEquals('get_one', $selectorService->getOne(1));
    }

    public function testGetAll(): void
    {
        $selectorService = new SelectorService($this->getEntityManagerMock());

        $this->assertEquals('get_all', $selectorService->getAll());
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

    protected function getRepositoryMock()
    {
        $repoMock = $this->createMock(EntityRepository::class);
        $repoMock->expects($this->any())->method('findAll')->willReturn('get_all');
        $repoMock->expects($this->any())->method('find')->willReturn('get_one');

        return $repoMock;
    }
}